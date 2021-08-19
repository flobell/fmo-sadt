<?php

/**
 * Clase 
 *
 * @author Pedro Flores <fmo16554@ferrominera.gob.ve>
 */
class Application_Model_DbTable_TrabajadorMedicamento extends Application_Model_DbTable_Abstract 
{
    protected $_schema = 'sch_sadt';
    protected $_name = 'trabajador_medicamento';
    protected $_primary  = 'id';
    protected $_sequence = true;
}
