<?php

/**
 * Clase 
 *
 * @author Roxanna Cortes <felixpga@ferrominera.com>
 */
class Application_Model_DbTable_Zona extends Application_Model_DbTable_Abstract 
{
   
    protected $_name = 'zona';
    protected $_sequence = true;
    protected $_referenceMap = array(
        'Zona' => array(
            self::COLUMNS => 'id_zona',
            self::REF_TABLE_CLASS => 'Application_Model_DbTable_Zona',
            self::REF_COLUMNS => 'id'
        ),
        'Nivel' => array(
            self::COLUMNS => 'nivel',
            self::REF_TABLE_CLASS => 'Application_Model_DbTable_Nivel',
            self::REF_COLUMNS => 'id'
        )
    );
    
}
