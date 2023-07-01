<?php
require_once './app/models/paises.model.php';
require_once './app/views/mundial.api.view.php';
require_once './libs/authHelper.php';
Class paisesApiController{
    private $model;
    private $view;
    private $data;

    public function __construct(){
        $this->model = new paisesModel(); 
        $this->view = new mundialApiView(); 
        $this->data = file_get_contents("php://input"); // lee el body del request
    }

    private function obtenerDatos() {
        return json_decode($this->data);
    }
    
    /*--Si el :ID cumple con las condiciones busca al jugador con dicho id, para mostrarlo junto al código correspondiente--*/
    public function obtenerPais($params){
        $id = $params[':ID'];
        if(is_numeric($id) && $id > 0){
            $pais = $this->model->obtenerPais($id);
            if($pais) 
                return $this->view->response($pais, 200);
            else
                return $this->view->response("El pais con el id ".$id." no existe", 404);
        }else
            return $this->view->response("Por favor verifique los datos ingresados", 404);
    }

    /*--Verifica si está seteado el orden, el filtro, la página o ninguno--*/
    public function obtenerPaises(){
        /*if (isset($_REQUEST['criterio']))
            $paises = $this->obtenerJugadoresOrdenados($_REQUEST['criterio']); 
        else if (isset($_REQUEST['filtrar']))
            $paises = $this->obtenerJugadoresFiltrados($_REQUEST['filtrar']); 
        else if(isset($_REQUEST['pagina']) && isset($_REQUEST['filas']))
            $paises=$this->paginar($_REQUEST['pagina'],$_REQUEST['filas']);
        else*/
            $paises = $this->model->obtenerPaises();  
        /*--En cualquiera de los casos muestra la vista adecuada--*/
        if ($paises != null)
            return $this->view->response($paises, 200);
        else
            return $this->view->response("No se encontraron paises", 404); 
    }

    /*--Verifica que los atributos sean correctos, obtiene la sentencia y la ejecuta en el modelo--*/
    /*public function obtenerJugadoresOrdenados($criterio){
        if($this->verificarAtributos($criterio)){
            if(isset($_REQUEST['orden']) &&  !empty($_REQUEST['orden'])){
                $orden = $_REQUEST['orden'];
                $sql = "SELECT * FROM jugadores ORDER BY $criterio $orden";
            }else
                $sql = "SELECT * FROM jugadores ORDER BY $criterio"; //lo llamaria ascendentemente por defecto   
            return $this->model->obtenerJugadoresOrdenados($sql);
        }
        else
            return $this->view->response("Verificar la columna/atributo de la tabla elegida como criterio", 404);
    }*/

    /*--Si el filtro es correcto retorna los jugadores obtenidos que cumplen con dicho filtro--*/
    /*public function obtenerJugadoresFiltrados($filtro){ 
        if ($this->verificarAtributos($filtro) && isset($_REQUEST['valor'])){
            $sql = "SELECT * FROM jugadores WHERE $filtro = :valor";
            return $this->model->obtenerJugadoresFiltrados($sql, $_REQUEST['valor']);    
        }    //no agregue un mensaje porque se pisa con el de obtener jugadores
    }    */  
    
    /*--Verifica que los datos sean correctos para poder paginar adecuadamente--*/
    /*public function paginar($pagina,$filas){ 
        if(!empty($pagina) && !empty($filas) && $pagina>0 && $filas>0 && is_numeric($pagina) && is_numeric($filas)){
            $cantidad = $this->model->obtenerTotalDeRegistros();
            if($pagina <= $cantidad/$filas){
                $inicio=$filas*($pagina-1);
                $sql = "SELECT * FROM jugadores LIMIT $inicio, $filas";
                return $this -> model -> paginar($sql); 
            }
            else
                return $this->view->response("la pagina pedida con esa cantidad de filas no contiene elementos", 404);
        }
        //else se pisa el mensaje con el de obtener jugadores
          //  return $this->view->response("Verificar la forma de los parametros utilizados", 404);
    }*/

    /*--Si los datos ingresados no están vacíos agrega al jugador--*/
    public function agregarPais(){
        $this -> comprobarUsuarioValido();
        $pais = $this->verificarDatosPais();
        $id = $this->model->agregarPais($pais); 
        $pais = $this->model->obtenerPais($id);
        if($pais)
            return $this->view->response($pais, 201);
        else
            return $this->view->response("El pais no se pudo agrear con éxito", 400); 
    }

    /*--Si el id cumple con los requisitos se solicita eliminar el jugador y se retorna la respuesta con el código correspondiente--*/
    public function eliminarPais($params){
        $this -> comprobarUsuarioValido();
        if(isset($params[':ID']) && is_numeric($params[':ID']) && $params[':ID'] > 0){
            $id = $params[':ID'];
            if($this->model->obtenerPais($id)){
                $this->model->eliminarPais($id);  
                return $this->view->response("El pais con el id ".$id." se eliminó con éxito", 200);
            }else
                return $this->view->response("El pais no se pudo eliminar, porque no existe el id ".$id, 400);
        }
        else
            return $this->view->response("Por favor verifique el id ingresado", 404);
    }

    /*--Verifica que exista el jugador con el id y si es así lo actualiza con los datos previamente comprobados--*/
    public function actualizarPais($params){
        $this -> comprobarUsuarioValido();
        if(isset($params[':ID']) && is_numeric($params[':ID']) && $params[':ID'] > 0){
            $id = $params[':ID'];
            if($this->model->obtenerPais($id)){
                $pais = $this->verificarDatosPais();
                $this->model->actualizarPais($pais, $id);   
                return $this->view->response("El pais con el id ".$id." se actualizó con éxito", 200);       
            }else   
                return $this->view->response("No existe ningún Pais con el id ingresado", 400);
        }else    
            return $this->view->response("Por favor verifique que el id se ingresó correctamente", 400);
    }
    
    /*--Verifica que los datos ingresados en el body de la request sean no esten vacíos y sean correctos*/
    private function verificarDatosPais(){
        $pais = $this->obtenerDatos();
        $nombrePaises= $this -> model -> obtenerNombrePaises();
        $clasificacionPaises= $this -> model -> obtenerclasificacionPaises();
        if (empty($pais->nombre) || empty($pais->continente) || empty($pais->clasificacion) || 
            empty($pais->bandera)){
                return $this->view->response("Por favor complete todos los datos", 400);
        }else{
            if(in_array ($pais->nombre, array_column($nombrePaises, 'nombre')) && in_array ($pais->clasificacion, array_column($clasificacionPaises, 'clasificacion')) ) // podriamos hacer una sola funcion que muestre esas dos columnas
                return  $pais;
            else 
                return $this->view->response("El id del pais no es correcto", 400);
        }  
    }

    /*--Verifica que los campos ingresados para filtrar u ordenar coincidan con los de la bbdd--*/
    /*public function verificarAtributos($filtro){
        $atributos = $this->model->obtenerColumnas();
        return (in_array($filtro, array_column($atributos, 'column_name')));
    }*/

    public function comprobarUsuarioValido(){
        $helper = new usuariosHelper();
        if(!($helper->validarPermisos())){
            $this -> view -> response("no posee permisos para realizar esta accion",401);
            die();
        }
    }
    
}