<?php

/*
 * Copyright (C) 2018 Enoc Carrasquero <enocc@ferrominera.com>
 */

/**
 * Description of Período
 *
 * @author enocc
 */
class Application_Model_DbTable_Ruta extends Application_Model_DbTable_Abstract
{
    protected $_name = 'ruta';
    protected $_sequence = true;
    protected $_primary = array('id');
    /*protected $_dependentTables = array(
        'Application_Model_DbTable_Catalogo',
    );*/
}
