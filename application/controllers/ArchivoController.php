<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArchivoController
 *
 * @author Felixpga
 */
Class ArchivoController extends Fmo_Controller_Action_Abstract {

    public function verAction() 
    {
        $pId = $this->getParam('id', '');
        try {
            $adj = Application_Model_DbTable_Adjunto::findOneById($pId);
            if (!$adj) {
                throw new Exception("No existe el archivo de código '{$pId}'");
            }
            if (!is_readable($adj->ruta_almacenamiento)) {
                throw new Exception("No es posible leer el archivo '{$adj->nombre}'");
            }
            $this->_helper->viewRenderer->setNoRender();
            $this->_helper->layout()->disableLayout();
            $this->getResponse()
                 ->setHeader('Content-Type', "{$adj->tipo_mime}; charset=UTF-8")
                 ->setHeader('Content-disposition', "attachment; filename=\"{$adj->nombre}\"")
                 ->setHeader('Cache-Control', 'public, must-revalidate, max-age=0')
                 ->setHeader('Content-Length', $adj->tamanio)
                 ->setHeader('Pragma', 'public')
                 ->setHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT')
                 ->setHeader('Last-Modified', gmdate('D, d M Y H:i:s \G\M\T'));
            ob_clean();
            readfile($adj->ruta_almacenamiento);
        } catch (Exception $ex) {
            $this->addMessageException($ex);
            $this->backUrlSession();            
        }
    }

    public function informeAction() 
    {
        $id = $this->getParam('id', '');
        try {
            $informe = Application_Model_DbTable_AdjuntoInforme::findOneById($id);
            if (!$informe) {
                throw new Exception("No existe el informe de código '{$id}'");
            }
            if (!is_readable($informe->ruta_almacenamiento)) {
                throw new Exception("No es posible leer el documento '{$informe->nombre}'");
            }
            $this->_helper->viewRenderer->setNoRender();
            $this->_helper->layout()->disableLayout();
            $this->getResponse()
                 ->setHeader('Content-Type', "{$informe->tipo_mime}; charset=UTF-8")
                 ->setHeader('Content-disposition', "attachment; filename=\"{$informe->nombre}\".pdf")
                 ->setHeader('Cache-Control', 'public, must-revalidate, max-age=0')
                 ->setHeader('Content-Length', $informe->tamanio)
                 ->setHeader('Pragma', 'public')
                 ->setHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT')
                 ->setHeader('Last-Modified', gmdate('D, d M Y H:i:s \G\M\T'));
            ob_clean();
            readfile($informe->ruta_almacenamiento);
        } catch (Exception $ex) {
            $this->addMessageException($ex);
            $this->backUrlSession();            
        }
    }

}
