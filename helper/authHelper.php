<?php
require_once './app/models/usuario.model.php';

Class usuariosHelper {
    private $secretKey = 'messias';
    private $usuario;
    private $model;

    public function __construct(){
        $this -> model = new usuariosModel();
        $this -> usuario = null;
    }

    /*--Valida que el campo Authorization no este vacío (token) y que el token ingresado sea válido--*/
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
    /*-- Genera y retorna un nuevo token--*/
    function obtenerToken($usuario){
        $tokenData =[
            'sub' => $usuario->id, // Identificador del usuario
            'iat' => time(), // Fecha de emisión del token
            'exp' => time() + 3600, // Fecha de vencimiento del token (1 hora)
            'data' => $usuario->usuario // Datos adicionales del usuario
        ];
        // Genera el token JWT
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $header = base64_encode($header);

        $payload = json_encode($tokenData);
        $payload = base64_encode($payload);

        $signature = hash_hmac('sha256', "$header.$payload", $this->secretKey, true);
        $signature = base64_encode($signature);

        $token = "$header.$payload.$signature";
        return ['token' => $token];
    }
    
    /*--Comprueba que el TOKEN ingresado coincida con el propocionado previamente--*/
    private function comprobarToken($token){
        // Divide el token en sus componentes: encabezado, payload y firma
        [$header, $payload, $signature] = explode('.', $token);

        // Decodifica el encabezado y el payload
        $payloadData = json_decode(base64_decode($payload), true);

        // Verifica la firma del token
        $hash = hash_hmac('sha256', "$header.$payload", $this->secretKey, true);
        $signatureData = base64_decode($signature);
        $isSignatureValid = hash_equals($hash, $signatureData);

        if ($isSignatureValid){
            // Verifica la fecha de vencimiento
            $currentTimestamp = time();
            $expirationTimestamp = $payloadData['exp'];
            if ($currentTimestamp <= $expirationTimestamp) // El token no ha expirado 
                return $payloadData['sub']; // identificador del usuario del payload
            else 
                return null;   
        }else 
            return null;
    }
}