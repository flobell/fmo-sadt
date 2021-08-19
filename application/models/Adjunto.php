<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SistemaC
 *
 * @author Felixpga
 */
class Application_Model_Adjunto {
    /* public static function createRegistro($datos) {

      $recurso = new Application_Model_DbTable_Rol();
      $row = $recurso->createRow();
      $row->setFromArray($datos);
      return $row->save();
      }
     */

    public static function buscarPorDescripcion($adjunto) {

        $row = Application_Model_DbTable_Adjunto::findOneByColumn('nombre = ?', $adjunto);
        return $row ? Application_Model_DbTable_Adjunto::findOneById($row->id) : null;
    }

    public static function buscar($id) {
        $tArchivo = new Application_Model_DbTable_Adjunto();
        $sel = $tArchivo->select()
                ->from(array('a' => $tArchivo->info(Zend_Db_Table::NAME)), array('*'), $tArchivo->info(Zend_Db_Table::SCHEMA))
                ->where('id = ? ', $id);
        $resultado = $tArchivo->getAdapter()->fetchAll($sel);
        if ($resultado) {
            return $resultado[0];
        }
    }

}
