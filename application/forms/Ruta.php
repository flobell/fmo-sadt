<?php

/**
 * Formulario para agregar y editar cauchos
 *
 * @author enocc
 */
class Application_Form_Ruta extends Fmo_Form_Abstract {

    //Elementos del formulario
    const E_ESTADO = 'estado';
    const E_MUNICIPIO = 'municipio';
    const E_CIUDAD = 'ciudad';
    const E_PARROQUIA = 'parroquia';
    const E_ZONA = 'zona';
    const E_SECTOR = 'sector';
    const E_AVENIDA = 'avenida';
    const E_CALLE = 'calle';
    const E_TIPO = 'tipo';
    const E_EDIFICIO = 'edificio';
    const E_PISO = 'PISO';
    const E_NUMERO = 'numero';
    const E_TELEFONO_CELULAR = 'telefono_celular';
    const E_TELEFONO_CASA = 'telefono_casa';
    const E_TELEFONO_OFICINA = 'telefono_oficina';
    const E_FILE = 'archivo';
    //const E_FILE_ANTERIOR = "archivoAnterior";
    const E_ADJUNTAR = 'adjuntar';
    const E_ACEPTAR = 'guardar';
    const E_VOLVER = 'volver';
    const E_LOCALIDAD = 'localidad';
    const E_RUTA = 'ruta';
    const E_HORARIO = 'horario';
    const E_ORDEN = 'orden';
    const E_GERENCIA = 'gerencia';
    const E_TPNO = 'tpno';

    public function init() {
        $this->setAction($this->getView()->url())
                ->setLegend('Registro de Servicios')
                ->setEnctype(self::ENCTYPE_MULTIPART);



        $eHorario = new Zend_Form_Element_Select(self::E_HORARIO);
        $eHorario->setLabel('Horario:')
                ->setRequired()
                ->setAttrib('onchange', 'this.form.submit();')
                ->addMultiOption('', '(Seleccione)')->addMultiOptions(Application_Model_Horario::getPairs());
        $this->addElement($eHorario);

        $eRuta = new Zend_Form_Element_Select(self::E_RUTA);
        $eRuta->setLabel('Ruta:')
                ->setRequired()
                ->addMultiOption('', '(Seleccione)')
                ->setAttrib('onchange', 'this.form.submit();');
        $this->addElement($eRuta);

        $eEstado = new Zend_Form_Element_Select(self::E_ESTADO);
        $eEstado->setLabel('Estado:')
                ->setRequired()
                ->setAttrib('onchange', 'this.form.submit();')
                ->addMultiOption('', '(Seleccione)')->addMultiOptions(Application_Model_Zona::listarPares("nivel = 2 "));
        $this->addElement($eEstado);

        $eMunicipio = new Zend_Form_Element_Select(self::E_MUNICIPIO);
        $eMunicipio->setLabel('Municipio:')
                ->setRequired()
                ->addMultiOption('', '(Seleccione)')
                ->setAttrib('onchange', 'this.form.submit();');
        $this->addElement($eMunicipio);


        $eParroquia = new Zend_Form_Element_Select(self::E_PARROQUIA);
        $eParroquia->setLabel('Parroquia:')
                ->setRequired()
                ->addMultiOption('', '(Seleccione)')
                ->setAttrib('onchange', 'this.form.submit();');
        $this->addElement($eParroquia);

        $eSector = new Zend_Form_Element_Select(self::E_SECTOR);
        $eSector->setLabel('Sector:')
                ->setRequired()
                ->addMultiOption('', '(Seleccione)')
                ->setAttrib('onchange', 'this.form.submit();');
        $this->addElement($eSector);

//        $nuevo_orden = Application_Model_Recorrido::getMaxOrdenes(); // $nuevo_orden = count($ordenes) + 1;
//        for($i=1;$i<=$nuevo_orden;$i++){
//            $resultado[$i]=$i;
//        }

        $eOrden = new Zend_Form_Element_Select(self::E_ORDEN);
        $eOrden->setLabel('Orden:')
                ->setRequired()
                ->addMultiOption('', '(Seleccione)')
                ->setAttrib('onchange', 'this.form.submit();');
        $this->addElement($eOrden);

        //lsitado de las gerencias

        $eGerencia = new Zend_Form_Element_Select(self::E_GERENCIA);
        $eGerencia->setLabel('Gerencia:')
                ->setRequired()
                ->setAttrib('onchange', 'this.form.submit();')
                ->addMultiOption('', '(Seleccione)')->addMultiOptions(Application_Model_Direccion::listarGerencias());
        $this->addElement($eGerencia);

        $eTpno = new Zend_Form_Element_Select(self::E_TPNO);
        $eTpno->setLabel('Tipo Nómina:')
                ->setRequired()
                ->setAttrib('onchange', 'this.form.submit();')
                ->addMultiOption('', '(Seleccione)')->addMultiOptions(Application_Model_Direccion::listarTpno());
        $this->addElement($eTpno);


//        $orden = new Zend_Form_Element_Select(self::E_ORDEN);
//        $orden->setLabel('Orden: ')
//                ->addMultiOptions($resultado)
//                ->setValue($nuevo_orden);
//        $this->addElement($orden);
//        $eParroquia = new Zend_Form_Element_Select(self::E_PARROQUIA);
//        $eParroquia->setLabel('Parroquia:')
//                ->setRequired()
//                ->addMultiOption('', '(Seleccione)')
//                ->setAttrib('onchange', 'this.form.submit();');
//
//        $this->addElement($eParroquia);
//        $eZona = new Zend_Form_Element_Select(self::E_ZONA);
//        $eZona->setLabel('Parroquia:')
//                ->setRequired()->addMultiOption('', '(Seleccione)')
//                ->setAttrib('onchange', 'this.form.submit();');
//        $this->addElement($eZona);
//        $eSector = new Zend_Form_Element_Select(self::E_SECTOR);
//        $eSector->setLabel('Sector:')
//                ->setRequired()->addMultiOption('', '(Seleccione)')
//                ->setAttrib('onchange', 'this.form.submit();');
//        $this->addElement($eSector);
        // echo self::E_ESTADO;
        //$localidad=Application_Model_Localidad::getPairs();
//        $localidad=self::E_LOCALIDAD;
//        var_dump($localidad);
//        $eLocalidad = new Zend_Form_Element_Select(self::E_LOCALIDAD);
//        $eLocalidad->setLabel('Localidad:')
//                ->setRequired()
//                ->setAttrib('onchange', 'this.form.submit();')
//                ->addMultiOption('', '(Seleccione)')->addMultiOptions(Application_Model_Localidad::getPairs());
//        $this->addElement($eLocalidad);
//        
//        $eRuta = new Zend_Form_Element_Select(self::E_RUTA);
//        $eRuta->setLabel('Ruta:')
//                ->setRequired()->addMultiOption('', '(Seleccione)')
//                ->setAttrib('onchange', 'this.form.submit();');
//        $this->addElement($eRuta);
        //****************************************
        //********************BOTONES*************
        //**************************************** 

        $eleA = new Zend_Form_Element_Submit(self::E_ACEPTAR);
        $eleA->setLabel('Agregar')
                ->setIgnore(true);
        $this->addElement($eleA);

        $eleC = new Zend_Form_Element_Submit(self::E_VOLVER);
        $eleC->setLabel('Guardar')
                ->setIgnore(true);
        $this->addElement($eleC);

        $this->setCustomDecorators();
    }

