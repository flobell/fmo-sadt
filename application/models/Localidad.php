<?php

/*
 * Copyright (C) 2018 Enoc Carrasquero <enocc@ferrominera.com>
 */

/**
 * Description of Período
 *
 * @author enocc
 */
class Application_Model_Localidad
{
    //Campos de la tabla período
    const ID            = 'id';
    const ABREV  = 'abrev';
    const DESCRIPCION     = 'descripcion';
    const STATUS = 'status';

    
    /**
     * Combo de las localidades
     * @return type
     */
    public static function getPairs()
    {
        $tLocalidad = new Application_Model_DbTable_Localidad();
        $select = $tLocalidad
                ->select()->distinct()
                ->from(array('a' => $tLocalidad->info(Zend_Db_Table::NAME)), array('id' => 'a.id',
                    'descripcion' => 'a.descripcion'), $tLocalidad->info(Zend_Db_Table::SCHEMA));
                //->where('estado = ? ', 'ABIERTO');
        $select->order('descripcion ASC');
        
//        $select->getAdapter()->fetchPairs($select);
//        $select->__toString();      
//        exit;
        return $select->getAdapter()->fetchPairs($select);
    }
    

    
}