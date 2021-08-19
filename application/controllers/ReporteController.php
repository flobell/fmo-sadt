<?php

/**
 * Controlador para las tarifas
 *
 * @author felixpga
 */
class ReporteController extends Fmo_Controller_Action_Abstract {

    /**
     * Acción para listar los servicios
     */
    public function confirmarAction() {
        //$urlVolver = $this->getRequest()->getModuleName() . "/" . $this->getRequest()->getControllerName() . "/listado";
    }

    /**
     * Acción para listar los servicios
     */
    public function trabajadoresAction() {
        $urlVolver = "default/direccion/nuevo";
        $form = new Application_Form_Direccion();
        try {
            $usuario = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::CEDULA};
            $us = Fmo_Model_Seguridad::getUsuarioSesion();
            $this->view->centro = $us->organigrama;
            $this->view->usuario = Fmo_Model_Seguridad::getUsuarioSesion();
            
            $listado = null;
            if ($this->getRequest()->isPost()) {
                $gerencia = $this->getParam(Application_Form_Direccion::E_GERENCIA);
                $ruta = $this->getParam(Application_Form_Direccion::E_RUTA);
                $post = $this->getRequest()->getPost();
                $form->recargar($post);
                $referencia = $this->getParam(Application_Form_Direccion::E_REFERENCIA);
                $localidad = $this->getParam(Application_Form_Direccion::E_LOCALIDAD);
                if ($this->getParam(Application_Form_Direccion::E_ZONA) != '') {
                    $listado = Application_Model_Direccion::listarTrabajadores($this->getParam(Application_Form_Direccion::E_ZONA), $referencia, $gerencia, $ruta, $localidad);
                } else {
                    if ($this->getParam(Application_Form_Direccion::E_PARROQUIA) != '') {
                        $listado = Application_Model_Direccion::listarTrabajadores($this->getParam(Application_Form_Direccion::E_PARROQUIA), $referencia, $gerencia, $ruta, $localidad);
                    } else {
                        if ($this->getParam(Application_Form_Direccion::E_MUNICIPIO) != '') {
                            $listado = Application_Model_Direccion::listarTrabajadores($this->getParam(Application_Form_Direccion::E_MUNICIPIO), $referencia, $gerencia, $ruta, $localidad);
                        } else {
                            $listado = Application_Model_Direccion::listarTrabajadores('', $referencia, $gerencia, $ruta, $localidad);
                        }
                    }
                }
            } else {
                $listado = Application_Model_Direccion::listarTrabajadores();
            }
            $form->getElement(Application_Form_Direccion::E_ACEPTAR)->setLabel('Buscar');

            /* if(!$listado){
              $this->addMessageWarning('No Existen Trabajadores Cargados en esta Area');
              } */
            $this->view->listado = $listado;
        } catch (Exception $ex) {
            //var_dump($ex);
            $this->addMessageWarning($ex->__toString());
        }
        $this->view->form = $form;
        //$this->renderScript('nuevo.phtml');
    }

    /**
     * Acción para ver Direccion
     */
    public function verAction() {
        $urlVolver = "default/reporte/trabajadores";
        try {
            $usuario = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::CEDULA};
            $us = Fmo_Model_Seguridad::getUsuarioSesion();
            $this->view->centro = $us->organigrama;
            $this->view->usuario = Fmo_Model_Seguridad::getUsuarioSesion();
            $form = new Application_Form_Direccion();
            $direccion = Application_Model_Direccion::buscar($usuario);
            if ($this->getParam(Application_Form_Direccion::E_ACEPTAR)) {
                $this->redirect($urlVolver);
            }
            $activo = Application_Model_Periodo::listar('1');
            if (count($activo) > 0) {
                $form->getElement(Application_Form_Direccion::E_ACEPTAR)->setLabel('Actualizar');
                $this->view->activo = 1;
            }
        } catch (Exception $ex) {
            //var_dump($ex);
            $this->addMessageWarning($ex->__toString());
        }//echo'<br/><br/>';
        $this->view->form = $form;
        $this->view->direccion = $direccion;
        //$this->renderScript('nuevo.phtml');
    }

}