    /**
     * Método para inicializar los valores por defecto en el formulario
     *
     */
    public function valoresPorDefecto($direccion) {
        $tDireccion = new Application_Model_DbTable_Direccion();
        //var_dump($direccion);
        //var_dump($servicio->toArray());exit;
        //$this->setDefault(self::E_SERVICIO, $idServicio);
        if ($direccion) {
            $this->getElement(self::E_PARROQUIA)
                    ->addMultiOptions(Application_Model_Zona::listarPares("id_zona = '$direccion->ciudad'"));
            $this->getElement(self::E_ZONA)
                    ->addMultiOptions(Application_Model_Zona::listarPares("id_zona = '$direccion->parroquia'", 1));

            return true;
        } else {
            return false;
        }
    }

    /**
     * Método para guardar los datos en la Base de Datos
     * */
    public function guardar() {
        try {
            if ($this->getValue(self::E_SECTOR) || $this->getValue(self::E_RUTA) || $this->getValue(self::E_HORARIO)) {

                //            var_dump($this->getValue(self::E_SECTOR));
//            exit;
//                $tRutaZona = new Application_Model_DbTable_RutaZona();
//                $a=Application_Model_Recorrido::getmaxOrden($this->getValue(self::E_RUTA));
//                var_dump($a);
//                exit;
                $tRecorrido = new Application_Model_DbTable_Recorrido();
                //        $sel = $tDireccion->select()->where("cedula = $usuario");
                //        $registro = $sel->getAdapter()->fetchAll($sel);
                //$this->getValues();exit;
//                $registro = $tRutaZona->createRow();
                $registro = $tRecorrido->createRow();
                //        if (count($registro) == 0) {
                //            $registro = $tDireccion->createRow();
                //            $registro->cedula = $usuario;
                //        } else {
                //            $registro = $tDireccion::findOneById($usuario);
                //        }
                //$registro->id_zona = $this->getValue(self::E_SECTOR);
                //$a=Application_Model_Recorrido::getmaxOrden($this->getValue(self::E_RUTA));
                $registro->id_ruta = $this->getValue(self::E_RUTA);
                $registro->id_refezona = $this->getValue(self::E_SECTOR);
                $registro->orden = Application_Model_Recorrido::getmaxOrden($this->getValue(self::E_RUTA));
                ; //INSERTAR ORDEN   
//                var_dump($registro->orden);
//                exit;

                return $registro->save();
            } else {
                throw new Exception("Debe seleccionar el Sector y la Ruta.", -1);
            }
        } catch (Exception $ex) {
            switch ($ex->getCode()) {
                case -1: {
                        throw new Exception($ex->getMessage());
                    }break;

                default : {
                        throw new Exception("No se logró agregar la Ruta.");
                    }break;
            }
        }
    }

