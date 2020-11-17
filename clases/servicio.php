<?php
namespace Clases;
class Servicio
{ 
    public $id;
    public $tipo;
    public $precio;
    public $demora;

    public function __construct($id,$tipo,$precio,$demora)
    {
        $this->id = $id;
        $this->tipo = $this->validarTipo($tipo);
        $this->precio = $precio;
        $this->demora = $demora;
    }
    function validarTipo($tipoAux){
        if($tipoAux == 10000 || $tipoAux == 20000 || $tipoAux == 50000){
            return $tipoAux;
        }
        else{
            return 10000;
        }
    }

    public static function crearServicio($id,$tipo,$precio,$demora){
        return $servicio = new Servicio($id,$tipo,$precio,$demora);

    }
    public static function validarServicioId($listaServicios,$id){

        $retorno = false;
        foreach ($listaServicios as $servicio) {
            $servicioA = (object) $servicio;
            if ($servicioA->id == $id) {
                $retorno = true;
            }
            else{
                $retorno = false;
            }
        }
        return $retorno;
    }
    
    
}
?>