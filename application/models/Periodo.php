<?php

/*
 * Copyright (C) 2017 Stalin Sanchez <stalins@ferrominera.com>
 */

/**
 * Description of Período
 *
 * @author stalins
 */
class Application_Model_Periodo
{
    //Campos de la tabla período
    const ID            = 'periodo_id';
    const FECHA_INICIO  = 'periodo_fecha_inicio';
    const FECHA_FIN     = 'periodo_fecha_fin';
    const ESTADO        = 'periodo_estado';
    
    const P_ABIERTO     = 'ABIERTO';
    const P_CERRADO     = 'CERRADO';
    
    /**
     * Combo de los periodos abiertos
     * @return type
     */
    public static function getPairs()
    {
        $tPeriodo = new Application_Model_DbTable_Periodo();
        $select = $tPeriodo
                ->select()->distinct()
                ->from(array('a' => $tPeriodo->info(Zend_Db_Table::NAME)), array('id' => 'a.id',
                    'nombre' => 'a.id'), $tPeriodo->info(Zend_Db_Table::SCHEMA));
                //->where('estado = ? ', 'ABIERTO');
        $select->order('nombre ASC');
        return $select->getAdapter()->fetchPairs($select);
    }
    
    /**
     * Combo de los periodos abiertos
     * @return type
     */
    public static function listar($estado='')
    {
        $tPeriodo = new Application_Model_DbTable_Periodo();
        $case = new Zend_Db_Expr("CASE WHEN fecha_fin::date<now()::date or estatus = 0 THEN 'Cerrado' else 'Abierto' END");
        $select = $tPeriodo
                ->select()
                ->from(array('a' => $tPeriodo->info(Zend_Db_Table::NAME)), array('a.*',
                    'estado' => $case), $tPeriodo->info(Zend_Db_Table::SCHEMA));
        if($estado!=''){
            $select->where('fecha_fin::date>=now()::date')
                    ->where('fecha_inicio::date<=now()::date');
        }
                //->where('estado = ? ', 'ABIERTO');
        $select->order('fecha_inicio DESC');
        return $select->getAdapter()->fetchAll($select);
    }
    
}