    /**
     * Metodo para guardar el archivo adjunto
     * @param Zend_Form_Element_File $file
     * @param type $nameServer
     * @return boolean
     * @throws Exception
     */
    public function guardarNuevoA(Zend_Form_Element_File $file, $nameServer, $id = null) {

        Zend_Db_Table::getDefaultAdapter()->beginTransaction();
        try {
            $tAdjunto = new Application_Model_DbTable_Adjunto();
            $registro = null;

            if ($id) {
                $nuevo = Application_Model_Adjunto::buscar($id);
                if (!$nuevo) {
                    $registro = $tAdjunto->createRow();
                } else {
                    $registro = Application_Model_DbTable_Adjunto::findOneById($id);
                }
                $registro->id = basename($nameServer);
                $registro->nombre = $file->getValue();
                $registro->ruta_almacenamiento = str_replace(PATH_PROJECT . DIRECTORY_SEPARATOR, '', $nameServer);
                $registro->descripcion = 'Constancia de trabajo';
                $registro->tamanio = filesize($nameServer);
                $registro->suma_comprobacion = md5_file($nameServer);
                $registro->tipo_mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $nameServer);
                $registro->save();
//echo '<br/><br/><br/><br/>despues son estos:',  var_dump(PATH_PROJECT,DIRECTORY_SEPARATOR, basename($nameServer),$salida);
            } else {
                echo 'no hay ID';
                return false;
            }
            $resultado = true;
            Zend_Db_Table::getDefaultAdapter()->commit();
        } catch (Exception $ex) {
            $resultado = false;
            Zend_Db_Table::getDefaultAdapter()->rollBack();
            Fmo_Logger::debug($ex->getMessage());
            $mensaje = $ex->getMessage();
            throw $ex;
        }

