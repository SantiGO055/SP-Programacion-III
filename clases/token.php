<?php
namespace Clases;
// require __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;
/**clase creada para manejar el token de  libreria firebase*/
class Token{
    
    //private static $aud = null;

    public static function crearToken($dato){
        $retorno = false;
        $key = "segundoParcial";

        $payload = array(
            "email" => $dato['email'],
            "clave" => $dato['clave'],
            "tipo" => $dato['tipo']

        );
        
        //este metodo devuelve el token
        //entonces en un login el encode lo hago una sola vez
        $retorno = JWT::encode($payload, $key); //encode sirve para codificar un objeto, le paso el array con los datos y la key
        return $retorno;
        
        
    }
    public static function VerificarToken($token){
        $key = "segundoParcial";
        $retorno = false;
        try {
            $retorno = JWT::decode($token, $key, array('HS256'));
        }
        catch (Exception $e) {
            echo "error";
            $retorno = false;
        }
        return $retorno;
    }
}








?>