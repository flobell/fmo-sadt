<?php

/**
 *
 * @author Pedro Flores
 */
class Application_Model_Medicamento
{

    public static function listarPares()
    {
        $tMedicamento = new Application_Model_DbTable_Medicamento();
        $select = $tMedicamento->select()
        ->from(
            array('a' => $tMedicamento->info(Zend_Db_Table::NAME)), 
            array('a.c_medicamento', 'a.d_medicamento'), 
            $tMedicamento->info(Zend_Db_Table::SCHEMA)
        );
        $select->order('a.d_medicamento ASC');
        return $select->getAdapter()->fetchPairs($select);
    }

    public static function listarExistentes($cedula)
    {
        $tMedicamento = new Application_Model_DbTable_Medicamento();
        $tTrabajadorMedicamento= new Application_Model_DbTable_TrabajadorMedicamento();

        $select = $tMedicamento->select()
        ->from(
            array('a' => $tMedicamento->info(Zend_Db_Table::NAME)), 
            array('a.c_medicamento'), 
            $tMedicamento->info(Zend_Db_Table::SCHEMA)
        )
        ->joinInner(array('b' => $tTrabajadorMedicamento->info(Zend_Db_Table::NAME)), 'b.id_medicamento = a.c_medicamento', null, $tTrabajadorMedicamento->info(Zend_Db_Table::SCHEMA))
        ->where("b.cedula = $cedula")
        ->order('a.d_medicamento ASC');
  
        return $select->getAdapter()->fetchAll($select);
    }


    public static function buscar($usuario)
    {
        $tTrabajadorMedicamento = new Application_Model_DbTable_TrabajadorMedicamento();
        $tMedicamento = new Application_Model_DbTable_Medicamento();
        $tPatologia = new Application_Model_DbTable_Patologia();
        
        $select = $tTrabajadorMedicamento->select()->setIntegrityCheck(false)
        ->from(
            array('a' => $tTrabajadorMedicamento->info(Zend_Db_Table::NAME)), 
            array(
                'id'                => 'a.id', 
                'cedula'            => 'a.cedula', 
                'id_medicamento'    => 'a.id_medicamento', 
                'medicamento'       => 'b.d_medicamento',
                'id_patologia'      => 'a.id_patologia', 
                'patologia'         => new Zend_Db_Expr("CASE WHEN c.nombre IS NULL THEN 'SIN PATOLOGÍA ASOCIADA' ELSE c.nombre END"), 
                'indicacion'        => new Zend_Db_Expr("CASE WHEN a.indicacion IS NULL THEN 'SIN INDICACIÓN' ELSE a.indicacion END"),
                'dosis'             => 'a.dosis',
                'frecuencia'        => 'a.frecuencia',
                'id_informe'           => 'a.informe_adjunto_id',
                'anadido_por'       => 'a.anadido_por', 
                'fecha_creacion'    => 'a.fecha_creacion', 
            ), 
            $tTrabajadorMedicamento->info(Zend_Db_Table::SCHEMA)
        )
        ->joinInner(array('b' => $tMedicamento->info(Zend_Db_Table::NAME)), 'b.c_medicamento = a.id_medicamento', null, $tMedicamento->info(Zend_Db_Table::SCHEMA))
        ->joinFull(array('c' => $tPatologia->info(Zend_Db_Table::NAME)), 'c.id = a.id_patologia', null, $tPatologia->info(Zend_Db_Table::SCHEMA))
        ->where("a.cedula = $usuario->cedula")
        ->order('b.d_medicamento ASC');

        return $tTrabajadorMedicamento->getAdapter()->fetchAll($select);
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

    public static function eliminarMedicamentoTrabajador($cedula, $id_medicamento){

        $tTrabajadorMedicamento = new Application_Model_DbTable_TrabajadorMedicamento();
        $where = $tTrabajadorMedicamento->getAdapter()->quoteInto("cedula = $cedula AND id_medicamento = ?", $id_medicamento);    
        $resultado = $tTrabajadorMedicamento->delete($where);

        if($resultado>0) return true;
        return false;
    }


}