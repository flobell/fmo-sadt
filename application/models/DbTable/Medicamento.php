<?php


/**
 * Description of Período
 *
 * @author Pedro Flores 
 */
class Application_Model_DbTable_Medicamento extends Application_Model_DbTable_Abstract
{
    protected $_schema = 'sch_sadt';
    protected $_name = 'medicamento';
    protected $_sequence = true;
    protected $_primary = array('c_medicamento');

}