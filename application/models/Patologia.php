<?php

/**
 *
 * @author Pedro Flores
 */
class Application_Model_Patologia
{

    public static function listarPares()
    {
        $tPatologia = new Application_Model_DbTable_Patologia();
        $select = $tPatologia->select()
        ->from(
            array('a' => $tPatologia->info(Zend_Db_Table::NAME)), 
            array('a.id', 'a.nombre'), 
            $tPatologia->info(Zend_Db_Table::SCHEMA)
        );
        $select->order('a.nombre ASC');
        return $select->getAdapter()->fetchPairs($select);
    }

    public static function listarParesExistente($cedula)
    {
        $tPatologia = new Application_Model_DbTable_Patologia();
        $tTrabajadorPatologia = new Application_Model_DbTable_TrabajadorPatologia();

        $select = $tPatologia->select()
        ->from(
            array('a' => $tPatologia->info(Zend_Db_Table::NAME)), 
            array('a.id', 'a.nombre'), 
            $tPatologia->info(Zend_Db_Table::SCHEMA)
        )
        ->joinInner(array('b' => $tTrabajadorPatologia->info(Zend_Db_Table::NAME)), 'b.id_patologia = a.id', null, $tTrabajadorPatologia->info(Zend_Db_Table::SCHEMA))
        ->where("b.cedula = $cedula")
        ->order('a.nombre ASC');
        
        return $select->getAdapter()->fetchPairs($select);
    }

    public static function listarExistentes($cedula)
    {
        $tPatologia = new Application_Model_DbTable_Patologia();
        $tTrabajadorPatologia = new Application_Model_DbTable_TrabajadorPatologia();

        $select = $tPatologia->select()
        ->from(
            array('a' => $tPatologia->info(Zend_Db_Table::NAME)), 
            array('a.id'), 
            $tPatologia->info(Zend_Db_Table::SCHEMA)
        )
        ->joinInner(array('b' => $tTrabajadorPatologia->info(Zend_Db_Table::NAME)), 'b.id_patologia = a.id', null, $tTrabajadorPatologia->info(Zend_Db_Table::SCHEMA))
        ->where("b.cedula = $cedula")
        ->order('a.nombre ASC');
  
        return $select->getAdapter()->fetchAll($select);
    }


    public static function buscar($usuario)
    {
        $tTrabajadorPatologia = new Application_Model_DbTable_TrabajadorPatologia();
        $tPatologia = new Application_Model_DbTable_Patologia();
        
        $select = $tTrabajadorPatologia->select()->setIntegrityCheck(false)
        ->from(
            array('a' => $tTrabajadorPatologia->info(Zend_Db_Table::NAME)), 
            array(
                'id'                => 'a.id', 
                'cedula'            => 'a.cedula', 
                'id_patologia'      => 'b.id', 
                'patologia'         => 'b.nombre', 
                'anadido_por'       => 'a.anadido_por', 
                'fecha_creacion'    => 'a.fecha_creacion', 
            ), 
            $tTrabajadorPatologia->info(Zend_Db_Table::SCHEMA)
        )
        ->joinInner(array('b' => $tPatologia->info(Zend_Db_Table::NAME)), 'b.id = a.id_patologia', null, $tPatologia->info(Zend_Db_Table::SCHEMA))
        ->where("cedula = $usuario->cedula")
        ->order('b.nombre ASC');

        return $tTrabajadorPatologia->getAdapter()->fetchAll($select);
    }

    public static function existe($cedula, $id_patologia){

        $tTrabajadorPatologia = new Application_Model_DbTable_TrabajadorPatologia();

        $select = $tTrabajadorPatologia->select()->setIntegrityCheck(false) 
        ->from(
            array('a' => $tTrabajadorPatologia->info(Zend_Db_Table::NAME)), 
            array('id' => 'a.id'), 
            $tTrabajadorPatologia->info(Zend_Db_Table::SCHEMA)
        )
        ->where("a.cedula = $cedula")
        ->where("a.id = $id_patologia");

        return $tTrabajadorPatologia->getAdapter()->fetchRow($select)?true:false;

    }
}