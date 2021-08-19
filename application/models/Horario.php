<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Personal
 *
 * @author Felix Patete - F12816 <felixpga@ferrominera.com>
 */
class Application_Model_Horario {
    
     public static function getPairs()
    {

        $tHorario = new Application_Model_DbTable_Horario();
        $select = $tHorario
                ->select()->distinct()
                ->from(array('a' => $tHorario->info(Zend_Db_Table::NAME)), array('id' => 'a.id',
                    'descripcion' => 'a.descripcion'), $tHorario->info(Zend_Db_Table::SCHEMA));
        $select->order('id ASC');
        
        //print_r ($select->__toString());
        //var_dump ($select->__toString());
        
//        $select->getAdapter()->fetchPairs($select);
//        $select->__toString();      
//        exit;
        return $select->getAdapter()->fetchPairs($select);
    }
  
}