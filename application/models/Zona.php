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
class Application_Model_Zona {

    public static function listarPares($condicion,$zona=0) {
        /*  select * 
            from zona
            where nivel = 3 and id in (select id_zona from zona)*/
        $tZona = new Application_Model_DbTable_Zona();
        
        
        $sel = $tZona->select()
                     ->from(array('a' => $tZona->info(Zend_Db_Table::NAME)), array('a.id','a.nombre'), $tZona->info(Zend_Db_Table::SCHEMA))
                     ->where($condicion)
                     ->order('a.nombre');
                     //->where('substring(id from char_length(id) for 1) <> ? ', '0');
        if($zona!=0){
            $sel->where("id IN (?)", new Zend_Db_Expr("select id_zona from zona where id_zona is not null"));
        }
        //print_r ($sel->__toString());
        //echo '<br/>este es',  var_dump($sel->__toString());
        return $tZona->getAdapter()->fetchPairs($sel);
    }
    /*
    public static function listarParesTodos($condicion,$zona=0) {
        /*  select * 
            from zona
            where nivel = 3 and id in (select id_zona from zona)*//*
        $tZona = new Application_Model_DbTable_Zona();
        
        
        $sel = $tZona->select()
                     ->from(array('a' => $tZona->info(Zend_Db_Table::NAME)), array('a.id','a.nombre'), $tZona->info(Zend_Db_Table::SCHEMA))
                     ->where($condicion)
                     ->where('substring(id from char_length(id) for 1) <> ? ', '0');
        if(!$zona){
            $sel->where("id IN (?)", new Zend_Db_Expr("select id_zona from zona where id_zona <>''"));
        }
        echo '<br/>este es',  var_dump($sel->__toString());
        return $tZona->getAdapter()->fetchPairs($sel);
    }*/
    
    public static function listarSub($zona=1) {
        /*  select * 
            from zona
            where nivel = 3 and id in (select id_zona from zona)*/
        $tZona = new Application_Model_DbTable_Zona();
        
        
        $sel = $tZona->select()
                     ->from(array('a' => $tZona->info(Zend_Db_Table::NAME)), array('a.id','a.nombre'), $tZona->info(Zend_Db_Table::SCHEMA))
                     ->where('id_zona = ? ',$zona);
        //echo '<br/><br/>este es',  var_dump($sel->__toString());
        return $tZona->getAdapter()->fetchAll($sel);
    }
    
    public static function isPadre($padre, $hijo) {
        $tZona = new Application_Model_DbTable_Zona();
        $sel = $tZona->select()
                     ->from(array('a' => $tZona->info(Zend_Db_Table::NAME)), array('a.id','a.nombre'), $tZona->info(Zend_Db_Table::SCHEMA))
                     ->where('id_zona = ? ',$padre)
                     ->where('id = ? ',$hijo);
        //echo '<br/><br/>este es',  var_dump($sel->__toString());
        return $tZona->getAdapter()->fetchRow($sel);
    }
    
    public static function getMax($zona) {
        /*  select max(substring(id from char_length(id_zona)+2)::numeric)+1
            from zona
            where --nivel = 6 and id in (select id_zona from zona)
            id_zona = '850-6-7-61-6' and substring(id from char_length(id_zona)+2)<>''*/
        $tZona = new Application_Model_DbTable_Zona();
        $max = new Zend_Db_Expr("max(substring(id from char_length(id_zona)+2)::numeric)+1");
        $sel = $tZona->select()
                     ->from(array('a' => $tZona->info(Zend_Db_Table::NAME)), array('nivel','valor'=>$max), $tZona->info(Zend_Db_Table::SCHEMA))
                     ->where('id_zona = ? ',$zona)
                     ->where('substring(id from char_length(id_zona)+2)<> ? ', '')
                     ->group('nivel');
        return $tZona->getAdapter()->fetchRow($sel);
    }
    
