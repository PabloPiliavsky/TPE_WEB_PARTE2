<?php
require_once './app/models/paises.model.php';
require_once './app/models/jugadores.model.php';
require_once './app/views/mundial.api.view.php';
require_once './helper/authHelper.php';

Class paisesApiController{
    private $model;
    private $modelJugadores;
    private $view;
    private $data;

    public function __construct(){
        $this->model = new paisesModel(); 
        $this->modelJugadores = new jugadoresModel(); 
        $this->view = new mundialApiView(); 
        $this->data = file_get_contents("php://input"); // lee el body del request
    }

    private function obtenerDatos() {
        return json_decode($this->data);
    }
    
    /*--Si el :ID cumple con las condiciones busca el pais con dicho id, para mostrarlo junto al código correspondiente--*/
    public function obtenerPais($params){ 
        $id = $params[':ID'];
        if(is_numeric($id) && $id > 0){
            $pais = $this->model->obtenerPais($id);
            if($pais) 
                return $this->view->response($pais, 200);
            else
                return $this->view->response("El pais con el id ".$id." no existe", 404);
        }else
            return $this->view->response("Por favor verifique los datos ingresados", 400);
    }

    /*--Verifica si está seteado el orden, el filtro, la página o ninguno--*/
    public function obtenerPaises(){
        if (isset($_REQUEST['criterio']))
            $paises = $this->obtenerPaisesOrdenados($_REQUEST['criterio']); 
        else if (isset($_REQUEST['filtrar']))
            $paises = $this->obtenerPaisesFiltrados($_REQUEST['filtrar']);  
        else
            $paises = $this->model->obtenerPaises();  
        /*--En cualquiera de los casos muestra la vista adecuada--*/
        if ($paises != null)
            return $this->view->response($paises, 200);
    }

    /*--Verifica que los atributos sean correctos, obtiene la sentencia y la ejecuta en el modelo--*/
    public function obtenerPaisesOrdenados($criterio){ 
        if($this->verificarAtributos($criterio)){
            if(isset($_REQUEST['orden'])){
                $orden = $_REQUEST['orden'];
                if($orden == null || $orden =="asc" || $orden =="ASC" || $orden == "desc" || $orden == "DESC"){
                $sql = "SELECT * FROM paises ORDER BY $criterio $orden";
                return $this->model->obtenerPaisesOrdenados($sql);
                }else
                    return $this->view->response("Verificar el orden elegido", 400);
            }else{
                $sql = "SELECT * FROM paises ORDER BY $criterio";
                return $this->model->obtenerPaisesOrdenados($sql);
            }
        }else
            return $this->view->response("Verificar el criterio elegido y/o el valor ingresado", 400);
    }


    /*--Si el filtro es correcto retorna los Paises obtenidos que cumplen con dicho filtro--*/
    public function obtenerPaisesFiltrados($filtro){  
        if ($this->verificarAtributos($filtro) && isset($_REQUEST['valor'])){
            $sql = "SELECT * FROM paises WHERE $filtro = :valor";
            $paisesFiltrados=$this->model->obtenerPaisesFiltrados($sql, $_REQUEST['valor']);
            if($paisesFiltrados == null)
                return $this->view->response("No hay ningun pais con ese valor", 404);
            else
                return $paisesFiltrados;     
        }else
            return $this->view->response("Verificar el filtro elegido como criterio y/o el valor ingresado", 400);
    }    

    /*--Si los datos ingresados no están vacíos agrega al jugador--*/
    public function agregarPais(){ 
        $this -> comprobarUsuarioValido();
        $pais = $this->verificarDatosPais();
        if($this->model->verificarPaisExistente($pais->clasificacion, $pais->nombre))
            return $this->view->response("El país o la clasificación ya existen", 400);
        else{
            $id = $this->model->agregarPais($pais); 
            $paisAgregado = $this->model->obtenerPais($id);
            if($paisAgregado)
                return $this->view->response($paisAgregado, 201);
            else
                return $this->view->response("El país no se pudo agregar con éxito", 500); 
        }
    }

    /*--Si el id cumple con los requisitos se solicita eliminar el jugador y se retorna la respuesta con el código correspondiente--*/
    public function eliminarPais($params){  
        $this -> comprobarUsuarioValido();
        if(isset($params[':ID']) && is_numeric($params[':ID']) && $params[':ID'] > 0){
            $id = $params[':ID'];
            if($this->modelJugadores->obtenerJugadoresPorPais($id) != null){
                return $this->view->response("El pais no se pudo eliminar, porque contiene registros vinculados", 400);
            }else{
                if($this->model->obtenerPais($id)){
                    $this->model->eliminarPais($id);  
                    return $this->view->response("El pais con el id ".$id." se eliminó con éxito", 200);
                }else
                    return $this->view->response("El pais no se pudo eliminar, porque no existe el id ".$id, 404);
            }
        }
        else
            return $this->view->response("Por favor verifique el id ingresado", 400);
    }

    /*--Verifica que exista el jugador con el id y si es así lo actualiza con los datos previamente comprobados--*/
    public function actualizarPais($params){
        $this -> comprobarUsuarioValido();
        if(isset($params[':ID']) && is_numeric($params[':ID']) && $params[':ID'] > 0){
            $id = $params[':ID'];
            $pais = $this->verificarDatosPais(); 
            if($pais){
                $verifica = $this->verificarPaisExistente($pais->clasificacion,$pais->nombre, $id);
                if($verifica)
                    $this->model->actualizarPais($pais, $id);   
                    return $this->view->response($pais, 200); 
            }                             
        }else    
            return $this->view->response("Por favor verifique que el id se ingresó correctamente", 400);
    }
    
    /*--Verifica que los datos ingresados en el body de la request sean no esten vacíos y sean correctos*/
    private function verificarDatosPais(){
        $pais = $this->obtenerDatos();
        if (empty($pais->nombre) || empty($pais->continente) || empty($pais->clasificacion) || empty($pais->bandera))
            return $this->view->response("Por favor complete todos los datos", 400);
        else if($pais->continente != "America" && $pais->continente != "Africa" && $pais->continente != "Europa" && $pais->continente != "Asia" && $pais->continente != "Oceania")
            return $this->view->response("El continente indicado no existe", 400);
        else
            return $pais;
    }

     /*--Verifica que el país a editar no repita el nombre o una clasificación existentes */
     public function verificarPaisExistente($clasificacion, $nombre, $id){
        $paisEditar = $this->model->obtenerPais($id);
        if($paisEditar){
            if($paisEditar->nombre == $nombre){
                if($paisEditar->clasificacion == $clasificacion)
                    return true;
                else
                    $existe = $this->model->verificarPaisExistente($clasificacion, null);   
            }else{
                if($paisEditar->clasificacion == $clasificacion)
                    $existe = $this->model->verificarPaisExistente(null, $nombre);
                else
                    $existe =  $this->model->verificarPaisExistente($clasificacion, $nombre);
            }
            if($existe)
                return $this->view->response("El nombre o la clasificación no se pueden repetir", 400);
        }else   
            return $this->view->response("No existe ningún país con el id ingresado", 404);
    }

    /*--Verifica que los campos ingresados para filtrar u ordenar coincidan con los de la bbdd--*/
    public function verificarAtributos($filtro){
        $atributos = $this->model->obtenerColumnas();
        return (in_array($filtro, array_column($atributos, 'column_name')));
    }

    public function comprobarUsuarioValido(){
        $helper = new usuariosHelper();
        if(!($helper->validarPermisos())){
            $this->view->response("no posee permisos para realizar esta accion",401);
            die();
        }
    }
    
}