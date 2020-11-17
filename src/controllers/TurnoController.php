<?php
namespace App\Controllers;
use Clases\Usuario;

use Clases\Servicio;

use App\Models\Vehiculo;
use App\Models\Service;
use App\Models\Turno;
use App\Models\User;
use App\Models\Mascota;
use Clases\Token;


class TurnoController {
    public $datos = array ('datos' => '.');

    public function altaTurno($request, $response, $args){
        
        $parsedBody = $request->getParsedBody();
        
        $veterinario_id = $parsedBody['veterinario_id'];
        $mascota_id = $parsedBody['mascota_id'];
        $datetime = $parsedBody['datetime'];

        $fechaYHora = explode(' ',$datetime);
        // var_dump($fechaYHora[0]);

        $fecha = $fechaYHora[0];
        $hora = $fechaYHora[1];
        $hrMin = array('horaMin' => '09:00:00');
        $hrMax = array('horaMax' => '17:00:00');
        
        // $turno = Turno::where('veterinario_id',$veterinario_id)
        //         ->where('fecha',$datetime)->first();
        $turno = Turno::select('veterinario_id', 'fecha')->where('fecha',$datetime)->groupBy('veterinario_id', 'fecha')->get();
       
        // echo count($turno);
        if($turno != []){ //hay turno con ese veterinario y fecha
            
            if($hrMin['horaMin'] <= $hora && $hora <= $hrMax['horaMax'] ){
                if(count($turno) == 1){
                    $fechaYHoraBase = explode(' ',$turno[0]['fecha']);
                    $fechaBase = $fechaYHora[0];
                    $horaBase = $fechaYHora[1];
                    if($turno[0]['veterinario_id'] != $veterinario_id){
                        
                        $turnoNuevo = new Turno;
                        $turnoNuevo->fecha = $datetime;
                        $turnoNuevo->veterinario_id = $veterinario_id;
                        $turnoNuevo->mascota_id = $mascota_id;
                        $turnoNuevo->save();
                        $datos['datos'] = "Turno dado de alta correctamente";
                        // var_dump($turno);
                    }
                    else{
                        $datos['datos'] = "Veterinario ocupado";
                    }

                }
                else if(count($turno) == 0){
                    $turnoNuevo = new Turno;
                    $turnoNuevo->fecha = $datetime;
                    $turnoNuevo->veterinario_id = $veterinario_id;
                    $turnoNuevo->mascota_id = $mascota_id;
                    $turnoNuevo->save();
                    $datos['datos'] = "Turno dado de alta correctamente";
                    // var_dump($turno);
                    
                }
                else{
                    $datos['datos'] = "Veterinario ocupado para el horario";
                }
                // $turno = Turno::select('veterinario_id', 'fecha')->where('fecha',$fecha)->groupBy('veterinario_id', 'fecha')->get();
    
            }
            else{
                $datos['datos'] = "Horario fuera de rango de atencion";
            }

        }
        else{
            try {
                
                $turnoNuevo = new Turno;
                $turnoNuevo->fecha = $datetime;
                $turnoNuevo->veterinario_id = $veterinario_id;
                $turnoNuevo->mascota_id = $mascota_id;
                $turnoNuevo->save();
                $datos['datos'] = "Turno dado de alta correctamente";
            } catch (\Throwable $th) {
                $datos['datos'] = "No existe alguno de los id ingresados";
            }

        }
        
        $payload = json_encode($datos);
        $response->getBody()->write($payload);
        
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function calcularEdad($fechaNacimiento){
        $fecha = explode(' ',$fechaNacimiento);
        // Y-n-d
    }
    public function getDatosTurno($request, $response, $args){
        $idUsuario = $args['idUsuario'];
        $turno = Turno::select('turnos.veterinario_id', 'turnos.fecha', 'turnos.mascota_id', 'mascotas.nombre', 
                            'usuarios.usuario as vet', 'cliente.usuario')
                                //->whereDate('fecha', '2020-11-20')
                            ->join('usuarios', 'usuarios.id', '=', 'turnos.veterinario_id')
                            ->join('mascotas', 'mascotas.id', '=', 'turnos.mascota_id')
                            ->join('usuarios as cliente', 'cliente.id', '=', 'mascotas.cliente_id')
                            //->join('mascotas', 'mascotas.cliente_id', 'usuarios.id')
                            ->get();

        // $turno = Turno::where('veterinario_id',$idUsuario)
        
        // ->join('usuarios','usuarios.id','=','veterinario_id')
        // ->join('mascotas','mascotas.id','=','mascota_id')
        // // ->join('mascotas','usuarios.id','=','cliente_id')
        
        // ->first();
        
        
        // echo $turno;
        // echo $turno;
        // $cliente = User::where('id',$turno->cliente_id)->first();
        // // echo $cliente;
        // // $datos['datos'] = "Nombre del animal ". $turno->nombre .  "Fecha y hora" . $turno->fecha . "Mail del dueño" . $turno->email;
        echo $turno;
        // $datos['nombre de la mascota'] = $turno->nombre;
        // $datos['fecha y hora'] = $turno->fecha;
        // $datos['mail del dueño'] = $cliente->email;
        // $datos['edad de mascota'] = $cliente->email;
        $payload = json_encode($datos);
        $response->getBody()->write($payload);
        
        return $response
          ->withHeader('Content-Type', 'application/json');
        // $usuario = Usuario::where('tipo', $tipo)->first();

        
    }
    
    
    
}