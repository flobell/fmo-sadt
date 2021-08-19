<?php

/**
 * Controlador para las tarifas
 *
 * @author felixpga
 */
class DireccionController extends Fmo_Controller_Action_Abstract {

    function bootstrap() {
        //agregar librerias 
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/js/number/jquery.number.min.js'));
        //Se habilitan las librerias de JQuery
        $this->view->jQueryX()->enable();
        $this->view->bootstrap()->enable();
        $this->view->bootstrap()->jsEnable();
        $this->view->select2()->enable();
    }

    public function nuevoAction() {
        $this->view->jQueryX()->enable();
        $this->view->select2()->enable();
        $this->bootstrap();
        $style = '#menu, #informacion { display: none; },  #contenido { top: 0%; } ';
        $this->view->headStyle()->appendStyle($style);
        $urlVolver = "default/direccion/ver";
        try {
            $usuario = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::CEDULA};
            $us = Fmo_Model_Seguridad::getUsuarioSesion();
            if ($this->getParam(Application_Form_Direccion::E_VOLVER)) {
                $this->redirect($urlVolver);
            }
            $this->view->centro = $us->organigrama;
            $this->view->usuario = $us;
            $form = new Application_Form_Direccion();
            $direccion = Application_Model_Direccion::buscar($usuario);
            if ($direccion) {
                $this->view->tipo = $direccion->tipo;
                //var_dump($direccion);
                if ($direccion->archivo != '') {
                    $this->view->anterior = $direccion->archivo;
                    $form->getElement(Application_Form_Direccion::E_FILE)->setRequired(false)->setLabel('Actualizar Constancia de Residencia / Rif / Factura de Servicio');
                }
            }
            //var_dump($direccion->archivo);
            if ($this->getRequest()->isPost()) {
                $post = $this->getRequest()->getPost();
                $form->setDefaults($post);
                $this->view->tipo = $this->getParam(Application_Form_Direccion::E_TIPO);
                if ($this->getParam(Application_Form_Direccion::E_ACEPTAR)) {
                    if ($form->isValid($post) && $form->guardar($usuario)) {
                        $this->addMessageSuccessful("Se guardó exitosamente la Dirección del Trabajador");
                        //$form->validarTipo($usuario);
                        if ($form->proceso($this->getRequest()->getPost(), $usuario)) {
                            $this->addMessageSuccessful("Se adjuntó exitosamente el Archivo");
                        } else {
                            if (!$direccion) {
                                $this->addMessageWarning('No ha sido Adjuntado la constancia');
                            } else {
                                if ($direccion->archivo == '') {
                                    $this->addMessageWarning('No ha sido Adjuntado la constancia');
                                } else {
                                    $this->addMessageSuccessful("El archivo no fue modificado");
                                }
                            }
                        }
                        $this->redirect($urlVolver);
                    }
                }
            } else {
                $form->valoresPorDefecto($direccion);
            }
        } catch (Exception $ex) {
            //var_dump($ex);
            $this->addMessageWarning('No pudo ser guardado con exito');
            $this->addMessageWarning($ex->__toString());
        }
        $this->view->form = $form;
        //$this->renderScript('nuevo.phtml');
    }

    /**
     * Acción para ver Direccion
     */
    public function verAction() {


        $style = '#menu, #informacion { display: none; },  #contenido { top: 0%; } ';
        $this->view->headStyle()->appendStyle($style);
        $urlVolver = "default/direccion/nuevo";
        if ($this->getParam(Application_Form_Trabajador::E_VOLVER)) {
            Fmo_Model_Seguridad::clearIdentity();
            $this->redirect('/');
        }
        try {
            $usuario = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::CEDULA};
            $us = Fmo_Model_Seguridad::getUsuarioSesion();
            // $patologiasUsuario = Application_Model_Patologia::buscar($us);
            // $this->view->patologias = $patologiasUsuario;
            $medicamentosUsuario = Application_Model_Medicamento::buscar($us);
            $this->view->medicamentos = $medicamentosUsuario;
            $this->view->centro = $us->organigrama;
            $this->view->usuario = Fmo_Model_Seguridad::getUsuarioSesion();
            $form = new Application_Form_Direccion();
            $direccion = Application_Model_Direccion::buscar($usuario);
            if ($this->getParam(Application_Form_Direccion::E_ACEPTAR)) {
                $this->redirect($urlVolver);
            }
            /* $activo = 1;//Application_Model_Periodo::listar('1');
              if (count($activo) > 0) { */
            $form->getElement(Application_Form_Direccion::E_ACEPTAR)->setLabel('Actualizar');
            $this->view->activo = 1;
            //}
            $this->view->form = $form;
            $this->view->direccion = $direccion;

            if ($this->getRequest()->isPost()) {
                $post = $this->getRequest()->getPost();
                if($this->getParam('eliminar_m')){
                    //Zend_Debug::dd($post);
                    if(Application_Model_Medicamento::eliminarMedicamentoTrabajador($post['cedula'],$post['medicamento'])){
                        $this->addMessageSuccessful("Medicamento eliminado!");
                    }
                    $urlVolverAqui = "default/direccion/ver";
                    $this->redirect($urlVolverAqui);
                }
            }
        } catch (Exception $ex) {
            //var_dump($ex);
            $this->addMessageWarning($ex->__toString());
        }//echo'<br/><br/>';

        
        //$this->renderScript('nuevo.phtml');
    }

}
