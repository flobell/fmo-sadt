<?php

/**
 * Controlador para las tarifas
 *
 * @author felixpga
 */
class AdministracionController extends Fmo_Controller_Action_Abstract {

    public function nuevoAction() {
        $this->view->jQueryX()->enable();
        $this->view->select2()->enable();
        $style = '#menu ,  #informacion { display: none; },  #contenido { top: 0%; } ';
        $this->view->headStyle()->appendStyle($style);
        $urlVolver = "default/administracion/buscar";
        try {
            $usuario = $this->getParam('id');
            //$this->view->usuario = $us = Fmo_Model_Personal::findOneByCedula($usuario);
            $this->view->usuario = $us = Application_Form_Direccion::buscarTrabajador($usuario);
            //Zend_Debug::dd($us);
            if ($us) {
                $this->view->centro = $us->organigrama;
                $form = new Application_Form_Direccion();
                $direccion = Application_Model_Direccion::buscar($usuario);
                if ($direccion) {
                    $this->view->tipo = $direccion->tipo;
                    //var_dump($direccion->archivo);
                    if ($direccion->archivo != '') {
                        $this->view->anterior = $direccion->archivo;
                        $form->getElement(Application_Form_Direccion::E_FILE)->setRequired(false)->setLabel('Actualizar Constancia de Residencia / Rif / Factura de Servicio');
                    }
                }
                if ($this->getRequest()->isPost()) {
                    $post = $this->getRequest()->getPost();
                    //var_dump($post);
                    $form->setDefaults($post);
                    $this->view->tipo = $this->getParam(Application_Form_Direccion::E_TIPO);
                    if ($this->getParam(Application_Form_Direccion::E_ACEPTAR) && $form->isValid($post) && $form->guardar($usuario)) {
                        $this->addMessageSuccessful("Se guardó exitosamente la Dirección del Trabajador");
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
                } else {
                    $form->valoresPorDefecto($direccion);
                }
                $this->view->form = $form;
            }
            //Zend_Debug::dd($us);
            //var_dump($us);
        } catch (Exception $ex) {
            //var_dump($ex);
            $this->addMessageWarning('No pudo ser guardado con exito');
            $this->addMessageWarning($ex->__toString());
        }
        //$this->renderScript('nuevo.phtml');
    }

    /**
     * Acción para listar los servicios
     */
    public function verAction() {
        $style = '#menu ,  #informacion { display: none; },  #contenido { top: 0%; } ';
        $this->view->headStyle()->appendStyle($style);
        $urlVolver = "default/administracion/nuevo";
        try {
            $usuario = $this->getParam('id');
            //$this->view->usuario = $us = Fmo_Model_Personal::findOneByCedula($usuario);
            $this->view->usuario = $us = Application_Form_Direccion::buscarTrabajador($usuario);
            $this->view->centro = $us->organigrama;
            // $patologiasUsuario = Application_Model_Patologia::buscar($us);
            // $this->view->patologias = $patologiasUsuario;
            $medicamentosUsuario = Application_Model_Medicamento::buscar($us);
            $this->view->medicamentos = $medicamentosUsuario;
            //Zend_Debug::dd($us);
            $form = new Application_Form_Direccion();
            $direccion = Application_Model_Direccion::buscar($us->cedula);
            if ($this->getParam(Application_Form_Direccion::E_ACEPTAR)) {
                $this->redirect($urlVolver . '/id/' . $us->cedula);
            }
            $form->getElement(Application_Form_Direccion::E_ACEPTAR)->setLabel('Actualizar');

            $this->view->form = $form;
            $this->view->direccion = $direccion;
            //$this->renderScript('nuevo.phtml');
    
            if ($this->getRequest()->isPost()) {
                $post = $this->getRequest()->getPost();
                if($this->getParam('eliminar_m')){
                    //Zend_Debug::dd($post);
                    if(Application_Model_Medicamento::eliminarMedicamentoTrabajador($post['cedula'],$post['medicamento'])){
                        $this->addMessageSuccessful("Medicamento eliminado!");
                    }
                    $urlVolverAqui = "default/administracion/ver/id/$usuario";
                    $this->redirect($urlVolverAqui);
                }
            }

        } catch (Exception $ex) {
            //var_dump($ex);
            $this->addMessageWarning($ex->__toString());
        }//echo'<br/><br/>';

    }

    /**
     * Acción para listar los servicios
     */
    public function buscarAction() {
        try {
            $style = '#menu ,  #informacion { display: none; },  #contenido { top: 0%; } ';
            $this->view->headStyle()->appendStyle($style);
            $form = new Application_Form_Trabajador();
            if ($this->getRequest()->isPost()) {
                $post = $this->getRequest()->getPost();
                if ($this->getParam(Application_Form_Trabajador::E_BUSCAR)) {
                    if ($form->isValid($post)) {
                        //$form = new Application_Form_Direccion();
                        $this->view->datoFicha = $id = $this->getParam(Application_Form_Trabajador::E_FICHA);
                        $trabajador = Application_Form_Direccion::buscarTrabajador($id);
                        //$trabajador = Fmo_Model_Persona::findOneByFicha($id);
                        //Zend_Debug::dd(count($trabajador));
                        if (count($trabajador)) {
                            $usuario = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::CEDULA};
                            //$usuario = 9952545;
                            //echo $usuario;
                            $this->redirect("{$this->getRequest()->getModuleName()}/{$this->getRequest()->getControllerName()}/ver/id/{$id}");
                            // $permiso = Application_Model_Personal::getAllPermisosByCedula($usuario, $trabajador->cedula);
                            // if (count($permiso) > 0 || ($trabajador->cedula == $usuario)) {
                            //     $this->redirect("{$this->getRequest()->getModuleName()}/{$this->getRequest()->getControllerName()}/ver/id/{$id}");
                            // } else {
                            //     $this->addMessageWarning('Disculpe, Ud. no está autorizado para ver la información de este trabajador.');
                            //     //$puede = '<b>Disculpe, Usted NO está autorizado para ver la información de este trabajador.</b>';
                            // }
                        }else{
                            $this->addMessageWarning('La Ficha/cedula introducida no existe entre los trabajadores Activos o Jubilados, revise los parametros de busqueda.');
                        }
                    }
                }
                if ($this->getParam(Application_Form_Trabajador::E_VOLVER)) {

                    Fmo_Model_Seguridad::clearIdentity();
                    $this->redirect('/');
                }
            }
            //  $datos = Application_Model_Direccion::listarTrabajadores();
            //var_dump($datos);exit;
            //  $this->view->paginacion = $this->paginator($datos, 20);

            $this->view->form = $form;
        } catch (Exception $ex) {
            //var_dump($ex);
            $this->addMessageWarning($ex->__toString());
        }
        //$this->renderScript('nuevo.phtml');
    }

}
