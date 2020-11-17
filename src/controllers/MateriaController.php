<?php
namespace App\Controllers;
use Clases\Usuario;

use Clases\Servicio;

use App\Models\Vehiculo;
use App\Models\Service;
use App\Models\Turno;
use App\Models\Materia;
use App\Models\Inscripcion;

use App\Models\User;
use App\Models\Nota;
use Clases\Token;

class MateriaController {
    public $datos = array ('datos' => '.');

    public function tipoMascota($request, $response, $args){
        
        /**
         * (GET) turno: Se recibe patente y fecha (día) y se debe guardar en el archivo turnos.xxx, fecha,
            *  patente, marca, modelo, precio y tipo de servicio. Si no hay cupo o la patente no existe informar
            * cada caso particular.
         */
        $parsedBody = $request->getParsedBody();
        
        $tipo = $parsedBody['tipo'];
        
        $mascotaBase = Tipo_Mascota::where('tipo', $tipo)->first();
        // $turnoBase = Turno::where('fecha', $fecha)->first();
        // $tipoServicioBase = Service::where('tipo', $tipo)->first();
        
        if($mascotaBase == null)
        {
            // if($turnoBase == null){ //si el turno no existe lo creo
            $mascota = new Tipo_Mascota;
            // $turno->fecha = $fecha;
            // $turno->patente = $vehiculo->patente;
            // $turno->modelo = $vehiculo->modelo;
            // $turno->marca = $vehiculo->marca;
            // $turno->precio = $vehiculo->precio;
            $mascota->tipo = $tipo;
            $rta = $mascota->save();
            $datos['datos'] = 'Se realizo el alta del tipo correctamente!';
            // }
            // else{ //como existe el turno no lo creo
            //     $datos['datos'] = 'No hay disponibilidad de turno para la fecha ' . $fecha;
            // }
        }
        else{
            $datos['datos'] = 'Ya existe el tipo de mascota cargado';
        }
        // else
        // {
        //     $rta = false;
        //     $datos['datos'] = "No se encuentra la mascota";
            
        // }
        $payload = json_encode($datos);
        $response->getBody()->write($payload);
        
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function altaMateria($request, $response, $args){
        $parsedBody = $request->getParsedBody();
        
        $nombre = $parsedBody['materia'];
        $cupos = $parsedBody['cupos'];
        $cuatrimestre = $parsedBody['cuatrimestre'];
        
        //si existe el tipo cargarlo a la tabla mascota
        //asociar tipo de mascota id
        // $materia = Materia::where('nombre', $nombre)->first();
        // var_dump( $materia);
        // $mascota = Mascota::where('cliente_id', $tipo)->first();
        // $mascotaPrueba = Mascota::with('cliente_id')->with('email')->get();
        // echo $mascota->cliente;
        
        
        
        $materiaNueva = new Materia;
        $materiaNueva->nombre = $nombre;
        $materiaNueva->cupos = $cupos;
        $materiaNueva->cuatrimestre = $cuatrimestre;
        $materiaNueva->save();
        
        $datos['datos'] = 'Se cargo la materia correctamente';
        

        // var_dump($tipoMascota);
        $payload = json_encode($datos);
        $response->getBody()->write($payload);
        
        
        return $response
          ->withHeader('Content-Type', 'application/json');

    }
    public function cargarInscripcion($request, $response, $args){
        
        $idMateria = $args['idMateria'];
        $parsedBody = $request->getParsedBody();
        $token = $request->getHeader('token');
        
        

        $user = Token::VerificarToken($token[0]);
        

        $materia = Materia::where('id', $idMateria)->first();
        $usuario = User::where('email',$user->email)->first();
        //obtengo materia y si cupo menor a 30
        // echo $usuario->id;
        
        if($materia != null && $user != null){
            if($materia->cupos <= 30){
                $inscripcion = new Inscripcion;
                $inscripcion->idAlumno = $usuario->id;
                $inscripcion->idMateria = $materia->id;
                $inscripcion->nombreAlumno = $usuario->name;
                $inscripcion->nombreMateria = $materia->nombre;
                $materia->cupos += 1;
                $materia->save();
                
                $inscripcion->save();
                $datos['datos'] = 'Se dio de alta la inscripcion correctamente';
            }
        }
        else{
            $datos['datos'] = 'No se encuentra el id de la materia';
        }

        
        $payload = json_encode($datos);
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function cargarNotas($request, $response, $args){
        $idMateria = $args['idMateria'];

        $parsedBody = $request->getParsedBody();
        $token = $request->getHeader('token');
        $user = Token::VerificarToken($token[0]);
        $notaIngresada = $parsedBody['nota'];
    
        $idAlumno = $parsedBody['idAlumno'];

        $materia = Materia::where('id', $idMateria)->first();
        // echo $materia;

        if($user!=null){
            $profesor = User::where('email',$user->email)->first();
        }
        else{
            $datos['datos'] = 'No se encontro el profesor';
        }
        $alumno = User::where('id',$idAlumno)->first();
        if($materia!=null){
            
            if($alumno != null){

                $nota = new Nota;
                $nota->idAlumno = $alumno->id;
                $nota->idProfesor = $profesor->id;
                $nota->nota = $notaIngresada;
                $nota->save();
                $datos['datos'] = 'Nota guardada correctamente';
            }
            else{
                $datos['datos'] = 'No se encontro el alumno';
            }
        }
        else{
            $datos['datos'] = 'No se encontro la materia';
        }


        $payload = json_encode($datos);
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function MostrarInscriptos($request, $response, $args){
        

        $parsedBody = $request->getParsedBody();
        $token = $request->getHeader('token');
        $user = Token::VerificarToken($token[0]);
        
        $idMateria = $args['idMateria'];

        $materia = Materia::where('id', $idMateria)->first();

        
        if($user!=null){
            $usuario = User::where('email',$user->email)->first();
        }
        else{
            $datos['datos'] = 'No se encontro el profesor';
        }
        
        if($usuario!=null && $materia != null){
            $datos['nombre'] = $materia->nombre;
            $datos['cuatrimestre'] = $materia->cuatrimestre;
            $datos['cupos'] = $materia->cupos;
            $datos['id'] = $materia->id;
        }
        else{
            $datos['datos'] = 'No se encontro la materia';
        }


        $payload = json_encode($datos);
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function MostrarMaterias($request, $response, $args) {
        $rta = Materia::get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    public function mostrarTodasLasNotas($request, $response, $args) {
        $rta = Nota::get();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    
    
    
}