<?php

/**
 * Controlador para las referencias
 *
 * @author eucarisg
 */
class ReferenciaController extends Fmo_Controller_Action_Abstract {

    public function crearAction() {

        try {

            $usuario = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::CEDULA};
            $us = Fmo_Model_Seguridad::getUsuarioSesion();
            if ($this->getParam(Application_Form_Direccion::E_VOLVER)) {
                $this->redirect($urlVolver);
            }
            $this->view->usuario = Fmo_Model_Seguridad::getUsuarioSesion();
            $form = new Application_Form_Referencia();
          
            $msj = $this->getParam('param');
            if (isset($msj)) {

                $this->addMessageSuccessful("Se eliminó la referencia correctamente");
            }

            $new = $this->getParam('new');

            if (isset($new)) {

                $this->addMessageSuccessful("Se agregó la referencia correctamente");
            }

            $this->view->referencias = $referencias = Application_Model_Referencia::getReferencias();
 //var_dump($referencias);
            if ($this->getRequest()->isPost()) {

                $post = $this->getRequest()->getPost();
                $form->setDefaults($post);

                if ($this->getParam(Application_Form_Referencia::E_GUARDAR)) {
                    $ereferencia = $this->getParam(Application_Form_Referencia::E_REFERENCIA);
                    if (!empty($ereferencia)) {

                        if ($form->guardar()) {
                            $this->redirect("default/referencia/crear/new/1");
                        } else {
                            $this->addMessageError('Ha ocurrido un error. No se ha agregado la referencia');
                        }
                    } else {
                        $this->addMessageError('Debe llenar todos los datos para crear la referencia');
                    }
                }
            }
        } catch (Exception $ex) {
            
        }

        $this->view->form = $form;

    }
    
  public function asignarAction() {
         try {
            $usuario = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::CEDULA};
            $us = Fmo_Model_Seguridad::getUsuarioSesion();
            $this->view->centro = $us->organigrama;
            $this->view->usuario = Fmo_Model_Seguridad::getUsuarioSesion();
            $form = new Application_Form_Asignar();
         
            
            if ($this->getRequest()->isPost()) {

                $post = $this->getRequest()->getPost();
                $form->setDefaults($post);

                if ($this->getParam(Application_Form_Asignar::E_GUARDAR)) {
                    $ereferencia = $this->getParam(Application_Form_Asignar::E_REFERENCIA);
                    $eparroquia = $this->getParam(Application_Form_Asignar::E_REFERENCIA);
                    if ((!empty($ereferencia))&&(!empty($eparroquia))) {

                        if ($form->guardar()) {
                            $this->addMessageSuccessful("Se Asignó la referencia correctamente");
                        } else {
                            $this->addMessageError('Ha ocurrido un error. No se ha agregado la referencia');
                        }
                    } else {
                        $this->addMessageError('Debe llenar todos los datos para crear la referencia');
                    }
                }
            }
            
            $form->getElement(Application_Form_Asignar::E_GUARDAR)->setLabel('Guardar');
        } catch (Exception $ex) {
            //var_dump($ex);
            $this->addMessageWarning($ex->__toString());
        }
        $this->view->form = $form;

    }    

    public function eliminarAction() {

        $this->view->headScript()
                ->appendFile($this->view->baseUrl('../zendlib/public/js/jquery.min.js'))
                ->appendFile($this->view->baseUrl('../zendlib/public/js/bootstrap.min.js'));
        $this->view->headLink()
                ->setStylesheet($this->view->baseUrl('../zendlib/public/basic_table/css/custom.min.css'))
                ->setStylesheet($this->view->baseUrl('../zendlib/public/basic_table/css/buttons.bootstrap.min.css'))
                ->setStylesheet($this->view->baseUrl('../zendlib/public/basic_table/css/dataTables.bootstrap.min.css'))
                ->setStylesheet($this->view->baseUrl('../zendlib/public/basic_table/css/fixedHeader.bootstrap.min.css'))
                ->setStylesheet($this->view->baseUrl('../zendlib/public/basic_table/css/responsive.bootstrap.min.css'))
                ->setStylesheet($this->view->baseUrl('../zendlib/public/basic_table/css/scroller.bootstrap.min.css'))
                ->setStylesheet($this->view->baseUrl('../zendlib/public/bootstrap-3.3.7-dist/css/bootstrap.css'));


        try {


            $this->view->referencias = $referencias = Application_Model_Referencia::getRutasHorario($this->getParam('id'));

            $this->view->rutas_desc = $referencias[0]->descripcion;


            if ($this->getRequest()->isPost()) {

                if ($this->getParam('btnEliminar') == 'Si') {

                    $result = Application_Model_Referencia::eliminarReferencia($this->getParam('id'));

                    $this->redirect("default/referencia/crear/param/1");
                } elseif ($this->getParam('btnEliminar') == 'No') {

                    $this->redirect("default/referencia/crear");
                }
            }
        } catch (Exception $ex) {
            $this->addMessageException($ex);
        }
    }

}
