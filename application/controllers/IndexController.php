<?php

/**
 * Controlador principal
 */
class IndexController extends Fmo_Controller_Action_Abstract {

    /**
     * Acción por defecto
     */
    public function indexAction() {

        //Redirect Enoc
//        if (Zend_Filter::filterStatic($this->getParam('redirect', '0'), 'Boolean')) {
//            $this->redirect('default/');
//        } else {
//            $this->forward('portada');
//        }
        //Redirect de Antonio        
//        if (Zend_Filter::filterStatic($this->getParam('redirect', '0'), 'Boolean')) {
//
//            $this->redirect('default/direccion/ver');
//        } else {
            $style = '#menu ,  #informacion { display: none; },  #contenido { top: 0%; } ';
            $this->view->headStyle()->appendStyle($style);
            $this->view->sistema = Zend_Registry::get('sistema');
            $this->view->usuario = Fmo_Model_Seguridad::getUsuarioSesion();
            //$this->forward('portada');
//        }
    }

}
