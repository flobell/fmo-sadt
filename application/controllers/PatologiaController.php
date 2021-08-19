<?php

/**
 * Controlador para los períodos
 *
 * @author Pedro Flores <fmo16554@ferrominera.gob.ve>
 */
class PatologiaController extends Fmo_Controller_Action_Abstract {


    public function agregarAction() {
        $style = '#menu, #informacion { display: none; },  #contenido { top: 0%; } ';
        $this->view->headStyle()->appendStyle($style);
        $urlVolver = "default/direccion/ver";
        $formulario = new Application_Form_Patologia();

        try {
            $usuario_id = $this->getParam('id');
            $this->view->usuario_id = $usuario_id;
            $paginaProveniente = $this->getParam('p');
            $this->view->paginaProveniente = $paginaProveniente;
            $cedulaUsuario = $this->getParam('c');
            $usuario = Fmo_Model_Personal::findOneByCedula($cedulaUsuario);
            $usuarioSesion = Fmo_Model_Seguridad::getUsuarioSesion();
            $patologiasUsuario = Application_Model_Patologia::buscar($usuario);
            

            if ($this->getRequest()->isPost()) {
                $post = $this->getRequest()->getPost();
                $formulario->setDefaults($post);

                if ($this->getParam(Application_Form_Patologia::E_ACEPTAR)) {
                    if($formulario->isValid($post)){
                        if($formulario->guardar($usuario,$usuarioSesion)) {
                            $this->addMessageSuccessful("Se guardó exitosamente las Patologias del Trabajador");

                            $administracionC = "default/administracion/ver/id/$usuario_id";
                            if($paginaProveniente == 1) $this->redirect($urlVolver);
                            else $this->redirect($administracionC);
                        } 
                    }
                }
                

            } else {
                $formulario->valoresPorDefecto($patologiasUsuario);
            }

        }
        catch (Exception $ex) {
            $this->addMessageWarning('No pudo ser guardado con exito');
            $this->addMessageWarning($ex->__toString());
        }
        $this->view->centro = $usuario->organigrama;
        $this->view->usuario = $usuario;
        $this->view->formulario = $formulario;

    }

    public function agregarmedicamentoAction() {
        $this->view->jQueryX()->enable();
        $this->view->select2()->enable();
        $style = '#menu, #informacion { display: none; },  #contenido { top: 0%; } ';
        $this->view->headStyle()->appendStyle($style);
        $urlVolver = "default/direccion/ver";
        $formulario = new Application_Form_Medicamento();

        try {
            $usuario_id = $this->getParam('id');
            $this->view->usuario_id = $usuario_id;
            $paginaProveniente = $this->getParam('p');
            $this->view->paginaProveniente = $paginaProveniente;
            $cedulaUsuario = $this->getParam('c');
            $usuario = Fmo_Model_Personal::findOneByCedula($cedulaUsuario);
            //Zend_Debug::dd($usuario);
            $usuarioSesion = Fmo_Model_Seguridad::getUsuarioSesion();
            //$medicamentos = Application_Model_Medicamentos::buscar($usuario);


            // if(count(Application_Model_Patologia::listarParesExistente($usuario->cedula)) < 1){
            //     $this->addMessageWarning("No se puede agregar medicamentos:<br>El trabajador no posee patologías asociadas, debe tener al menos 1 patología");
            //     $administracionC = "default/administracion/ver/id/$usuario_id";
            //     if($paginaProveniente == 1) $this->redirect($urlVolver);
            //     else $this->redirect($administracionC);
            // }

            //$formulario->valoresPorDefecto($usuario);

            if ($this->getRequest()->isPost()) {
                $post = $this->getRequest()->getPost();
                // Zend_Debug::dd($post);
                // $formulario->setDefaults($post);

                if ($this->getParam(Application_Form_Medicamento::E_ACEPTAR)) {
                    if ($formulario->isValid($post) && $formulario->guardar($usuario,$usuarioSesion)) {
                        $this->addMessageSuccessful("Se guardó exitosamente el Medicamento al Trabajador");

                        $administracionC = "default/administracion/ver/id/$usuario_id";
                        if($paginaProveniente == 1) $this->redirect($urlVolver);
                        else $this->redirect($administracionC);

                    }
                }

            } 
            // else {
            //     $formulario->valoresPorDefecto($usuario);
            // }

        }
        catch (Exception $ex) {
            $this->addMessageWarning('No pudo ser guardado con exito');
            $this->addMessageWarning($ex->__toString());
        }
        $this->view->centro = $usuario->organigrama;
        $this->view->usuario = $usuario;
        $this->view->formulario = $formulario;
    }

    public function eliminarmedicamentoAction() {
        $id = $this->getParam('id', '');
        $trabajadorMedicamento = Application_Model_DbTable_TrabajadorMedicamento::findOneById($id);

        try {

            if (!$trabajadorMedicamento) {
                throw new Exception("No existe el medicamento asociado de código '{$id}'");
            }

            if($trabajadorMedicamento->informe_adjunto_id){
                $adjuntoInforme = Application_Model_DbTable_AdjuntoInforme::findOneById($trabajadorMedicamento->informe_adjunto_id);
                $ruta = $adjuntoInforme->ruta_almacenamiento;
                $adjuntoInforme->delete();
                unlink($ruta);
            }

            $trabajadorMedicamento->delete();
           
            $this->addMessageSuccessful("Medicamento eliminado!");

            $this->backUrlSession(); 
        } catch (Exception $ex) {
            echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
            $this->addMessageException($ex);
            $this->backUrlSession();            
        }
    }

}