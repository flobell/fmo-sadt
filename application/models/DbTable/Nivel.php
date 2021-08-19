<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Adjunto
 *
 * @author Felixpga
 */
class Application_Model_DbTable_Nivel extends Application_Model_DbTable_Abstract
{
    protected $_name = 'nivel';
    protected $_sequence = true;
    protected $_primary = array('id');/*
    protected $_referenceMap = array(
        'Zona' => array(
            self::COLUMNS => 'id',
            self::REF_TABLE_CLASS => 'Application_Model_DbTable_Direccion',
            self::REF_COLUMNS => 'cedula'
        )
    );*/
    protected $_dependentTables = array('Application_Model_DbTable_Zona');
    
     /**
     * Devuelve el listado de los adjuntos
     * 
     * 
     */
    public static function getAdjuntos($id)
    {
        Fmo_Logger::debug($id);
        $tAdj = new Application_Model_DbTable_Adjunto();
        $sel = $tAdj->select()
                ->from(array('a' => $tAdj->info(Zend_Db_Table::NAME)), array('a.id',
                    'a.nombre','a.ruta_almacenamiento','a.tipo_mime','a.tamanio'), $tAdj->info(Zend_Db_Table::SCHEMA))
                ->where('id = ?', $id)
                ->order('a.nombre');
                
        return $tAdj->getAdapter()->fetchAll($sel);
        
    } 
}

