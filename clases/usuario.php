<?php
namespace Clases;
use Clases\Token;
use Clases\Archivos;



class Usuario{
    public $email;
    public $clave;
    public $imagenNombre;
    public $tipo;

    public function __construct($email, $clave, $imagenNombre,$tipo)
    {
        $this->setEmail($email);
        $this->clave = $clave;
        $this->imagenNombre = $imagenNombre;
        $this->tipo = $this->setUser($tipo);
    }
    
    public function setEmail($email){
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->email = "emailNoValido";
        }
        else{
            $this->email = $email;
            
        }
    }
    //TODO chequear esta validacion depende que tipo de usuario pida
    public function setUser($user){
        if($user == 'admin' || $user == 'profesor' || $user == 'alumno'){
            return $user;
        }
        else{
            $user = 'cliente';
            return $user;
        }
    }

    public static function Login($email,$clave,$claveBase,$imagenNombre,$tipo){
        $retorno = false;
        // $lista = Archivos::leerJson('users.json',$listaUsuarios);

        
        // var_dump($lista);
        
        // if(isset($lista)){
        //     foreach ($lista as $usuario) {
                //$usuario['clave'] es la clave encriptada del json que levanto
                // $usuario['email'] == $email &&
                
                if ( Usuario::verificarContraseña($clave,$claveBase)) {
                    $usuario = array('email' => $email, 'clave' => $claveBase,'tipo'=>$tipo);
                    //Usuario::verificarContraseña($clave,$usuario['clave']);

                    $token = Token::crearToken($usuario);
                    
                    return $token;
                // break;
                }
                else{
                    return false;
                }
        //     }
        // }
    }

    public static function encriptarContraseña($clave){
        return password_hash($clave, PASSWORD_DEFAULT);
    }

    public static function verificarContraseña($clave,$hash){
        // echo $clave;
        // echo $hash;
        return password_verify($clave, $hash);
    }

    

    public static function CrearUsuario($email,$claveEncriptada,$tipo){
        $retorno = false;
        
        // if(!Usuario::buscarUsuario($email)){
            // echo $email;
            // $imagenNombre = Archivos::guardarImagen($_FILES,3670016,'./imagenes/',true);
            $imagenNombre = "";
            $usuario = new Usuario($email,$claveEncriptada,'./imagenes/'.$imagenNombre,$tipo);
            if ($usuario->email != "emailNoValido") {
                // if(Archivos::guardarJson($usuario,'users.json')){
                    $retorno = $usuario;
                // }
                if (isset($listaUsuarios)) {
                    
                    array_push($listaUsuarios);
                }
                else{
                    
                    $listaUsuarios = $usuario;
                }
            }
            else{
                $retorno = false;
            }

            
        // }
        // else
        // {
        //     $retorno = false;
        // }
        return $retorno;
    }


    public static function buscarUsuario($email)
    {   
        $retorno = false;
        Archivos::leerJson('./users.json',$listaUsuarios);
        //var_dump($listaUsuarios);

        foreach ($listaUsuarios as $usuario) {
            if ($usuario['email'] === $email) {
                $retorno = true;
            }
            else{
                $retorno = false;
            }
        }
        return $retorno;
        // if(isset( $listaUsuarios)){
        //     if(Archivos::leerTxt('usuario.txt', $listaUsuarios))
        //     {
        //         foreach ($listaUsuarios as $auxUr)
        //         {
        //             if($email == $auxUr['email'])
        //             {
        //                 $retorno = true;
        //                 break;
        //             }
        //         }
        //     }
        // return $retorno;
        // }
        
    }
    public static function asignarFotoNueva($email,$foto){
        Archivos::leerJson('./users.json',$listaUsuarios);
        //var_dump($listaUsuarios);
        $nombreFoto = $_FILES["foto"]["name"];
        for ($i=0; $i < count($listaUsuarios); $i++) { 
            if ($listaUsuarios[$i]['email'] === $email) {
                //var_dump($nombreFoto);
                //$pathMover = "./imagenes/" . $listaUsuarios[$i]['imagenNombre'];

                Archivos::moverImagen("./imagenes/imagen" . $listaUsuarios[$i]['imagenNombre'] , "./backup/".$listaUsuarios[$i]['imagenNombre']);
                $usuarioAux = new Usuario($email,$listaUsuarios[$i]['clave'],$nombreFoto);
                
                Archivos::modificarJson("./users.json",$i,"imagenNombre",$nombreFoto);
                $retorno = true;
                return $retorno;
            }
            else{
                $retorno = false;
            }
        }
        // foreach ($listaUsuarios as $usuario) {
            
        // }

        
    }



}


?>