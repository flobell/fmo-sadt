<?php

/**
 * Controlador para las tarifas
 *
 * @author felixpga
 */
class AsignarController extends Fmo_Controller_Action_Abstract {

    /**
     * Acción para listar los servicios
     */
    public function confirmarAction() {
        //$urlVolver = $this->getRequest()->getModuleName() . "/" . $this->getRequest()->getControllerName() . "/listado";
    }

    /**
     * Acción para listar los servicios
     */
    public function asignarAction() {
        $urlVolver = "default/direccion/nuevo";
        try {
            $usuario = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::CEDULA};
            $us = Fmo_Model_Seguridad::getUsuarioSesion();
            $this->view->centro = $us->organigrama;
            $this->view->usuario = Fmo_Model_Seguridad::getUsuarioSesion();
            $form = new Application_Form_Ruta();
            $listado = null;
            if ($this->getRequest()->isPost()) {
                $post = $this->getRequest()->getPost();
                $form->recargar($post);
                if ($this->getParam(Application_Form_Ruta::E_PARROQUIA) != '') {
                    $listado = Application_Model_Direccion::listarTrabajadores($this->getParam(Application_Form_Direccion::E_ZONA));
                } else {
                    /*if ($this->getParam(Application_Form_Ruta::E_MUNICIPIO) != '') {
                        $listado = Application_Model_Direccion::listarTrabajadores($this->getParam(Application_Form_Direccion::E_CIUDAD));
                    } else {*/
                        $listado = Application_Model_Direccion::listarTrabajadores();
                    //}
                }
            } else {
                $listado = Application_Model_Direccion::listarTrabajadores();
            }
            $form->getElement(Application_Form_Ruta::E_ACEPTAR)->setLabel('Buscar');
        } catch (Exception $ex) {
            //var_dump($ex);
            $this->addMessageWarning($ex->__toString());
        }
        $this->view->form = $form;
        /* if(!$listado){
          $this->addMessageWarning('No Existen Trabajadores Cargados en esta Area');
          } */
        $this->view->listado = $listado;
        //$this->renderScript('nuevo.phtml');
    }

}
