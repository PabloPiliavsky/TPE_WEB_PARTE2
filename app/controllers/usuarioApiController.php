<?php
require_once './app/models/usuario.model.php';
require_once './app/views/mundial.api.view.php';
require_once './helper/authHelper.php';

class usuarioApiController{
    private $model;
    private $apiView;
    private $data;

    public function __construct(){
        $this -> data = file_get_contents ("php://input"); // lee el body del request
        $this -> apiView = new mundialApiView();
        $this -> model = new usuariosModel();
    }

    /*--Convierte el body de la request codificado en JSON a una variable de PHP--*/
    private function getData(){
        return json_decode($this->data);
    }

    /*--Verifica que el usuario y contraseña coincidan con el de la BBDD y si es correcto genera el token--*/
    public function login(){
        $datos = $this->getData();
        $usuario = $datos->usuario;
        $password = $datos->password;
        if (empty($usuario) || empty($password))
            $this->apiView->response("Debe indicar el nombre de usuario y/o password", 400);
        else{
            $usuarioModel = $this->model->obtenerUsuario($usuario);
            if($usuarioModel && password_verify($password, $usuarioModel->password)){
                $helper = new usuariosHelper();
                $token = $helper->obtenerToken($usuarioModel);
                $this->apiView->response($token, 200);
            }
            else
                $this->apiView->response("usuario o contraseña incorrecto/s", 400);    
        }    
    }
}
