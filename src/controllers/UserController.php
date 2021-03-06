<?php
namespace App\Controllers;
use Clases\Usuario;
use Clases\Auto;
use Clases\Servicio;
use App\Models\User;
use App\Models\Vehiculo;
use App\Models\Service;
use App\Models\Turno;
use Clases\Token;
use Clases\Archivos;

class UserController {
    public $datos = array ('datos' => '.');
    public function getAllUsers ($request, $response, $args) {
        $rta = User::get(); //select * from users
        // $rta = User::find(1);
        // $rta = User::where('id', '>',  0)
        // ->where('campo', 'operador', 'valor')        
        // ->get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    public function getAllVehiculos ($request, $response, $args) {
        $rta = Vehiculo::get();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getUserEmail($request, $response, $args, $email){
        // $rta = User::get(); //select * from users
        $rta = User::where('email', $email)
        ->first();
        return $rta;
    }
    public function getServicios($request, $response, $args){
        // var_dump($args['id']);
        // $rta = User::get(); //select * from users
        $rta = Service::where('id', $args['id'])
        ->first();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    
    public function registro($request, $response, $args)
    {

        $parsedBody = $request->getParsedBody();
        $email = $parsedBody['email'];
        $nombre = $parsedBody['nombre'];
        $clave = $parsedBody['clave'];
        $tipo = $parsedBody['tipo'];
        
        
        if($tipo != null && $email != null && $clave != null){
            
            
            $rta = User::where('email', $email)->first();
            
            $claveEncriptada = Usuario::encriptarContraseña($clave);
            // var_dump( $datos['datos']);
            
            
            // var_dump($usuarioCreado);
            if($rta == null)
            {
                $usuarioCreado = Usuario::CrearUsuario($email,$claveEncriptada,$tipo);
                $user = new User;
                $user->name = $nombre;
                $user->email = $usuarioCreado->email;
                $user->password = $usuarioCreado->clave;
                
                $user->tipo = $usuarioCreado->tipo;
                $user->rutaImagen = $usuarioCreado->imagenNombre;
                $rta = $user->save();
                
                $datos['datos'] = 'Se creo el usuario correctamente!';
                // var_dump($user);
            }
            else
            {
                $rta = false;
                $datos['datos'] = "Error al crear usuario, ya existe ese email o nombre.";
                
            }
        }
        else{
            $rta = false;
            $datos['datos'] = "Complete los campos correctamente.";
        }
        $payload = json_encode($datos);
        $response->getBody()->write($payload);
        
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function login($request, $response, $args)
    {
        /**
        *   2. (POST) login: Los usuarios deberán loguearse y se les devolverá un token con email y tipo en
        *   caso de estar registrados, caso contrario se informará el error.
        */

        
        $parsedBody = $request->getParsedBody();
        
        $email = $parsedBody['email'];
        $clave = $parsedBody['clave'];
        // $nombre = $parsedBody['nombre'];
        
        $usuario = User::where('email', $email)
        ->first();
        

        
        // $usuario = $this->getUserEmail($request,$response,$args,$email);
        
        // $usuarioPrueba = json_decode($usuario);
        // var_dump($usuario);
        // echo $usuario;
        
        if($usuario != null){
            
            $token = Usuario::Login($email,$clave,$usuario->password,$usuario->imagenNombre,$usuario->tipo);
            
        }
        else {
            $token = false;
        }
        

        if($token != false)
        {
            $datos['datos'] = 'Login Exitoso.';
            $datos['token'] = $token;
            
        }
        else
        {
            $datos['datos'] = 'Nombre o Clave Incorrecto';                       
        }
        $payload = json_encode($datos);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    // public function Materia($request, $response, $args){
    //     $parsedBody = $request->getParsedBody();
    //     $token = $request->getHeader('token');
    //     // $token = $header['token'];
    //     $nombreMateria = $parsedBody['nombre'];
    //     $cuatrimestre = $parsedBody['cuatrimestre'];
    //     // $cuatrimestre = $_POST['cuatrimestre'] ?? "";
        
    //     $usuarioLogueado = Token::VerificarToken($token);
        
    //     if (!$usuarioLogueado) {
    //         $datos = "Usuario no logueado, token incorrecto!";
    //     }
    //     else{
    //         $materia = new Materias($nombreMateria,$cuatrimestre);
            

    //         // if(Archivos::guardarJson($materia,'materias.json')){
    //         //     $datos = "Materia guardada correctamente";
    //         // }
    //         // else{
    //         //     $datos = "Ocurrio un error al guardar la materia";
    //         // }
    //     }
    //     $payload = json_encode($datos);

    //     $response->getBody()->write($payload);
    //     return $response
    //     ->withHeader('Content-Type', 'application/json');
    // }
    
    public function Vehiculo($request, $response, $args){
        // echo "me autentique";
        // var_dump($response);
        $parsedBody = $request->getParsedBody();

        $marca = $parsedBody['marca'];
        $modelo = $parsedBody['modelo'];
        $patente = $parsedBody['patente'];
        $precio = $parsedBody['precio'];
        
        $rta = Vehiculo::where('patente', $patente)->first();
        
        
        if($rta == null){
            $vehiculo = new Vehiculo;
            $vehiculo->marca = $marca;
            $vehiculo->modelo = $modelo;
            $vehiculo->patente = $patente;
            $vehiculo->precio = $precio;
            $rta = $vehiculo->save();
            $datos = array( 'datos' => 'Se guardaron los datos del vehiculo correctamente!');
        }
        else if($rta->patente != $patente){
            $vehiculo = new Vehiculo;
            $vehiculo->marca = $marca;
            $vehiculo->modelo = $modelo;
            $vehiculo->patente = $patente;
            $vehiculo->precio = $precio;
            $rta = $vehiculo->save();
            $datos = array( 'datos' => 'Se guardaron los datos del vehiculo correctamente!');
        }
        else{
            $datos = array( 'datos' => 'Error al guardar el vehiculo, patente existente.');

        }

        
        
        $payload = json_encode($datos);

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function getVehiculo($request, $response, $args){
        // $rta = User::get(); //select * from users
        
        $patente = $args['patente'];
        $vehiculo = Vehiculo::where('patente', $patente)
        ->first();
        if($vehiculo != null){
            $rta = $vehiculo;
        }
        else{
            $rta = array( 'datos' => 'No se encontro el vehiculo con la patente ' . $patente);
        }
        $payload = json_encode($rta);
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function getStats($request, $response, $args){
        // $rta = User::get(); //select * from users
        // $token = $request->getHeader('token');
        
        // $user = Token::VerificarToken($token[0]);
        // var_dump($user);
        // $tipo = $args['tipo'];
        // var_dump($tipo);
        if($args != null){
            $tipo = $args['tipo'];
            $rta = Service::where('tipo', $tipo)
            ->get();
        }
        else{
            $rta = Service::get();
        }
        // if($vehiculo != null){
        //     $rta = $vehiculo;
        // }
        // else{
            // }
        // $rta = array( 'datos' => 'No se encontro el vehiculo con la patente ' . $patente);
        $payload = json_encode($rta);
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function altaServicio($request, $response, $args){
        
        $parsedBody = $request->getParsedBody();
        $id = $parsedBody['id']; //int
        $tipo = $parsedBody['tipo']; //int -> 10000, 20000, 50000
        $precio = $parsedBody['precio']; //double
        $demora = $parsedBody['demora']; //date
        
        $servicioBase = Service::where('id', $id)->first();
        
        // var_dump( $datos['datos']);
        $servicioCreado = new Servicio($id,$tipo,$precio,$demora);
        // var_dump($usuarioCreado);
        if($servicioBase == null)
        {
            $servicio = new Service;
            $servicio->id = $servicioCreado->id;
            $servicio->tipo = $servicioCreado->tipo;
            $servicio->precio = $servicioCreado->precio;
            $servicio->demora = $servicioCreado->demora;
            $rta = $servicio->save();
            $datos['datos'] = 'Se creo el servicio correctamente!';
            // var_dump($user);
        }
        else
        {
            $rta = false;
            $datos['datos'] = "Error al crear servicio, id existente";
            
        }
        $payload = json_encode($datos);
        $response->getBody()->write($payload);
        
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    // public function altaTurno($request, $response, $args){
        
    //     /**
    //      * (GET) turno: Se recibe patente y fecha (día) y se debe guardar en el archivo turnos.xxx, fecha,
    //         *  patente, marca, modelo, precio y tipo de servicio. Si no hay cupo o la patente no existe informar
    //         * cada caso particular.
    //      */
    //     $parsedBody = $request->getParsedBody();
    //     $patente = $parsedBody['patente']; //int
    //     $fecha = $parsedBody['fecha'];
    //     $tipo = $parsedBody['tipo']; //int -> 10000, 20000, 50000
        
    //     $vehiculo = Vehiculo::where('patente', $patente)->first();
    //     $turnoBase = Turno::where('fecha', $fecha)->first();
    //     $tipoServicioBase = Service::where('tipo', $tipo)->first();
        
    //     if($vehiculo != null) //si encontro vehiculo
    //     {
    //         if($turnoBase == null){ //si el turno no existe lo creo
    //             $turno = new Turno;
    //             $turno->fecha = $fecha;
    //             $turno->patente = $vehiculo->patente;
    //             $turno->modelo = $vehiculo->modelo;
    //             $turno->marca = $vehiculo->marca;
    //             $turno->precio = $vehiculo->precio;
    //             $turno->tipo = $tipoServicioBase->tipo;
    //             $rta = $turno->save();
    //             $datos['datos'] = 'Se creo el turno correctamente!';
    //         }
    //         else{ //como existe el turno no lo creo
    //             $datos['datos'] = 'No hay disponibilidad de turno para la fecha ' . $fecha;
    //         }
    //     }
    //     else
    //     {
    //         $rta = false;
    //         $datos['datos'] = "No se encuentra el vehiculo";
            
    //     }
    //     $payload = json_encode($datos);
    //     $response->getBody()->write($payload);
        
    //     return $response
    //       ->withHeader('Content-Type', 'application/json');
    // }


    // public function add($request, $response, $args)
    // {
    //     $user = new User;
    //     $user->name = "Juan";
    //     $user->email = "Juan@mail.com";
    //     $user->password = "sdxdsdsds";

    //     $rta = $user->save();

    //     $response->getBody()->write(json_encode($rta));
    //     return $response;
    // }

    public function update($request, $response, $args)
    {
        $params = (array)$request->getQueryParams();

        $id = $args['id'];
        $email = $params['email'];
        $name = $params['name'];
        // var_dump($request);
        // $parsedBody = $request->getParsedBody();
        // var_dump($params);
        // $email = $parsedBody['email'];
        // $name = $parsedBody['name'];

        $user = User::find($id);
        if($user){
            $user->email = $email;
            $user->name = $name;
            
            if($user->save()){

                $datos['datos'] = "Se modifico el usuario correctamente.";
                
            }
            else{
                $datos['datos'] = "Error al modificar datos";
            }
            
        }
        else{
            $datos['datos'] = "Error. ID no encontrado";
        }

        // $rta = $user->save();

        $response->getBody()->write(json_encode($datos));
        return $response;
    }

    public function delete($request, $response, $args)
    {
        $id = $args['id'];
        $parsedBody = $request->getParsedBody();
        
        // $rta = User::where('email', $email)->first();

        $user = User::find($id);
        if($user){
            if($user->delete()){
                $datos['datos'] = "Se elimino el usuario correctamente.";
            }
        }
        else{
            $datos['datos'] = "Error. ID no encontrado";
        }
        

        $response->getBody()->write(json_encode($datos));
        return $response;
        // getAll();
    }
    public function isEmail($email){
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }
        else{
            return $email;
        }
    }

    // $respuesta = new stdClass;
    // $respuesta->success = true;

    // $respuesta->data = $datos; 

    
}