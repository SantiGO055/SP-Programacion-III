<?php
namespace App\Controllers;
use Clases\Usuario;

use Clases\Servicio;

use App\Models\Vehiculo;
use App\Models\Service;
use App\Models\Turno;
use App\Models\Mascota;
use App\Models\Tipo_Mascota;
use App\Models\User;
use Clases\Token;

class MascotaController {
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
    public function cargarMascota($request, $response, $args){
        $parsedBody = $request->getParsedBody();
        
        $tipo = $parsedBody['tipo'];
        $idCliente = $parsedBody['idCliente'];
        $nombre = $parsedBody['nombre'];
        $fechaNac = $parsedBody['fecha_nacimiento'];
        //si existe el tipo cargarlo a la tabla mascota
        //asociar tipo de mascota id
        $tipoMascota = Tipo_Mascota::where('tipo', $tipo)->first();
        $mascota = Mascota::where('cliente_id', $tipo)->first();
        // $mascotaPrueba = Mascota::with('cliente_id')->with('email')->get();
        // echo $mascota->cliente;
        $usuario = User::where('id',  $idCliente)->first();
        
        if($tipoMascota != null){
            if($usuario != null){
                if($tipoMascota->tipo == $tipo ){
                    if($usuario->id)
                    $mascota = new Mascota;
                    
                    $mascota->nombre = $nombre;
                    
                    // $mascota->users()->save($usuario);


                    // $mascota->tipoMascota()->associate($tipoMascota);
                    $mascota->cliente_id = $usuario->id;
                    $mascota->fecha_nacimiento = $fechaNac;
                    $mascota->tipo_mascota_id = $tipoMascota->id;
                    // $mascota->users()->associate($usuario);
                    // $mascota->users()->associate($usuario->id);
                    $mascota->save();

                    
                    
                    
                    $datos['datos'] = 'Alta de mascota correctamente';
                    // var_dump($usuario);
                }
                
            }
            else{
                $datos['datos'] = 'No se encuentra el usuario con el id' . $idCliente;
            }
            //el cliente_id de la tabla mascota es el id del dueño de la mascota.
            
        }
        else{
            $datos['datos'] = 'No existe el tipo de mascota ingresada';
        }

        // var_dump($tipoMascota);
        $payload = json_encode($datos);
        $response->getBody()->write($payload);
        
        
        return $response
          ->withHeader('Content-Type', 'application/json');

    }
    /** Asociar una tabla con otra si tiene la clave foranea
     * $account = Account::find(10);

     *   $user->account()->associate($account);

     *   $user->save();
     */
    
}