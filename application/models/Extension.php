<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Personal
 *
 * @author Pedro Flroes <fmo16554@ferrominera.gob.ve>
 */
class Application_Model_Extension {

    public static function listarPares($condicion = NULL) {

        $tExtension = new Application_Model_DbTable_Extension();        
        
        $sel = $tExtension->select()
        ->from(
            array('a' => $tExtension->info(Zend_Db_Table::NAME)), 
            array('a.extension','a.texto'), 
            $tExtension->info(Zend_Db_Table::SCHEMA))
        ->order('a.extension');

        if($condicion != NULL) $sel->where($condicion);

        //print_r ($sel->__toString());
        //echo '<br/>este es',  var_dump($sel->__toString());
        //Zend_Debug::dd($tExtension->getAdapter()->fetchPairs($sel));
        return $tExtension->getAdapter()->fetchPairs($sel);
    }

}