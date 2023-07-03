<?php

require_once './app/models/usuario.model.php';

Class usuariosHelper {

    private $secretKey = 'messias'; // se usa para desencriptar en el comprobar
    private $usuario;
    private $model;

    public function __construct(){
        $this -> model = new usuariosModel();
        $this -> usuario = null;
    }

    public function validarPermisos(){
        $header = apache_request_headers();
        if (!isset($header['Authorization']))
            return null;
        $authorization = $header['Authorization'];
        $params = explode(' ', $authorization);
        $token = $params[1];
        $usuario = $this->comprobarToken($token);
        if ($usuario) {
            //Buscamos el usuario en la DB y lo guardamos en la clase
            $this-> usuario = $this->model->obtenerUsuario($usuario);
            return true;
        } else
            return null;
    }

    function obtenerToken($usuario){
        $tokenData =[
            'sub' => $usuario->id, // Identificador del usuario
            'iat' => time(), // Fecha de emisión del token
            'exp' => time() + 3600, // Fecha de vencimiento del token (1 hora)
            'data' => $usuario->usuario // Datos adicionales del usuario
        ];
        // Genera el token JWT, que es el tipo de firma del token
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);//valores constantes en un json para el header
        $header = base64_encode($header);

        $payload = json_encode($tokenData);
        $payload = base64_encode($payload);

        $signature = hash_hmac('sha256', "$header.$payload", $this->secretKey, true);//hash entre header, payload y el secretkey, todo encripado
        $signature = base64_encode($signature);

        $token = "$header.$payload.$signature";
        return ['token' => $token];
    }

    private function comprobarToken($token){
        // Divide el token en sus componentes: encabezado, payload y firma
        [$header, $payload, $signature] = explode('.', $token);

        // Decodifica el payload para recibir los datos ingresados al crear el token
        $payloadData = json_decode(base64_decode($payload), true);

        // Verifica la firma del token
        $hash = hash_hmac('sha256', "$header.$payload", $this->secretKey, true);//'sha256' es una constante generada por el JWT
        $signatureData = base64_decode($signature);
        $isSignatureValid = hash_equals($hash, $signatureData);//compara el hash nuevo con el anterior

        if ($isSignatureValid){
            // Verifica la fecha de vencimiento
            $currentTimestamp = time();
            $expirationTimestamp = $payloadData['exp'];
            if ($currentTimestamp <= $expirationTimestamp) {
                // El token no ha expirado
                return $payloadData['sub']; // identificador del usuario del payload
            }else 
                return null;
        }else 
            return null;
    }
}