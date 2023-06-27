<?php

Class paisesModel{
    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost:4306;dbname=db_mundial;charset=utf8', 'root', '');
    }
    public function obtenerIdPais($nombre){
        $query = $this->db->prepare("SELECT id FROM paises WHERE (nombre) = :nombre");
        $query->execute([':nombre' => $nombre]);
        return $query->fetch(); 
    }
}