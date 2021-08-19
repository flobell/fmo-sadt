<?php

/**
 * Controlador para las tarifas
 *
 * @author felixpga
 */
class ZonaController extends Fmo_Controller_Action_Abstract {

    /**
     * Acción para listar los servicios
     */
    public function confirmarAction() {
        //$urlVolver = $this->getRequest()->getModuleName() . "/" . $this->getRequest()->getControllerName() . "/listado";
    }

    /**
     * Acción para listar los servicios
     */
    public function listarAction() {
        //$urlVolver = "default/direccion/nuevo";
        try {
            
            $usuario = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::CEDULA};
            $us = Fmo_Model_Seguridad::getUsuarioSesion();
            $this->view->centro = $us->organigrama;
            $this->view->usuario = Fmo_Model_Seguridad::getUsuarioSesion();
            $form = new Application_Form_Direccion();
            if ($this->getRequest()->isPost()) {
                $form->getElement(Application_Form_Direccion::E_ESTADO)->setValue($this->getParam(Application_Form_Direccion::E_ESTADO));
            }
            $listado = Application_Model_Zona::listarSub($this->getParam(Application_Form_Direccion::E_ESTADO, 2));
            //var_dump($this->getAllParams());
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

    /**
     * Acción para listar los servicios
     */
    public function editarAction() {
        $urlVolver = "default/zona/listar";
        try {
            if ($this->getParam(Application_Form_Zona::E_VOLVER)) {
                $this->redirect($urlVolver);
            }
            //var_dump($this->getAllParams());
            $form = new Application_Form_Zona();
            $id = $this->getParam('id');
            $zona = Application_Model_Zona::getZona($id);

            $this->view->id = $id;
            $listado = Application_Model_Zona::listarSub($id);
            //var_dump($zona);
            $form->valoresPorDefecto($id);
            //$form->getElement(Application_Form_Zona::E_PADRE)->setAttrib('disabled', 'disabled');
            if ($this->getRequest()->isPost()) {
                $post = $this->getRequest()->getPost();
                //$form->setDefaults($post);
                if ($this->getParam(Application_Form_Zona::E_ACEPTAR)) {
                    if ($form->isValid($post) && $form->guardarCambios($id)) {
                        $this->addMessageSuccessful("Se guardó exitosamente el cambio en la Zona");
                        $this->redirect($urlVolver);
                    }
                }
            }
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

    /**
     * Acción para listar los servicios
     */
    public function nuevoAction() {
        $urlVolver = "default/zona/listar";
        try {
            if ($this->getParam(Application_Form_Zona::E_VOLVER)) {
                $this->redirect($urlVolver);
            }
            //var_dump($this->getAllParams());
            $form = new Application_Form_Zona();
            $id = $this->getParam('id');
            $this->view->id = $id;
            $form->valoresPorDefecto($id, 'nuevo');
            $form->getElement(Application_Form_Zona::E_PADRE)->setAttrib('disabled', 'disabled');
            if ($this->getRequest()->isPost()) {
                $post = $this->getRequest()->getPost();
                //$form->setDefaults($post);
                if ($this->getParam(Application_Form_Zona::E_ACEPTAR)) {
                    if ($form->isValid($post) && $form->guardar($id)) {
                        $this->addMessageSuccessful("Se guardó exitosamente el nuevo registro");
                        $this->redirect($urlVolver);
                    }
                }
            }
        } catch (Exception $ex) {
            //var_dump($ex);
            $this->addMessageWarning($ex->__toString());
        }
        $this->view->form = $form;
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
