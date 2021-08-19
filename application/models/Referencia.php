<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Personal
 *
 * @author enocc
 */
class Application_Model_Referencia {
    
    const REFERENCIA  = 'descripcion';
    const ID      = 'id';

    public static function getPairs($localidad = null) {

        $tReferencia = new Application_Model_DbTable_Referencia();
        $tRefeZona = new Application_Model_DbTable_RefeZona();
        $select = $tReferencia
                ->select()->distinct()
                ->from(array('a' => $tReferencia->info(Zend_Db_Table::NAME)), array('id' => 'a.id',
                    'descripcion' => 'a.descripcion'), $tReferencia->info(Zend_Db_Table::SCHEMA))
                ->join(array('b' => $tRefeZona->info(Application_Model_DbTable_RefeZona::NAME)), 'a.id=b.id_referencia', null, $tRefeZona->info(Application_Model_DbTable_RefeZona::SCHEMA));

        if (!empty($localidad)) {
            $select->where('b.id_zona = ?', $localidad);
        }
        $select->order('descripcion ASC');

        return $select->getAdapter()->fetchPairs($select);
    }

    //Funcion para llenar el combo de las Referencias...
    public static function getReferencias()
    {   
        $tblReferencia = new Application_Model_DbTable_Referencia();
        
        $sql = $tblReferencia->select()
            ->from(array('c' => $tblReferencia->info(Zend_Db_Table::NAME)), array(
                self::ID => 'c.id',
                self::REFERENCIA => "c.descripcion"
                ), $tblReferencia->info(Zend_Db_Table::SCHEMA))
            ->order('c.descripcion ASC');
    
               // var_dump ($select->__toString());
        //print_r($select->__toString());    
        //exit;
        
        return $tblReferencia->getAdapter()->fetchPairs($sql);
    }    
    
    public static function eliminarReferencia ($id_referencia){
        
        try {
            
            
            $referencia = Application_Model_DbTable_Referencia::findOneById($id_referencia);

            //$referencia->status= 'I'; //la ruta no se elimina, se inactiva.

            $a =$referencia->save();

            return $a;
        } catch (Exception $ex){
            
            throw new Exception("Error al eliminar la referencia, por favor contacte con el administrador del sistema.");
            
        }
        
    }    
    
}
