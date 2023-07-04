<?php
require_once './app/models/jugadores.model.php';
require_once './app/models/paises.model.php';
require_once './app/views/mundial.api.view.php';
require_once './helper/authHelper.php';
Class jugadoresApiController{
    private $model;
    private $view;
    private $modelPaises;
    private $data;

    public function __construct(){
        $this->model = new jugadoresModel(); 
        $this->modelPaises = new paisesModel();
        $this->view = new mundialApiView(); 
        $this->data = file_get_contents("php://input"); // lee el body del request
    }

    private function obtenerDatos() {
        return json_decode($this->data);
    }
    
    /*--Si el :ID cumple con las condiciones busca al jugador con dicho id, para mostrarlo junto al código correspondiente--*/
    public function obtenerJugador($params){
        $id = $params[':ID'];
        if(is_numeric($id) && $id > 0){
            $jugador = $this->model->obtenerJugador($id);
            if($jugador) 
                return $this->view->response($jugador, 200);
            else
                return $this->view->response("El jugador con el id ".$id." no existe", 404);
        }else
            return $this->view->response("Por favor verifique los datos ingresados", 400);
    }

    /*--Verifica si está seteado el orden, el filtro, la página o ninguno--*/
    public function obtenerJugadores(){
        if (isset($_REQUEST['criterio']))
            $jugadores = $this->obtenerJugadoresOrdenados($_REQUEST['criterio']); 
        else if (isset($_REQUEST['filtrar']))
            $jugadores = $this->obtenerJugadoresFiltrados($_REQUEST['filtrar']); 
        else if(isset($_REQUEST['pagina']) && isset($_REQUEST['filas']))
            $jugadores=$this->paginar($_REQUEST['pagina'],$_REQUEST['filas']);
        else
            $jugadores = $this->model->obtenerJugadores();  
        /*--En cualquiera de los casos muestra la vista adecuada--*/
        if ($jugadores != null)
            return $this->view->response($jugadores, 200); 
    }

    /*--Verifica que los atributos sean correctos, obtiene la sentencia y la ejecuta en el modelo--*/
    public function obtenerJugadoresOrdenados($criterio){
        if($this->verificarAtributos($criterio)){
            if(isset($_REQUEST['orden'])) {
                $orden = $_REQUEST['orden'];
                if($orden == null || $orden =="asc" || $orden =="ASC" || $orden == "desc" || $orden == "DESC"){
                    $sql = "SELECT * FROM jugadores ORDER BY $criterio $orden";
                    return $this->model->obtenerJugadoresOrdenados($sql);
                }else
                    return $this->view->response("Verificar el orden elegido", 400);
            }
            else{
                $sql = "SELECT * FROM jugadores ORDER BY $criterio";
                return $this->model->obtenerJugadoresOrdenados($sql);
            }
        }
        else
            return $this->view->response("Verificar el criterio y/o valor ingresados", 400);
    }

    /*--Si el filtro es correcto retorna los jugadores obtenidos que cumplen con dicho filtro--*/
    public function obtenerJugadoresFiltrados($filtro){ //listo
        if ($this->verificarAtributos($filtro) && isset($_REQUEST['valor'])){
            $sql = "SELECT * FROM jugadores WHERE $filtro = :valor";
            $jugadoresFiltrados= $this->model->obtenerJugadoresFiltrados($sql, $_REQUEST['valor']);
            if($jugadoresFiltrados==null)
                return $this->view->response("No hay ningun jugador con ese valor", 404);
            else
            return $jugadoresFiltrados;
            }
        else
            return $this->view->response("Verificar el filtro elegido como criterio y el valor ingresado", 400);
    }      
    
    /*--Verifica que los datos sean correctos para poder paginar adecuadamente--*/
    public function paginar($pagina,$filas){ 
        if(!empty($pagina) && !empty($filas) && $pagina>0 && $filas>1 && is_numeric($pagina) && is_numeric($filas)){
            $cantidad = $this->model->obtenerTotalDeRegistros();
            if($pagina <= $cantidad/$filas){
                $inicio=$filas*($pagina-1);
                $sql = "SELECT * FROM jugadores LIMIT $inicio, $filas";
                return $this -> model -> paginar($sql); 
            }
            else
                return $this->view->response("La página pedida con esa cantidad de filas no contiene elementos", 404);
        }else 
            return $this->view->response("Verificar que los parámetros utilizados sean correctos. Ver más información en la documentación", 400);
    }

    /*--Si los datos ingresados no están vacíos agrega al jugador--*/
    public function agregarJugador(){
        $this -> comprobarUsuarioValido();
        $jugadorCargado = $this->verificarDatosJugador();
        if($jugadorCargado){
            $id = $this->model->agregarJugador($jugadorCargado); 
            $jugador = $this->model->obtenerJugador($id);
            if($jugador)
                return $this->view->response($jugador, 201);
            else
                return $this->view->response("El jugador no se pudo agregar con éxito", 500); 
        }
    }

    /*--Si el id cumple con los requisitos se solicita eliminar el jugador y se retorna la respuesta con el código correspondiente--*/
    public function eliminarJugador($params){
        $this -> comprobarUsuarioValido();
        if(isset($params[':ID']) && is_numeric($params[':ID']) && $params[':ID'] > 0){
            $id = $params[':ID'];
            if($this->model->obtenerJugador($id)){
                $this->model->eliminarJugador($id); 
                return $this->view->response("El jugador con el id ".$id." se eliminó con éxito", 200);
            }else
                return $this->view->response("El jugador no se pudo eliminar, porque no existe el id ".$id, 404);
        }
        else
            return $this->view->response("Por favor verifique el id ingresado", 400);
    }

    /*--Verifica que exista el jugador con el id y si es así lo actualiza con los datos previamente comprobados--*/
    public function actualizarjugador($params){
        $this -> comprobarUsuarioValido();
        if(isset($params[':ID']) && is_numeric($params[':ID']) && $params[':ID'] > 0){
            $id = $params[':ID'];
            if($this->model->obtenerJugador($id)){
                $jugador = $this->verificarDatosJugador();
                $this->model->actualizarJugador($jugador, $id);  
                return $this->view->response($jugador, 200);       
            }else   
                return $this->view->response("No existe ningún jugador con el id ingresado", 404);
        }else    
            return $this->view->response("Por favor verifique que el id se ingresó correctamente", 400);
    }
    
    /*--Verifica que los datos ingresados en el body de la request sean no esten vacíos y sean correctos*/
    private function verificarDatosJugador(){
        $id_paises = $this->modelPaises->obtenerIdPaises();
        $jugador = $this->obtenerDatos();
        if (empty($jugador->nombre) || empty($jugador->apellido) || empty($jugador->descripcion) || 
            empty($jugador->posicion)|| empty($jugador->foto) || empty($jugador->id_pais)){
            return $this->view->response("Por favor complete todos los datos", 400);
        }else if(($jugador->posicion!="Arquero") || ($jugador->posicion!="Defensor") || ($jugador->posicion!="Medio campista") || ($jugador->posicion!="Delantero")){
            return $this->view->response("Por favor complete la posicion con una opcion valida", 400);
        }else{
            if(in_array($jugador->id_pais, array_column($id_paises, 'id')))
                return $jugador; 
            else 
                return $this->view->response("El id del pais no es correcto", 400);
        }
    }

    /*--Verifica que los campos ingresados para filtrar u ordenar coincidan con los de la bbdd--*/
    public function verificarAtributos($filtro){
        $atributos = $this->model->obtenerColumnas();
        return (in_array($filtro, array_column($atributos, 'column_name')));
    }

    public function comprobarUsuarioValido(){
        $helper = new usuariosHelper();
        if(!($helper->validarPermisos())){
            $this -> view -> response("no posee permisos para realizar esta accion",401);
            die();
        }
    }
    
}