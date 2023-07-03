<?php

Class paisesModel{
    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost;dbname=db_mundial;charset=utf8', 'root', '');
    }
    public function obtenerIdPaises(){  
        $query = $this->db->prepare("SELECT id FROM paises");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC); 
    }

    /*--Obtiene todos los paises filtrados por criterio/valor--*/
    public function obtenerPaisesFiltrados($sql, $valor){ 
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute([':valor' => $valor]);
        return $sentencia->fetchAll(PDO::FETCH_OBJ); 
    }
    /*--Obtiene todos los paises ordenados por criterio (Listado de categorias)--*/
    public function obtenerPaisesOrdenados($sql){  
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_OBJ); 
    }

    /*--Obtiene todos los paises (Listado de categorias)--*/
    function obtenerPaises(){
        $sentencia = $this->db->prepare("SELECT * FROM paises");
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }

    /*--Obtiene el pais segun id--*/
    function obtenerPais($id){  
        $sentencia = $this -> db -> prepare("SELECT * FROM paises WHERE (id) = :id");
        $sentencia -> execute([":id"=>$id]);
        return $sentencia -> fetch(PDO::FETCH_OBJ);
    }

    /*--Borra el pais segun id --*/
    function  eliminarPais($id){  
        $sentencia = $this -> db ->prepare("DELETE FROM paises WHERE (id)=:id");
        $sentencia -> execute([":id"=>$id]);
        return $sentencia->rowCount();
    }

    /*--Agrega un nuevo pais y retorna el último id ingresado--*/
    function agregarPais($pais){
        $sentencia = $this -> db ->prepare("INSERT INTO paises 
                                                   (nombre, continente, clasificacion, bandera) 
                                            VALUES (:nombre, :continente, :clasificacion, :bandera)");
        $sentencia->execute([":nombre"=>$pais->nombre,
                             ":continente"=>$pais->continente,
                             ":clasificacion"=>$pais->clasificacion,
                             ":bandera"=>$pais->bandera]);
        return $this -> db ->lastInsertId();
    }

    /*--Edita el pais según el id--*/
    function ActualizarPais($pais, $id){
        $sentencia = $this -> db ->prepare("UPDATE paises 
                                            SET nombre = :nombre,
                                                continente = :continente,
                                                clasificacion = :clasificacion,
                                                bandera = :bandera
                                            WHERE id = :id");
        $sentencia -> execute([":nombre" => $pais->nombre, 
                               ":continente"=> $pais->continente, 
                               ":clasificacion"=>$pais->clasificacion,
                               ":bandera"=>$pais->bandera,
                               ":id" => $id]
                            );
    }

    /*--Verifica si existe algún pais con el mismo nombre o clasificación en la BBDD--*/
    function verificarPaisExistente($clasificacion = null, $nombre = null){
        $sentencia = $this -> db -> prepare("SELECT * FROM paises WHERE (clasificacion) = :clasificacion OR (nombre) = :nombre");
        $sentencia -> execute([":clasificacion" => $clasificacion,
                                ":nombre" => $nombre]);
        return  $sentencia->fetch(PDO::FETCH_OBJ);
    }

    /*--Retorna los atributos/columnas de la tabla paises--*/
    public function obtenerColumnas(){
        $sentencia = $this->db->prepare('SELECT column_name FROM information_schema.columns WHERE table_name = :table_name');
        $sentencia->execute([':table_name' => 'paises']);
        return $sentencia->fetchAll();
    }
}
