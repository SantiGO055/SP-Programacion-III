<?php
namespace Clases;
class Materias{
    public $_nombre;
    public $_cuatrimestre;
    public $_id;
    
    public function __construct($nombre,$cuatrimestre,$id = 0){
        $this->_nombre = $nombre;
        $this->_cuatrimestre = $cuatrimestre;
        if ($id === 0) {
            $this->_id = rand(1,100);
        }
        else{
            $this->_id = $id;
        }
    }

    public static function mostrarMaterias(){
        $retorno = '';
        $listaMaterias = Archivos::leerJson("materias.json", $listaMaterias);
        if(isset($listaMaterias))
        {
            
            foreach ($listaMaterias as $aux) 
            {
                $retorno .=  $aux['_nombre']. ' ,' . $aux['_cuatrimestre']. ' ,' .$aux['_id']. ' ,' .PHP_EOL;    
            }   
        }
        return $retorno;
    }



}


?>