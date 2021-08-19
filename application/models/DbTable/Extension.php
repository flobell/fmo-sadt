<?php

/**
 * Clase 
 *
 * @author Pedro Flroes <fmo16554@ferrominera.gob.ve>
 */
class Application_Model_DbTable_Extension extends Application_Model_DbTable_Abstract 
{

    protected $_schema = 'sch_sadt';
    protected $_name = 'extension';
    protected $_sequence = true;
    protected $_referenceMap = array(
        'Zona' => array(
            self::COLUMNS => 'id_zona',
            self::REF_TABLE_CLASS => 'Application_Model_DbTable_Zona',
            self::REF_COLUMNS => 'id'
        )
    );
    
}