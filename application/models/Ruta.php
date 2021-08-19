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
class Application_Model_Ruta {
    
     public static function getPairs($localidad=null)
    {

        $tRuta = new Application_Model_DbTable_Ruta();
        $select = $tRuta
                ->select()->distinct()
                ->from(array('a' => $tRuta->info(Zend_Db_Table::NAME)), array('id' => 'a.id',
                    'descripcion' => 'a.descripcion'), $tRuta->info(Zend_Db_Table::SCHEMA))
                ->where ("a.status = 'A'")
                ->order('descripcion ASC');
        
        //var_dump ($select->__toString());
        
//        $select->getAdapter()->fetchPairs($select);
//        $select->__toString();      
//        exit;
        return $select->getAdapter()->fetchPairs($select);
    }
    
    public static function getAll ($id_horario = null, $id_ruta= null){
        
        $tRuta = new Application_Model_DbTable_Ruta();
        $select = $tRuta
                ->select()
                ->from(array('a' => $tRuta->info(Zend_Db_Table::NAME)), array('a.*'), $tRuta->info(Zend_Db_Table::SCHEMA))
                ->where ("a.status = 'A'")
                ->order('descripcion ASC');
        
        
//        print_r ($select->__toString());
//        exit;
        
//        $select->getAdapter()->fetchPairs($select);
//        $select->__toString();      
//        exit;
        return $select->getAdapter()->fetchAll($select);
        
    }
    
     public static function getPairsRutaHorario($horario=null)
    {

        $tRuta = new Application_Model_DbTable_Ruta();
        $select = $tRuta
                ->select()->distinct()
                ->from(array('a' => $tRuta->info(Zend_Db_Table::NAME)), array('id' => 'a.id',
                    'descripcion' => 'a.descripcion'), $tRuta->info(Zend_Db_Table::SCHEMA))
                ->where ("a.status = 'A'");
        
        if (!empty($horario)){
            $select->where('id_horario = ?', $horario);    
        }
                       
        $select->order('descripcion ASC');
        
        //var_dump ($select->__toString());
        
//        $select->getAdapter()->fetchPairs($select);
//        $select->__toString();      
//        exit;
        return $select->getAdapter()->fetchPairs($select);
    }
    
    public static function getRutasHorario ($ruta=null, $horario=null){
        
        $tRuta = new Application_Model_DbTable_Ruta();
        $tHorario= new Application_Model_DbTable_Horario();
        $select = $tRuta
                ->select()
                ->setIntegrityCheck(false)
                ->from(array('a' => $tRuta->info(Zend_Db_Table::NAME)), array('a.*', 'b.descripcion as horario_desc'), $tRuta->info(Zend_Db_Table::SCHEMA))
                ->join(array('b' => $tHorario->info(Application_Model_DbTable_Horario::NAME)), 'a.id_horario = b.id', null, $tHorario->info(Application_Model_DbTable_Horario::SCHEMA))
                ->where ("a.status = 'A'");
        
        if (!empty($ruta)){
            $select->where('a.id = ?', $ruta);    
        }
        
        if (!empty($horario)){
            $select->where('a.id_horario = ?', $horario);    
        }
        
        $select->order('a.descripcion ASC');
        
//        print_r ($select->__toString());
//        exit;
        return $select->getAdapter()->fetchAll($select);
        
    }
    
    public static function eliminarRuta ($id_ruta){
        
        try {
            
            
            $ruta = Application_Model_DbTable_Ruta::findOneById($id_ruta);

            $ruta->status= 'I'; //la ruta no se elimina, se inactiva.

            $a =$ruta->save();

            return $a;
        } catch (Exception $ex){
            
            throw new Exception("Error al eliminar la ruta, por favor contacte con el administrador del sistema.");
            
        }
        
    }
    


}