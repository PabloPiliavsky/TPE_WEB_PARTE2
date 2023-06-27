<?php
require_once './app/models/jugadores.model.php';
//require_once './app/models/paises.model.php';
require_once './app/views/mundial.api.view.php';
Class jugadoresApiController{
    private $model;
    private $view;
    //private $modelPaises;
    private $data;

    public function __construct(){
        $this->model = new jugadoresModel(); 
        //$this->modelPaises = new paisesModel();
        $this->view = new mundialApiView(); 
        $this->data = file_get_contents("php://input"); // lee el body del request
    }

    private function obtenerDatos() {
        return json_decode($this->data);
    }
    
    /*--VERIFICA SI ESTÁ SETEADO EL ORDEN, EL FILTRO, LA PAGINA O NINGUNO--*/
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
        else
            return $this->view->response("No se encontraron jugadores", 404); 
    }
    
    public function obtenerJugadoresFiltrados($filtro){ 
        $existeElFiltro = $this->verificarAtributos($filtro);//boolean
        if ($existeElFiltro && isset($_REQUEST['valor'])){
            $sql = $this->obtenerSentenciaFiltro($filtro);
            return $this->model->obtenerJugadoresFiltrados($sql, $_REQUEST['valor']);    
        }    
    }        
    
    /*--Verifica que los campos ingresados para filtrar u ordenar coincidan con los de la bbdd--*/
    public function verificarAtributos($filtro){
        $atributos = $this->model->obtenerColumnas();
        return (in_array($filtro, array_column($atributos, 'column_name')));
    }

    /*--Verifica que los atributos sean correctos, obtiene la sentencia y la ejecuta en el modelo--*/
    public function obtenerJugadoresOrdenados($criterio){
        if($this->verificarAtributos($criterio)){
            if(isset($_REQUEST['orden']) &&  !empty($_REQUEST['orden']))
                $sql = $this->obtenerSentenciaOrden($criterio, $_REQUEST['orden']);
            else
                $sql = $this->obtenerSentenciaOrden($criterio); //lo llamaria ascendentemente por defecto   
            return $this->model->obtenerJugadoresOrdenados($sql);;
        }
    }

    //verificar si esta correctamente seteado el :ID, o sea, sin negativos ni caracteres no numericos
    public function obtenerJugador($params){
        $id = $params[':ID'];
        if(is_numeric($id) && $id > 0){
            $jugador = $this->model->obtenerJugador($id);
            if($jugador) 
                return $this->view->response($jugador, 200);
            else
                return $this->view->response("El jugador con el id ".$id." no existe", 404);
        }else
            return $this->view->response("Por favor verifique los datos ingresados", 404);
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
        if(isset($params[':ID'])){//verificar que pasa si esta seteado y vacio
            $eliminado = $this->model->eliminarJugador($params[':ID']); 
            if($eliminado != 0)
                return $this->view->response("El jugador se eliminó con éxito", 200);
            else
                return $this->view->response("El jugador no se pudo eliminar", 400);
        }
    }

    public function actualizarjugador($params){
        if(isset($params[':ID'])){
            $id = $params[':ID'];
            $jugador_db = $this->model->obtenerJugador($id);
            if($jugador_db){
                $jugador = $this->verificarDatosJugador();
                $this->model->actualizarJugador($jugador, $id);  
                return $this->view->response("El jugador con el id ".$id." se actualizó con éxito", 200);       
            }        
        }else    
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
        $sentencia = "SELECT * FROM jugadores ORDER BY $criterio $orden";  
        return $sentencia;
    }

    public function obtenerSentenciaFiltro($filtro){
        $sentencia = "SELECT * FROM jugadores WHERE $filtro LIKE :valor";
        return $sentencia;
    }

    public function obtenerSentenciaPaginada($inicio,$filas){
        $sentencia = "SELECT * FROM `jugadores` ORDER BY id LIMIT $inicio, $filas";
        return $sentencia;
    }

    public function paginar($pagina,$filas){//comprobar que el numero de paginas sea igual o menor a la cantidad total de jugadores dividido la cantidad de filas
        if(!empty($pagina) && !empty($filas)){//corroborar que no sea ni negativo, ni menor a 0 ni un caracter no numerico y que la cantidad de filas sea mayor que 0
            $inicio=$filas*($pagina-1);//el 10 es porque todavia no use un param para poner la cantidad de filas
            $sql = $this->obtenerSentenciaPaginada($inicio,$filas);//lo llamaria ascendentemente por defecto
            $jugadoresPaginado = $this -> model -> paginar($sql); 
            return $jugadoresPaginado;  
        }
    }
}