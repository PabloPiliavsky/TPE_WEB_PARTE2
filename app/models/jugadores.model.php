<?php

Class jugadoresModel{
    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost;dbname=db_mundial;charset=utf8', 'root', '');
    }
    /*--Obtiene todos los jugadores--*/
    public function obtenerJugadores(){
        $sentencia = $this->db->prepare('SELECT jugadores.*, paises.nombre as pais FROM jugadores 
                                      JOIN paises ON jugadores.id_pais = paises.id');
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }

    /*--Obtiene todos los jugadores de un país determinado--*/
    public function obtenerJugadoresPorPais($id_pais){
        $sentencia = $this->db->prepare('SELECT * FROM jugadores WHERE (id_pais) = :id_pais');
        $sentencia->execute([':id_pais' => $id_pais]);
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }
    
    /*--Obtiene todos los datos de un jugador por id--*/
    public function obtenerJugador($id){
        $sentencia = $this->db->prepare('SELECT jugadores.*, paises.nombre as pais FROM jugadores JOIN paises 
                                      ON jugadores.id_pais = paises.id WHERE jugadores.id = :id');
        $sentencia->execute([':id'=>$id]);
        return $sentencia->fetch(PDO::FETCH_OBJ);
    }

    /*--Obtiene todos los jugadores filtrados por criterio/valor--*/
    public function obtenerJugadoresFiltrados($sql, $valor){
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute([':valor' => $valor]);
        return $sentencia->fetchAll(PDO::FETCH_OBJ); 
    }

    /*--Obtiene todos los jugadores ordenados por criterio--*/
    public function obtenerJugadoresOrdenados($sql){
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_OBJ); 
    }

    /*--Obtiene los jugadores paginados--*/
    function paginar($sql){
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }

    /*--Agrega un jugador--*/
    public function agregarJugador($jugador){
        $sentencia = $this->db->prepare('INSERT INTO jugadores (nombre, apellido, descripcion, posicion, foto, id_pais) 
                                     VALUES (:nombre, :apellido, :descripcion, :posicion, :foto, :id_pais)');
        $sentencia->execute([':nombre' => $jugador->nombre, 
                         ':apellido' => $jugador->apellido,
                         ':descripcion' => $jugador->descripcion, 
                         ':posicion' => $jugador->posicion,
                         ':foto' => $jugador->foto,
                         ':id_pais' => $jugador->id_pais]);
        $id = $this->db->lastInsertId();
        return $id;
    }

    /*--Actualiza un jugador por id--*/
    public function actualizarjugador($jugador, $id){
        $sentencia = $this ->db ->prepare ('UPDATE jugadores 
                                            SET nombre = :nombre,
                                                apellido = :apellido,
                                                descripcion = :descripcion,
                                                posicion = :posicion,
                                                foto = :foto,
                                                id_pais = :id_pais
                                            WHERE id = :id');
        $sentencia->execute([':nombre' => $jugador->nombre,
                             ':apellido' => $jugador->apellido,
                             ':descripcion' => $jugador->descripcion,
                             ':posicion' => $jugador->posicion,
                             ':foto' => $jugador->foto,
                             ':id_pais' =>$jugador->id_pais,
                             ':id' => $id]);   
    }

    /*--Elimina un jugador por id--*/
    public function eliminarJugador($id){
        $sentencia =  $this ->db ->prepare('DELETE FROM jugadores WHERE (id) = :id');
        $sentencia->execute([':id' => $id]);
    }
    
    /*--Obtiene los nombres de las columnas de la tabla jugadores--*/
    public function obtenerColumnas(){
        $sentencia = $this->db->prepare('SELECT column_name FROM information_schema.columns WHERE table_name = :table_name');
        $sentencia->execute([':table_name' => 'jugadores']);
        return $sentencia->fetchAll();
    }

    /*--Obtiene el total de registros de la tabla jugadores--*/
    public function obtenerTotalDeRegistros(){
        $sentencia = $this->db->prepare ("SELECT count(*) FROM jugadores");
        $sentencia->execute();
        return $sentencia->fetchColumn(); 
    }
}