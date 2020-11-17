<?php

namespace Clases;

class Turno
{ 
    public $fecha;
    public $disponible = true;

    public function __construct($fecha,$disponible)
    {
        
        $this->fecha = $fecha;
        $this->disponible = $disponible;
        
    }
    
    public function validarTurnoDisponible($fecha){
        if($disponible){
            return $fecha;
        }
        else{
            return $this->disponible = false;
        }
    }
    
}

?>
