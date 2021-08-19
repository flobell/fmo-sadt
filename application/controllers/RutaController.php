<?php

/**
 * Controlador para las tarifas
 *
 * @author enocc
 */
class RutaController extends Fmo_Controller_Action_Abstract {

    public function nuevoAction() {
        $urlVolver = "default/ruta/ver";
        try {
            $usuario = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::CEDULA};
            $us = Fmo_Model_Seguridad::getUsuarioSesion();
            if ($this->getParam(Application_Form_Direccion::E_VOLVER)) {
                $this->redirect($urlVolver);
            }
            $this->view->usuario = Fmo_Model_Seguridad::getUsuarioSesion();
            $form = new Application_Form_Ruta();



            if ($this->getParam(Application_Form_Ruta::E_LOCALIDAD))
                $form->ruta($this->getParam(Application_Form_Ruta::E_LOCALIDAD));

            if ($this->getRequest()->isPost()) {
                $post = $this->getRequest()->getPost();
                $this->view->id_horario = $id_horario = ($this->getParam(Application_Form_Ruta::E_HORARIO));
                $this->view->id_ruta = $id_ruta = ($this->getParam(Application_Form_Ruta::E_RUTA));

                $form->setDefaults($post, $id_ruta);
                $municipio = $this->getParam(Application_Form_Ruta::E_MUNICIPIO);
                if (!empty($municipio)) {

                    $this->view->id_municipio = $id_municipio = ($this->getParam(Application_Form_Ruta::E_MUNICIPIO));

                    $parroquia = $this->getParam(Application_Form_Ruta::E_PARROQUIA);
                    if (!empty($parroquia)) {
                        $this->view->id_parroquia = $id_parroquia = ($this->getParam(Application_Form_Ruta::E_PARROQUIA));
                    }
                }

                $this->view->tipo = $this->getParam(Application_Form_Direccion::E_TIPO);
                if ($this->getParam(Application_Form_Direccion::E_ACEPTAR)) {
                    $a = $form->isValid($post);
                    if ($form->guardar()) {
                        $this->addMessageSuccessful("Se agregó la Ruta seleccionada");
                    } else {
                        $this->addMessageError('Ha ocurrido un error');
                    }
                }

                $id_horario = (!empty($id_horario)) ? $id_horario : null;
                $id_ruta = (!empty($id_ruta)) ? $id_ruta : null;
                $id_municipio = (!empty($id_municipio)) ? $id_municipio : null;
                $id_parroquia = (!empty($id_parroquia)) ? $id_parroquia : null;
                if($id_horario !='' && $id_ruta!= ''){
                    $this->view->recorridos = $recorridos = Application_Model_Recorrido::getRecorridoRutas($id_horario, $id_ruta);
                }
                
                //var_dump($recorridos);
            } else {
                //$form->valoresPorDefecto($direccion);
            }
        } catch (Exception $ex) {
            //var_dump($ex);
            $this->addMessageWarning('No pudo ser guardado con exito');
            $this->addMessageWarning($ex->__toString());
        }
        $this->view->form = $form;
        //$this->renderScript('nuevo.phtml');
    }

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
                $form->setDefaults($post);

                $post = $this->getRequest()->getPost();
                //$form->recargar($post);
                $gerencia = null;
                $tpno = null;


                if ($this->getParam(Application_Form_Ruta::E_GERENCIA))
                    $gerencia = $this->getParam(Application_Form_Ruta::E_GERENCIA);

                if ($this->getParam(Application_Form_Ruta::E_TPNO))
                    $tpno = $this->getParam(Application_Form_Ruta::E_TPNO);

                if ($this->getParam(Application_Form_Ruta::E_LOCALIDAD))
                    $form->ruta($this->getParam(Application_Form_Ruta::E_LOCALIDAD));

                if ($this->getParam(Application_Form_Ruta::E_HORARIO) != '') {


                    if ($this->getParam(Application_Form_Ruta::E_SECTOR) != '') {

                        $listado = Application_Model_Direccion::listarTrabajadoresRutas($this->getParam(Application_Form_Ruta::E_SECTOR), null, null, $gerencia, $tpno);
                    } else {
                        if ($this->getParam(Application_Form_Ruta::E_PARROQUIA) != '') {
                            $listado = Application_Model_Direccion::listarTrabajadoresRutas($this->getParam(Application_Form_Ruta::E_PARROQUIA), $this->getParam(Application_Form_Ruta::E_HORARIO), null, $gerencia, $tpno);
                        } else {
                            if ($this->getParam(Application_Form_Ruta::E_CIUDAD) != '') {
                                $listado = Application_Model_Direccion::listarTrabajadoresRutas($this->getParam(Application_Form_Ruta::E_CIUDAD), null, null, $gerencia, $tpno);
                            } else {
                                if ($this->getParam(Application_Form_Ruta::E_MUNICIPIO) != '') {
                                    $listado = Application_Model_Direccion::listarTrabajadoresRutas($this->getParam(Application_Form_Ruta::E_MUNICIPIO), $this->getParam(Application_Form_Ruta::E_HORARIO), null, $gerencia, $tpno);
                                } else {
                                    if ($this->getParam(Application_Form_Ruta::E_ESTADO) != '') {
                                        $listado = Application_Model_Direccion::listarTrabajadoresRutas($this->getParam(Application_Form_Ruta::E_ESTADO), $this->getParam(Application_Form_Ruta::E_HORARIO), null, $gerencia, $tpno);
                                    } else {
                                        $listado = Application_Model_Direccion::listarTrabajadoresRutas(null, $this->getParam(Application_Form_Ruta::E_HORARIO), null, $gerencia, $tpno);
                                    }
                                }
                            }
                        }
                    }

                    if (empty($listado)) {
                        $this->view->mensaje = "No se han encontrado registros.";
                    }
                }
