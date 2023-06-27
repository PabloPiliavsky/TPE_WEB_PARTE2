<?php
require_once './app/models/jugadores.model.php';
require_once './app/models/paises.model.php';
require_once './app/views/mundial.api.view.php';
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
    
    public function obtenerJugadores(){
        /*--VERIFICA SI ESTÁ SETEADO EL ORDEN, EL FILTRO O NINGUNO--*/
        if (isset($_REQUEST['sort']))
        $jugadores = $this->obtenerJugadoresOrdenados($_REQUEST['sort']); 
        else if (isset($_REQUEST['filtrar']))
            $jugadores = $this->obtenerJugadoresFiltrados($_REQUEST['filtrar']); 
        else 
            $jugadores = $this->model->obtenerJugadores();  
        /*--En cualquiera de los casos muestra la vista adecuada--*/
        if ($jugadores != null)
            return $this->view->response($jugadores, 200);
        else
            return $this->view->response("No se encontraron jugadores", 404); 
    }
    
    public function obtenerJugadoresFiltrados($filtro){ 
        $existeElFiltro = $this->verificarAtributos($filtro);
        if ($existeElFiltro && isset($_REQUEST[$filtro])){
            $sql = $this->obtenerSentenciaFiltro($filtro);
            return $this->model->obtenerJugadoresFiltrados($sql, $_REQUEST[$filtro]);    
        }    
    }        
    
    public function verificarAtributos($filtro){
        $atributos = $this->model->obtenerColumnas();
        if (in_array($filtro, array_column($atributos, 'column_name')))
            return true;
        else
            return false;
    }
    public function obtenerJugadoresOrdenados($sort){
        $existeElAtributo = $this->verificarAtributos($sort);
        if($existeElAtributo){
            echo("leggue aca");
            if(isset($_REQUEST['order']) &&  !empty($_REQUEST['order'])){
                $sql = $this->obtenerSentenciaOrden($sort, $_REQUEST['order']);
                $jugadores = $this->model->obtenerJugadoresOrdenados($sql);
            }else{
                $sql = $this->obtenerSentenciaOrden($sort);
                $jugadores = $this->model->obtenerJugadoresOrdenados($sql);  
            }
            return $jugadores;
        }
    }
    public function obtenerJugador($params){
        $id= $params[':ID'];
        $jugador = $this->model->obtenerJugador($id);
        if($jugador) 
            return $this->view->response($jugador, 200);
        else
            return $this->view->response("El jugador con el id ".$id." no existe", 404);
    }

    public function agregarJugador(){
        $jugador = $this->verificarDatosJugador();
        $id = $this->model->agregarJugador($jugador); 
        $jugador = $this->model->obtenerJugador($id);
        if($jugador)
            return $this->view->response($jugador, 201);
        else
            return $this->view->response("El jugador no se pudo agrear con éxito", 400); 
    }

    public function eliminarJugador($params){
        if(isset($params[':ID']))
            $eliminado = $this->model->eliminarJugador($params[':ID']); 
            if($eliminado != 0)
                return $this->view->response("El jugador se eliminó con éxito", 200);
            else
                return $this->view->response("El jugador no se pudo eliminar", 400);
    }

    public function actualizarjugador($params){
        if(isset($params[':ID'])){
            $id = $params[':ID'];
            $jugador_db = $this->model->obtenerJugador($id);
            if($jugador_db)
                $jugador = $this->verificarDatosJugador();
            $this->model->actualizarJugador($jugador, $id);  
            return $this->view->response("El jugador con el id ".$id." se actualizó con éxito", 200);       
        }        
        else    
            return $this->view->response("Por favor ingrese el id del jugador", 404);
    }
    
    private function verificarDatosJugador(){
        $jugador = $this->obtenerDatos();
        if (empty($jugador->nombre) || empty($jugador->apellido) || empty($jugador->descripcion) || empty($jugador->posicion)|| empty($jugador->foto) || empty($jugador->id_pais))
            return $this->view->response("Por favor complete todos los datos", 400);
        else
            return $jugador;    
    }

    public function obtenerSentenciaOrden($criterio, $orden = null){
        $query = "SELECT * FROM jugadores ORDER BY $criterio $orden";  
        return $query;
    }

    public function obtenerSentenciaFiltro($filtro){
        $query = "SELECT * FROM jugadores WHERE $filtro LIKE :valor";
        return $query;
    }

    function paginar(){
        if(isset($_GET['pagina']) && isset($_GET['filas'])){//corroborar que no sea ni negativo ni un caracter no numerico y que la cantidad de filas sea mayor que 0
            $pagina=0;
            $pagina = $pagina + $_GET['filas']*($_GET['pagina']-1);//el 10 es porque todavia no use un param para poner la cantidad de filas
            $jugadoresPaginado = $this -> model -> paginar($pagina,$_GET['filas']);
            $this -> view -> response($jugadoresPaginado,200);  
        }

    }
}