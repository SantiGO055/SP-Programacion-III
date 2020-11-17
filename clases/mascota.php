<?php
namespace Clases;
class Mascota{
    public $nombre;
    public $tipo;
    public $cliente_id;
    public $edad;
    public $fechaVisita;
    
    
    public function __construct($nombre,$tipo,$cliente_id,$edad,$fechaVisita){
        $this->_nombre = $nombre;
        $this->tipo = $tipo;
        $this->cliente_id = $cliente_id;
        $this->edad = $edad;
        $this->fechaVisita = $fechaVisita;
    }
    



}


?>