//                else{
//                $listado = Application_Model_Direccion::listarTrabajadoresRutas();
//            }
            }
            $form->getElement(Application_Form_Ruta::E_ACEPTAR)->setLabel('Buscar');
        } catch (Exception $ex) {
            //var_dump($ex);
            $this->addMessageWarning($ex->__toString());
        }
        $this->view->form = $form;

        $this->view->listado = $listado;
        //$this->renderScript('nuevo.phtml');
    }

    public function crearAction() {

        try {

            $usuario = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::CEDULA};
            $us = Fmo_Model_Seguridad::getUsuarioSesion();
            if ($this->getParam(Application_Form_Direccion::E_VOLVER)) {
                $this->redirect($urlVolver);
            }
            $this->view->usuario = Fmo_Model_Seguridad::getUsuarioSesion();
            $form = new Application_Form_Crear();



            //var_dump($rutas);
            $msj = $this->getParam('param');
            if (isset($msj)) {

                $this->addMessageSuccessful("Se eliminó la ruta correctamente");
            }

            $new = $this->getParam('new');

            if (isset($new)) {

                $this->addMessageSuccessful("Se agregó la ruta correctamente");
            }

            $this->view->rutas = $rutas = Application_Model_Ruta::getRutasHorario();

            if ($this->getRequest()->isPost()) {

                $post = $this->getRequest()->getPost();
                $form->setDefaults($post);

                if ($this->getParam(Application_Form_CREAR::E_GUARDAR)) {
                    $eruta = $this->getParam(Application_Form_Ruta::E_RUTA);
                    $ehorario = $this->getParam(Application_Form_Ruta::E_HORARIO);
                    if (!empty($eruta) && !empty($ehorario)) {

                        if ($form->guardar()) {
                            $this->redirect("default/ruta/crear/new/1");
                        } else {
                            $this->addMessageError('Ha ocurrido un error. No se ha agregado la ruta');
                        }
                    } else {
                        $this->addMessageError('Debe llenar todos los datos para crear la ruta');
                    }
                }
            }
        } catch (Exception $ex) {
            
        }

        $this->view->form = $form;

        //<form id="CrearForm" name="CrearForm" method="post" action="/~enocc/sadt/default/ruta/crear">
        //<form enctype="application/x-www-form-urlencoded" action="/~enocc/sadt/default/ruta/crear" method="post"><table class="general" summary="Sistema" style="width:50%">
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


            $this->view->rutas = $rutas = Application_Model_Ruta::getRutasHorario($this->getParam('id'));

            $this->view->rutas_desc = $rutas[0]->descripcion;


            if ($this->getRequest()->isPost()) {

                if ($this->getParam('btnEliminar') == 'Si') {

                    $result = Application_Model_Ruta::eliminarRuta($this->getParam('id'));

                    $this->redirect("default/ruta/crear/param/1");
                } elseif ($this->getParam('btnEliminar') == 'No') {

                    $this->redirect("default/ruta/crear");
                }
            }
        } catch (Exception $ex) {
            $this->addMessageException($ex);
        }
    }

    public function editrecorridoAction() {

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


            $this->view->recorridos = $recorridos = Application_Model_Recorrido::getRecorrido($this->getParam('id'));

            $this->view->ruta_desc = $recorridos[0]->des_ruta;

            $cborden = $this->getParam('cbOrden');
            if (!empty($cborden)) {
                $this->view->Vorden = $this->getParam('cbOrden');
                echo $this->getParam('cbOrden');
                ;
            }

            $msj = $this->getParam('param');
            if (isset($msj)) {

                $this->addMessageSuccessful("Se eliminó el recorrido correctamente");
            }

            if ($this->getRequest()->isPost()) {

                if ($this->getParam('btnEliminar') == 'Si') {

                    $result = Application_Model_Ruta::eliminarRuta($this->getParam('id'));

                    $this->redirect("default/ruta/crear/param/1");
                } elseif ($this->getParam('btnEliminar') == 'No') {

                    $this->redirect("default/ruta/crear");
                }
            }
        } catch (Exception $ex) {
            $this->addMessageException($ex);
        }
    }

    public function editarordenAction() {

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

            $this->view->orden = $recorrido = Application_Model_Recorrido::getRecorrido($this->getParam('id'), $this->getParam('id-orden'));


            $this->view->selectorden = $orden = array($recorrido[0]->orden) + Application_Model_Recorrido::getOrden($recorrido[0]->id_ruta, $recorrido[0]->orden);
            //var_dump($this->getParam('cboOrden'));
            $cborden = $this->getParam('cboOrden');
            if (!empty($cborden)) {
                $this->view->vOrden = $cborden;
                $orden_nuevo = $orden[$cborden];
            } else {
                $orden_nuevo = $this->getParam('id-orden');
            }

            if ($this->getParam('btnActualizar')) {
                $id_recorrido = Application_Model_Recorrido::updateOrden($recorrido[0]->orden, $orden_nuevo, Application_Model_Recorrido::getOrden($recorrido[0]->id_ruta));
                $id_ruta = $recorrido[0]->id_ruta;
                if ($id_recorrido != 0) {
                    $this->redirect("default/ruta/editarorden/id-orden/$orden_nuevo/id-recorrido/$id_recorrido/id/$id_ruta");
                }
            }


            $this->view->recorridos = $recorridos = Application_Model_Recorrido::getRecorrido($this->getParam('id'));

            $this->view->ruta_desc = $recorridos[0]->des_ruta;
        } catch (Exception $ex) {
            
        }
    }

    public function eliminarecAction() {

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

            //echo $this->getParam('id');           
            $this->view->recorrido = $recorrido = Application_Model_Recorrido::getRecorrido(null, null, $this->getParam('id-recorrido'));
            //var_dump($recorrido);
            $this->view->rutas_desc = $recorrido[0]->des_ruta;
            $this->view->referencia_desc = $recorrido[0]->descripcion;
            //var_dump($recorrido);

            if ($this->getRequest()->isPost()) {

                if ($this->getParam('btnEliminar') == 'Si') {

                    $result = Application_Model_Recorrido::eliminarRecorridoRuta($recorrido);
//                    var_dump($result);
                    if ($result) {
                        echo "se elimino";
                        $id = $this->getParam('id-ruta');
                        $this->redirect("default/ruta/editrecorrido/id/$id/param/1");
                    }
                } elseif ($this->getParam('btnEliminar') == 'No') {

                    $this->redirect("default/ruta/crear");
                }
            }
        } catch (Exception $ex) {
            
        }
    }

}
