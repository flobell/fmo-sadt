<?php

/*
 * Copyright (C) 2017 Stalin Sanchez <stalins@ferrominera.com>
 */

/**
 * Controlador para los períodos
 *
 * @author Felixpga
 */
class PeriodoController extends Fmo_Controller_Action_Abstract {

    /**
     * Acción para listar los períodos
     */
    public function listadoAction() {

        $tblPeriodo = new Application_Model_DbTable_Periodo();
        $periodos = $this->paginator(Application_Model_Periodo::listar());
        $this->view->periodos = $periodos;
    }

    /**
     * Acción para crear un nuevo Juguete
     */
    public function nuevoAction() {
        $crear = true;
        $formulario = new Application_Form_Periodo(/* $crear */);
        $urlVolver = "default/periodo/listado";
        $this->view->formulario = $formulario;

        if ($this->getParam(Application_Form_Periodo::E_VOLVER)) {
            $this->redirect($urlVolver);
        }

        //Se obtiene la petición
        $request = $this->getRequest();

        try {
            $formulario->removeElement(Application_Form_Periodo::E_ESTADO);
            //Se verifica si se ha recibido una petición via POST
            if ($request->isPost()) {
                $post = $request->getPost();
                if ($formulario->isValid($request->getPost()) && $formulario->crear($post)) {
                    $this->addMessageSuccessful("Período creado exitosamente.");
                    $this->redirect($urlVolver);
                }

                $formulario->setDefaults($this->getAllParams());
            }
        } catch (Exception $e) {
            switch ($e->getCode()) {
                case 1: {
                        $this->addMessageError('Error: ' . $e->getMessage());
                    }break;

                case 23505: {
                        $this->addMessageError("El Año {$post[Application_Form_Periodo::ID_PERIODO]} ya posee un período.");
                    }break;

                default: {
                        $this->addMessageError('Error: ' . $e->getMessage());
                        $this->redirect('/');
                    }break;
            }
        }
    }

    /**
     * Acción para crear
     */
    public function editarAction() {
        $id = $this->getParam('id');
        $urlVolver = "default/periodo/listado";
        
        if ($this->getParam(Application_Form_Periodo::E_VOLVER)) {
            $this->redirect($urlVolver);
        }
        try {
            $form = new Application_Form_Periodo(/* 'Editar' */);
            $form->getElement(Application_Form_Periodo::E_CREAR)->setLabel('Actualizar');
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->getRequest()->getPost()) && $form->editar($this->getRequest()->getPost(), $id)) {
                    $this->addMessageSuccessful("Se guardó exitosamente el registro");
                    $this->redirect($urlVolver);
                } else {
                    $form->setDefaults($this->getAllParams());
                }
            }
            $form->cargarDatos($id);
        } catch (Exception $e) {
            switch ($e->getCode()) {
                case 1: {
                        $this->addMessageWarning($e->getMessage());
                    }break;
                default: {
                        $this->addMessageWarning($e->getMessage());
                        //$this->redirect('/');
                    }break;
            }
        }
    }

    /**
     * Acción para eliminar un Juguete
     */
    public function eliminarAction() {
        $pPage = $this->getParam('page');
        $urlVolver = "default/periodo/listado";
        $pCodigo = $this->getParam('id');
        $pConfirmar = $this->getParam('confirmar', 'N');

        try {
            $periodo = Application_Model_DbTable_Periodo::findOneById($pCodigo);
            if ($pConfirmar == 'S') {
                $mensaje = "Se eliminó exitosamente el registro";
                $periodo->delete();
                $this->addMessageSuccessful($mensaje);
                $this->redirect($urlVolver);
            }
        } catch (Exception $ex) {
            if ($ex->getCode() == 23503) {
                $this->addMessageWarning('No puede eliminar este resgistro porque existen registros asociados');
            } else {
                $this->addMessageException($ex);
                $this->redirect($urlVolver);
            }
        }
        $this->view->periodo = $periodo;
        $this->view->page = $pPage;
    }

}
