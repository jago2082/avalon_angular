<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Configuracion
 *
 * @author JPenagos
 */
class Lectura {
    private $connection;

    
    // Propiedades
    public $matricula;
    public $fecha;
    public $hora;
    public $anterior;
    public $actual;
    public $observaciones;
    public $empresa;
    public $mov;
    public $consumo;
    
    //Getters

    function getMatricula() {
        return $this->matricula;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getHora() {
        return $this->hora;
    }

    function getAnterior() {
        return $this->anterior;
    }

    function getActual() {
        return $this->actual;
    }

    function getObservaciones() {
        return $this->observaciones;
    }

    function getEmpresa() {
        return $this->empresa;
    }

    function getMov() {
        return $this->mov;
    }

    function getConsumo() {
        return $this->consumo;
    }


    // Setters

    function setMatricula($matricula) {
        $this->matricula = (int)$matricula;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setHora($hora) {
        $this->hora = $hora;
    }

    function setAnterior($anterior) {
        $this->anterior = (int)$anterior;
    }

    function setActual($actual) {
        $this->actual = (int)$actual;
    }

    function setObservaciones($observaciones) {
        $this->observaciones = (int)$observaciones;
    }

    function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    function setMov($mov) {
        $this->mov = $mov;
    }

    function setConsumo($consumo) {
        $this->consumo = (int)$consumo;
    }

        
    //Constructor
    public function __construct($connection){
        $this->connection = $connection;
    }

    

    


 /**
 * saveLect
 *
 * Este metodo inserta los registros en la tabla de lecturas
 *
 * @param array|mixed $data
 * @return  boolean $filas  
 */

public function saveLect($data){

        
        try {
        
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'matricula':
                $this->setMatricula($value);
                break;

                case 'fecha':
                $this->setFecha($value);
                break;

                case 'hora':
                $this->setHora($value);
                break;
                
                case 'anterior':
                $this->setAnterior($value);
                break;

                case 'actual':
                $this->setActual($value);
                break;

                case 'observaciones':
                $this->setObservaciones($value);
                break;

                case 'empresa':
                $this->setEmpresa($value);
                break;
                
                case 'mov':
                $this->setMov($value);
                break;

                case 'consumo':
                $this->setConsumo($value);
                break;
                
                default:
                    # code...
                    break;
            }
              
        }
            $sql ="INSERT INTO lectura (lc_nromat, lc_fecreg, lc_horreg, lc_lecant, lc_lecact, lc_observ, lc_codemp, lc_tipomv, lc_consumo) VALUES (".$this->getMatricula().",'".$this->getFecha()."','".$this->getHora()."', ".$this->getAnterior().", ".$this->getActual().", ".$this->getObservaciones().", '".$this->getEmpresa()."', '".$this->getMov()."', ".$this->getConsumo().")";
            $sth = $this->connection->prepare($sql);
            $filas = $sth->execute(); 

        } catch (PDOException $e) {
            return  $e->getMessage();
        }
        return $filas;
    }
    

}