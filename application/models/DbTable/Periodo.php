<?php

/*
 * Copyright (C) 2017 Stalin Sanchez <stalins@ferrominera.com>
 */

/**
 * Description of Período
 *
 * @author felixpga
 */
class Application_Model_DbTable_Periodo extends Application_Model_DbTable_Abstract
{
    protected $_name = 'periodo';
    protected $_sequence = true;
    protected $_primary = array('id');
    /*protected $_dependentTables = array(
        'Application_Model_DbTable_Catalogo',
    );*/
}
