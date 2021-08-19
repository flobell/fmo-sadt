<?php

/**
 * Clase 
 *
 * @author Felix Patete <felixpga@ferrominera.com>
 */
class Application_Model_DbTable_Direccion extends Application_Model_DbTable_Abstract 
{
   
    protected $_name = 'direccion';
    protected $_sequence = true;
    protected $_referenceMap = array(
        'Zona' => array(
            self::COLUMNS => 'id_zona',
            self::REF_TABLE_CLASS => 'Application_Model_DbTable_Zona',
            self::REF_COLUMNS => 'id'
        ),
        'Referencia' => array(
            self::COLUMNS => 'id_zona',
            self::REF_TABLE_CLASS => 'Application_Model_DbTable_Referencia',
            self::REF_COLUMNS => 'id'
        )
    );
    protected $_dependentTables = array('Application_Model_DbTable_Adjunto');
    
}
