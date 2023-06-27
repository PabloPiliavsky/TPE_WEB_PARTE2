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
}