        return $resultado;
    }

    /**
     * Proceso para guardar los archivos
     * @param array $data
     * @return type
     */
    public function proceso(array $data, $id) {
        $tservicio = 'SGP';
        $valor = false;
        if ($this->isValid($data) && $id) {
            $eFile = $this->getElement(self::E_FILE);
            if ($eFile->getValue()) {
                if ($eFile->receive()) {
                    $filename = $this->getElement(self::E_FILE)->getFileName();
                    $nuevoNombre = dirname($filename) . DIRECTORY_SEPARATOR . $id;
                    //$tipomime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $nuevoNombre);
                    rename($this->getElement(self::E_FILE)->getFileName(), $nuevoNombre);
                    try {
                        $valor = $this->guardarNuevoA($this->getElement(self::E_FILE), $nuevoNombre, $id);
                    } catch (Exception $ex) {
                        $this->getElement(self::E_FILE)
                                ->addError($ex->getMessage());
                        unlink($nuevoNombre);
                    }
                } else {
                    $eFile->addError('No recibe');
                }
            }
        }
        Fmo_Logger::debug($this->getMessages());
        Fmo_Logger::debug($this->getErrors());
        Fmo_Logger::debug($this->getErrorMessages());
        Fmo_Logger::debug($valor);
        return $valor;
    }

    public function adjuntar(array $data) {
        $tservicio = "sgp";
        $valor = false;
        if (parent::isValid($data)) {
            if ($this->getElement(self::E_FILE)->receive()) {

                $filename = $this->getElement(self::E_FILE)->getFileName();
                $nuevoNombre = dirname($filename) . DIRECTORY_SEPARATOR . uniqid($tservicio) . '-' . basename($filename);
                rename($this->getElement(self::E_FILE)->getFileName(), $nuevoNombre);
                try {
                    $valor = $this->guardarAdjunto($this->getElement(self::E_FILE), $nuevoNombre);
                } catch (Exception $ex) {
                    $this->getElement(self::E_FILE)
                            ->addError($ex->getMessage());
                    unlink($nuevoNombre);
                }
            }
        }
        Fmo_Logger::debug($this->getMessages());
        Fmo_Logger::debug($this->getErrors());
        Fmo_Logger::debug($this->getErrorMessages());
        Fmo_Logger::debug($valor);
        return $valor;
    }

    public function guardarAdjunto(Zend_Form_Element_File $file, $nameServer) {
        Zend_Db_Table::getDefaultAdapter()->beginTransaction();
        try {
            $tadjunto = new Application_Model_DbTable_Adjunto();
            $registro = $tadjunto->createRow();
            $registro->nombre = $file->getValue();
            $registro->ruta_almacenamiento = str_replace(PATH_PROJECT . DIRECTORY_SEPARATOR, '', $nameServer);
            $registro->descripcion = $this->getValue(self::E_DESCRI);
            $registro->tamanio = filesize($nameServer);
            $registro->suma_comprobacion = md5_file($nameServer);
            $registro->tipo_mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $nameServer);
            $idArchivo = $registro->save();

            $tadjuntoS = new Application_Model_DbTable_AdjuntoSolicitud();
            $registroS = $tadjuntoS->createRow();
            $registroS->id_adjunto = $idArchivo;
            $registroS->id_solicitud = $this->getSolicitud()->id;

            $registroS->save();
            $resultado = true;
            Zend_Db_Table::getDefaultAdapter()->commit();
        } catch (Exception $ex) {
            $resultado = false;
            Zend_Db_Table::getDefaultAdapter()->rollBack();
            Fmo_Logger::debug($ex->getMessage());
            throw $ex;
        }
        return $resultado;
    }

    /**
     * Método para guardar los datos modificados en la Base de Datos
     * */
    public function modificarCodigo($id) {
        $tCodigo = Application_Model_DbTable_Codigo::findOneById($id);
        $tCodigo->nombre = $this->getValue(self::E_NOMBRE_CODIGO);
        return $tCodigo->save();
    }

    public function ruta($localidad) {
        $this->getElement(self::E_RUTA)
                ->addMultiOptions(Application_Model_Ruta::getPairs($localidad));
    }

    public function setDefaults(array $defaults, $id_ruta = null) {
//        echo "entro en la funcion";
//        exit;
        if (isset($defaults[self::E_HORARIO])) {
            $horario = $defaults[self::E_HORARIO];
        }
        if (!empty($horario)) {
            $this->getElement(self::E_RUTA)
                    ->addMultiOptions(Application_Model_Ruta::getPairsRutaHorario($horario));
        }
        if ($id_ruta) {
            if (isset($defaults[self::E_SECTOR])) {
                $estado = $defaults[self::E_ESTADO];
            }else{
                $estado = '';
            }
            if (isset($defaults[self::E_ESTADO])) {
                $sector = $defaults[self::E_SECTOR];
            }
            if ($estado != '') {
                $municipio = $defaults[self::E_MUNICIPIO];
                $parroquia = $defaults[self::E_PARROQUIA];
                $this->getElement(self::E_MUNICIPIO)
                        ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$estado'"));

                if ($municipio != '') {

                    $municipios = Application_Model_Zona::getNivel($municipio);

                    $this->getElement(self::E_PARROQUIA)
                            ->addMultiOptions(Application_Model_Zona::getParroquias($municipio, $municipios->nivel));

                    if ($parroquia != '') {

                        $this->getElement(self::E_SECTOR)
                                ->addMultiOptions(Application_Model_Referencia::getPairs($parroquia));
                    }
                }
            }
        }
        parent::setDefaults($defaults);
    }

    public function recargar(array $defaults) {
        $ciudad = $defaults[self::E_CIUDAD];
        $parroquia = $defaults[self::E_PARROQUIA];
        if ($ciudad != '') {
            $this->getElement(self::E_PARROQUIA)
                    ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$ciudad'"));

            if ($parroquia != '' && ($ciudad == substr($parroquia, 0, strlen($ciudad)))) {
                $this->getElement(self::E_ZONA)
                        ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$parroquia'"));
            }
        }
        parent::setDefaults($defaults);
    }

    public static function crearCombobox($id, $nivel) {
        
    }

    /*
      public function validarTipo() {
      $filename = $this->getElement(self::E_FILE)->getFileName();

      $tipomime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $this->getElement(self::E_FILE));
      var_dump($tipomime);
      /*if($tipomime=='application/pdf'){
      return true;
      }
      return false;
      }
     */
}