    public static function getZona($id){
        $tZona = new Application_Model_DbTable_Zona();
        
        
        $sel = $tZona->select()
                     ->from(array('a' => $tZona->info(Zend_Db_Table::NAME)), array('a.*'), $tZona->info(Zend_Db_Table::SCHEMA))
                     ->where("id = (?)", $id);
        //echo '<br/><br/>este es',  var_dump($sel->__toString());
        return $tZona->getAdapter()->fetchRow($sel);
    }
    
   public static function listarParesLocalidad($condicion=null,$zona=0) {
        /*  select * 
            from zona
            where nivel = 3 and id in (select id_zona from zona)*/
        $tZona = new Application_Model_DbTable_Zona();
        
        
        $sel = $tZona->select()
                     ->from(array('a' => $tZona->info(Zend_Db_Table::NAME)), array('a.id','a.nombre'), $tZona->info(Zend_Db_Table::SCHEMA));
        if(!$zona){
            $sel->where("id IN ('850-6-2-62','850-6-2-63','850-6-1','850-6-8')")
                ->where("id not in ('850-6-2-65')");
        }
        //echo '<br/><br/>este es',  var_dump($sel->__toString());
        return $tZona->getAdapter()->fetchPairs($sel);
    }
    
    public static function getNivel ($id){
        $tZona = new Application_Model_DbTable_Zona();
        
        
        $sel = $tZona->select()
                     ->from(array('a' => $tZona->info(Zend_Db_Table::NAME)), array('a.*'), $tZona->info(Zend_Db_Table::SCHEMA))
                     ->where("id = (?)", $id);
        //echo '<br/><br/>este es',  var_dump($sel->__toString());
        return $tZona->getAdapter()->fetchRow($sel);
    }
    
    
    public static function getParroquias ($id,$nivel){
        
        $tZona = new Application_Model_DbTable_Zona();
        $sel = $tZona->select()
                     ->from(array('a' => $tZona->info(Zend_Db_Table::NAME)), array('a.id','a.nombre'), $tZona->info(Zend_Db_Table::SCHEMA))
                     ->where('id_zona = (?)', $id);
                     //->where("nivel = (?))", $nivel+2);
        
                     //print_r($sel->__toString());
                     
             $parroquia= $tZona->getAdapter()->fetchPairs($sel);
        
        
//        if(empty($parroquia)){
//            //echo "entro en nivel + 1";
//            $sel->orwhere("(id like (?)", "$id-%")
//                ->where("nivel = (?))", $nivel+1);
//            //echo '<br/><br/>este es',  var_dump($sel->__toString());
//            $parroquia= $tZona->getAdapter()->fetchPairs($sel);
//        }
        
        return $parroquia;
                     
//        if (!empty($tZona->getAdapter()->fetchAll($sel))){
//            return $tZona->getAdapter()->fetchAll($sel);
//        }else{
//            $sel->orwhere("nivel = (?)", $nivel+1);
//            echo '<br/><br/>este es',  var_dump($sel->__toString());
//            return $tZona->getAdapter()->fetchAll($sel);
//        }
//     
    }
    
    public static function listarParesNietos ($id,$nivel){
        
        $tZona = new Application_Model_DbTable_Zona();
        $sel = $tZona->select()
                     ->from(array('a' => $tZona->info(Zend_Db_Table::NAME)), array('a.id','a.nombre'), $tZona->info(Zend_Db_Table::SCHEMA))
                     ->where("id like (?)", "$id%")
                     ->where("nivel = (?)", $nivel);
        //echo '<br/><br/>este es',  var_dump($sel->__toString());
        return $tZona->getAdapter()->fetchPairs($sel);
    }

    public static function getOtroPar(){
        $tZona = new Application_Model_DbTable_Zona();
        $sel = $tZona->select()
        ->from(array('a' => $tZona->info(Zend_Db_Table::NAME)), array('a.id','a.nombre'), $tZona->info(Zend_Db_Table::SCHEMA))
        ->where("a.nombre LIKE (?)", "%OTRO%");
        //echo '<br/><br/>este es',  var_dump($sel->__toString());
        return $tZona->getAdapter()->fetchPairs($sel);
    }

}
