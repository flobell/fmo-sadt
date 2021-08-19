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
class Application_Model_Nivel {

    public static function listarPares($condicion,$zona=0) {
        /*  select * 
            from zona
            where nivel = 3 and id in (select id_zona from zona)*/
        $tNivel = new Application_Model_DbTable_Zona();
        
        
        $sel = $tNivel->select()
                     ->from(array('a' => $tNivel->info(Zend_Db_Table::NAME)), array('a.id','a.nombre'), $tNivel->info(Zend_Db_Table::SCHEMA))
                     ->where($condicion)
                     ->where('substring(id from char_length(id) for 1) <> ? ', '0');
        if(!$zona){
            $sel->where("id IN (?)", new Zend_Db_Expr("select id_zona from zona where id_zona <>''"));
        }
        //echo '<br/><br/>este es',  var_dump($sel->__toString());
        return $tNivel->getAdapter()->fetchPairs($sel);
    }
    
    public static function getNivel($id) {
        /*  select * 
            from zona
            where nivel = 3 and id in (select id_zona from zona)*/
        $tNivel = new Application_Model_DbTable_Nivel();
        
        
        $sel = $tNivel->select()
                     ->from(array('a' => $tNivel->info(Zend_Db_Table::NAME)), array('a.id','a.nombre'), $tNivel->info(Zend_Db_Table::SCHEMA))
                     ->where('id = ? ',$id);
        return $tNivel->getAdapter()->fetchRow($sel);
    }

}
