<?php

Class jugadoresModel{
    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost:4306;dbname=db_mundial;charset=utf8', 'root', '');
    }
    public function obtenerJugadores(){
        $sentencia = $this->db->prepare ('SELECT jugadores.*, paises.nombre as pais FROM jugadores 
                                      JOIN paises ON jugadores.id_pais = paises.id');
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_OBJ); 
    }
    
    public function obtenerJugador($id){
        $sentencia = $this->db->prepare('SELECT jugadores.*, paises.nombre as pais FROM jugadores JOIN paises 
                                      ON jugadores.id_pais = paises.id WHERE jugadores.id = :id');
        $sentencia->execute([':id'=>$id]);
        return $sentencia->fetch(PDO::FETCH_OBJ);
    }
    public function obtenerJugadoresFiltrados($sql, $valor){
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute([':valor' => $valor]);
        return $sentencia->fetchAll(PDO::FETCH_OBJ); 
    }

    public function obtenerJugadoresOrdenados($sql){
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_OBJ); 
    }
    function paginar($sql){
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }

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

    public function eliminarJugador($id){
        $sentencia =  $this ->db ->prepare('DELETE FROM jugadores WHERE (id) = :id');
        $sentencia->execute([':id' => $id]);
    }
    
    public function obtenerColumnas(){
        $sentencia = $this->db->prepare('SELECT column_name FROM information_schema.columns WHERE table_name = :table_name');
        $sentencia->execute([':table_name' => 'jugadores']);
        return $sentencia->fetchAll();
    }

    public function obtenerTotalDeRegistros(){
        $sentencia = $this->db->prepare ("SELECT count(*) FROM jugadores");
        $sentencia->execute();
        return $sentencia->fetchColumn(); 
    }
}