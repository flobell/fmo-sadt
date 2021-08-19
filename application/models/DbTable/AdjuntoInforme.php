<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Adjunto Informe
 *
 * @author fmo16554
 */
class Application_Model_DbTable_AdjuntoInforme extends Application_Model_DbTable_Abstract
{
    protected $_name = 'adjunto_informe';
    protected $_sequence = true;
    protected $_primary = 'id';

    
    public static function getAdjuntos($id)
    {
        Fmo_Logger::debug($id);
        $tAdj = new Application_Model_DbTable_AdjuntoInforme();
        $sel = $tAdj->select()
                ->from(array('a' => $tAdj->info(Zend_Db_Table::NAME)), array('a.id',
                    'a.nombre','a.ruta_almacenamiento','a.tipo_mime','a.tamanio'), $tAdj->info(Zend_Db_Table::SCHEMA))
                ->where('id = ?', $id)
                ->order('a.nombre');
                
        return $tAdj->getAdapter()->fetchAll($sel);
    } 
}
