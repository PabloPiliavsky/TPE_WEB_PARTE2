<?php

Class jugadoresModel{
    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost;dbname=db_mundial;charset=utf8', 'root', '');
    }
    public function obtenerJugadores(){
        $query = $this->db->prepare ("SELECT jugadores.*, paises.nombre 
                                      as pais 
                                      FROM jugadores JOIN paises 
                                      ON jugadores.id_pais = paises.id");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ); 
    }
    
    public function obtenerJugador($id){
        $query = $this->db->prepare ('SELECT jugadores.*, paises.nombre 
                                      as pais 
                                      FROM jugadores JOIN paises 
                                      ON jugadores.id_pais = paises.id 
                                      WHERE (id) = :id');
        $query->execute([':id'=>$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function agregarJugador($jugador){
        $query = $this->db->prepare('INSERT INTO jugadores 
                                            (nombre, apellido, descripcion, posicion, foto, id_pais) 
                                     VALUES (:nombre, :apellido, :descripcion, :posicion, :foto, :id_pais)');
        $query->execute([':nombre' => $jugador->nombre, 
                         ':apellido' => $jugador->apellido,
                         ':descripcion' => $jugador->descripcion, 
                         ':posicion' => $jugador->posicion,
                         ':foto' => $jugador->foto,
                         ':id_pais' => $jugador->id_pais]);
        $id = $this->db->lastInsertId();
        return $id;
    }

    public function actualizarjugador($jugador, $id){
        $query = $this ->db ->prepare ('UPDATE jugadores 
                                        SET nombre = :nombre,
                                            apellido = :apellido,
                                            descripcion = :descripcion,
                                            posicion = :posicion,
                                            foto = :foto,
                                            id_pais = :id_pais
                                        WHERE id = :id');
        $query->execute([':nombre' => $jugador->nombre,
                         ':apellido' => $jugador->apellido,
                         ':descripcion' => $jugador->descripcion,
                         ':posicion' => $jugador->posicion,
                         ':foto' => $jugador->foto,
                         ':id_pais' =>$jugador->id_pais,
                         ':id' => $id]);   
    }

    public function eliminarJugador($id){
        $query =  $this ->db ->prepare('DELETE FROM jugadores WHERE (id) = :id');
        $query->execute([':id' => $id]);
        return $query->rowCount();
    }
    public function obtenerJugadoresFiltrados($sql, $valor){
        $query = $this->db->prepare($sql);
        $query->execute([':valor' => $valor]);
        return $query->fetchAll(PDO::FETCH_OBJ); 
    }

    public function obtenerJugadoresOrdenados($sql){
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ); 
    }
   
    public function obtenerColumnas(){
        $query = $this->db->prepare('SELECT column_name 
                                     FROM information_schema.columns 
                                     WHERE table_name = :table_name');
        $query->execute([':table_name' => 'jugadores']);
        return $query->fetchAll();
    }

    
}