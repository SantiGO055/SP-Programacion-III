<?php
namespace Clases;
use \Clases\Materias;
class Profesor{
    public $legajo; //int
    public $nombre; //string
    public $materias; //de tipo lista de Materias
    public $turno; //string

    public function __construct($nombre, $legajo = 0, $materias = "sinMateria", $turno = "sinTurno"){
        $this->nombre = $nombre;
        if ($legajo === 0) {
            $this->legajo = rand(50,100) + $legajo;
        }
        else{
            $this->legajo = $legajo;
        }
        $this->turno = $turno;
        $this->materias = $materias;
    }
    public static function asignarMateria($listaDeMaterias,$listaProfes,$listaDeMateriasProfe, $legajo,$idMateria,$turno){
        if(file_exists("./materias-profesores.json")/*isset($listaDeMateriasProfe) && $listaDeMateriasProfe != null*/) {
            foreach ($listaDeMaterias as $materia) {
                if ($materia['_id'] == $idMateria && $listaDeMateriasProfe != $idMateria) {
                
                    foreach ($listaProfes as $profesor) {
                        if($profesor['legajo'] == $legajo){
                            foreach ($listaDeMateriasProfe as $materiasProfesores) {
                                if ($materiasProfesores['legajo'] != $legajo) {
                                    
                                    $materiaProf = new Materias($materia['_nombre'],$materia['_cuatrimestre'],$materia['_id']);
                                    $profeMateria = new Profesor($profesor['nombre'],$profesor['legajo'],$materiaProf,$turno);
                                    //var_dump($profeMateria);
                                    $datos = $profeMateria;
                                }
                                else if ($materiasProfesores['turno'] != $turno) {
                                        $materiaProf = new Materias($materia['_nombre'],$materia['_cuatrimestre'],$materia['_id']);
                                        $profeMateria = new Profesor($profesor['nombre'],$profesor['legajo'],$materiaProf,$turno);
                                        //var_dump($profeMateria);
                                        
                                        return $profeMateria;
                                }
                                else{
                                    $datos = "El profesor ya posee la materia asignada";
                                }
                            }
                        }
                        
                        else{
                            $datos = "legajo no encontrado";
                        }
                    }
                }
                else{
                    $datos = "id de materia no encontrado";
                }
            }
        }
        else{
            foreach ($listaDeMaterias as $materia) {
                if ($materia['_id'] == $idMateria) {
                    foreach ($listaProfes as $profesor) {
                        if ($profesor['legajo'] == $legajo) {
                        
                            $materiaProf = new Materias($materia['_nombre'],$materia['_cuatrimestre'],$materia['_id']);
                            $profeMateria = new Profesor($profesor['nombre'],$profesor['legajo'],$materiaProf,$turno);
                            return $profeMateria;
                        
                        }
                        else{
                            echo "entre";
                            $datos = "legajo no encontrado";
                        }
                    }
                }
                else{
                    $datos = "idMateria no encontrado";
                }
            }
        }
        return $datos;
    }

    public static function mostrarProfesores(){
        $retorno = '';
        $listaProfesores = Archivos::leerJson("profesores.json", $listaProfesores);
        if(isset($listaProfesores))
        {
            
            foreach ($listaProfesores as $aux) 
            {
                $retorno .=  $aux['legajo']. ' ,' . $aux['nombre']. ' ,' .$aux['materias']. ' ,' . $aux['turno']. ' ,' .PHP_EOL;    
            }   
        }
        return $retorno;
    }
    public static function mostrarMateriasAsignadas(){
        $retorno = '';
        $listamateriasProfesores = Archivos::leerJson("materias-profesores.json", $listamateriasProfesores);
        if(isset($listamateriasProfesores))
        {
            
            foreach ($listamateriasProfesores as $aux) 
            {
                $retorno .=  $aux['legajo']. ' ,' . $aux['nombre']. ' ,' .$aux['materias']['_nombre']. ' ,' .$aux['materias']['_cuatrimestre']. ' ,'.$aux['materias']['_id']. ' ,' .$aux['turno'] .' ,' .PHP_EOL;    
            }   
        }
        return $retorno;
    }

}


?>