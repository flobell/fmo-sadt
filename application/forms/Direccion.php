<?php

/**
 * Formulario para agregar y editar cauchos
 *
 * @author felixpga
 */
class Application_Form_Direccion extends Fmo_Form_Abstract {

    //Elementos del formulario
    //const E_CIUDAD = 'ciudad';
    const E_GERENCIA = 'gerencia';
    const E_RUTA = 'ruta';
    const E_ESTADO = 'estado';
    const E_MUNICIPIO = 'municipio';
    const E_PARROQUIA = 'parroquia';
    const E_REFERENCIA = 'referencia';
    const E_ZONA = 'zona';
    const E_OTRA_ZONA = 'otra_zona';
    const E_SECTOR = 'sector';
    const E_AVENIDA = 'avenida';
    const E_CALLE = 'calle';
    const E_TIPO = 'tipo';
    const E_EDIFICIO = 'edificio';
    const E_PISO = 'PISO';
    const E_NUMERO = 'numero';
    const E_LOCALIDAD = 'localidad';
    const E_TELEFONO_CELULAR_EXT = 'telefono_celular_ext';
    const E_TELEFONO_CELULAR = 'telefono_celular';
    const E_TELEFONO_CASA_EXT = 'telefono_casa_ext';
    const E_TELEFONO_CASA = 'telefono_casa';
    const E_TELEFONO_OFICINA_EXT = 'telefono_oficina_ext';
    const E_TELEFONO_OFICINA = 'telefono_oficina';
    const E_CORREO = 'correo';
    const E_GRUPO_SANGUINEO = 'grupo_sanguineo';
    const E_FILE = 'archivo';
    //const E_FILE_ANTERIOR = "archivoAnterior";
    const E_ADJUNTAR = 'adjuntar';
    const E_ACEPTAR = 'guardar';
    const E_VOLVER = 'volver';

    public function init() {
        $this->setAction($this->getView()->url())
        ->setLegend('Registro de Servicios')
        ->setEnctype(self::ENCTYPE_MULTIPART);

        $eRuta = new Zend_Form_Element_Select(self::E_RUTA);
        $eRuta->setLabel('Ruta:')
        ->setAttrib('onchange', 'this.form.submit();')
        ->addMultiOption('', '(Seleccione)')
        ->addMultiOptions(Application_Model_Ruta::getPairs());
        $this->addElement($eRuta);

        $eCeco = new Zend_Form_Element_Select(self::E_GERENCIA);
        $eCeco->setLabel('Gerencia:')
        ->setAttrib('onchange', 'this.form.submit();')
        ->addMultiOption('', '(Seleccione)')
        ->addMultiOptions(Application_Model_Direccion::listarGerencias());
        $this->addElement($eCeco);

        $eEstado = new Zend_Form_Element_Select(self::E_ESTADO);
        $eEstado->setLabel('Estado:')->setRequired()
        ->addMultiOption('', '(Seleccione)')
        ->setAttrib('onchange', 'this.form.submit();')
        ->addMultiOptions(Application_Model_Zona::listarPares("nivel = 2 "));
        $this->addElement($eEstado);

        $eMunicipio = new Zend_Form_Element_Select(self::E_MUNICIPIO);
        $eMunicipio->setLabel('Municipio:')->setRequired()
        ->addMultiOption('', '(Seleccione)')
        ->setAttrib('onchange', 'this.form.submit();');
        //->addMultiOptions(Application_Model_Zona::listarPares("nivel = 2 "));
        $this->addElement($eMunicipio);
        /*
          $eCiudad = new Zend_Form_Element_Select(self::E_CIUDAD);
          $eCiudad->setLabel('Localidad:')
          //->setRequired()
          ->setAttrib('onchange', 'this.form.submit();')
          ->addMultiOption('', '(Seleccione)')/* ->addMultiOptions(Application_Model_Zona::listarPares("nivel = 4 ")) */;
        /*   $this->addElement($eCiudad);
         */
        $eParroquia = new Zend_Form_Element_Select(self::E_PARROQUIA);
        $eParroquia->setLabel('Parroquia:')->setRequired()
        ->addMultiOption('', '(Seleccione)')
        ->setAttrib('onchange', 'this.form.submit();');

        //->addMultiOption('', '(Seleccione)')->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null,null,'nivel = 4 and '));
        $this->addElement($eParroquia);

        $eZona = new Zend_Form_Element_Select(self::E_ZONA);
        $eZona->setLabel('Zona/Urb/Sector:')->setRequired()
        ->setAttrib('onchange', 'this.form.submit();')
        ->addMultiOption('', '(Seleccione)');
        //->addMultiOptions(Application_Model_Zona::getOtroPar());
        //->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null,null,'nivel = 3'));

        $this->addElement($eZona);
        
        // $eOtraZona = new Zend_Form_Element_Text(self::E_OTRA_ZONA);
        // $eOtraZona->setLabel('Docimicilio Especificado:')
        // ->setAttrib('size', '50')
        // ->setAttrib('maxlength', '255');
        // $this->addElement($eOtraZona);

        $eReferencia = new Zend_Form_Element_Select(self::E_REFERENCIA);
        $eReferencia->setLabel('Punto de Referencia:')//->setRequired()
        ->addMultiOption('', '(Seleccione)');

        //->addMultiOption('', '(Seleccione)')->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null,null,'nivel = 4 and '));
        $this->addElement($eReferencia);

        $eTelCelExt = new Zend_Form_Element_Select(self::E_TELEFONO_CELULAR_EXT);
        $eTelCelExt->setLabel('Ext. Cel:')
        ->addMultiOption('', '(Codg.)')
        ->addMultiOption('414', '0414')
        ->addMultiOption('424', '0424')
        ->addMultiOption('416', '0416')
        ->addMultiOption('426', '0426')
        ->addMultiOption('412', '0412');
        $this->addElement($eTelCelExt);

        //->setAttrib('onchange', 'this.form.submit();');

        $eTelCel = new Zend_Form_Element_Text(self::E_TELEFONO_CELULAR);
        $eTelCel->setLabel('Telefono Celular:')
        ->addValidator('StringLength', false, array('min' => 7, 'max' => 7, 'encoding' => $this->getView()->getEncoding()))
        ->addValidator('Digits', true)
        ->addValidator('Between', true, array('inclusive' => true, 'min' => 1, 'max' => 999999999999))
        ->setAttrib('size', '7')
        ->setAttrib('maxlength', '7')
        ->addFilter('StringTrim');
        $this->addElement($eTelCel);


        
        $eTelCasaExt = new Zend_Form_Element_Select(self::E_TELEFONO_CASA_EXT);
        $eTelCasaExt->setLabel('Ext. Casa:')
            ->addMultiOption('', '(Codg.)')
            ->addMultiOptions(Application_Model_Extension::listarPares());
            
        $this->addElement($eTelCasaExt);

        $eTelCasa = new Zend_Form_Element_Text(self::E_TELEFONO_CASA);
        $eTelCasa->setLabel('Telefono Casa:')
                ->addValidator('StringLength', false, array('min' => 7, 'max' => 7, 'encoding' => $this->getView()->getEncoding()))
                ->addValidator('Digits', true)
                ->addValidator('Between', true, array('inclusive' => true, 'min' => 1, 'max' => 999999999999))
                ->setAttrib('size', '7')
                ->setAttrib('maxlength', '7')
                ->addFilter('StringTrim');
        $this->addElement($eTelCasa);


        $eTelOficinaExt = new Zend_Form_Element_Select(self::E_TELEFONO_OFICINA_EXT);
        $eTelOficinaExt->setLabel('Ext. Ofi:')
            ->addMultiOption('', '(Codg.)')
            ->addMultiOptions(Application_Model_Extension::listarPares());
        $this->addElement($eTelOficinaExt);

        $eTelOficina = new Zend_Form_Element_Text(self::E_TELEFONO_OFICINA);
        $eTelOficina->setLabel('Telefono Oficina:')
                ->addValidator('StringLength', false, array('min' => 7, 'max' => 7, 'encoding' => $this->getView()->getEncoding()))
                ->addValidator('Digits', true)
                ->addValidator('Between', true, array('inclusive' => true, 'min' => 1, 'max' => 999999999999))
                ->setAttrib('size', '7')
                ->setAttrib('maxlength', '7')
                ->addFilter('StringTrim');
        $this->addElement($eTelOficina);

        $eAvenida = new Zend_Form_Element_Text(self::E_AVENIDA);
        $eAvenida->setLabel('Avenida:')
                ->setAttrib('size', '30')
                ->setAttrib('maxlength', '255')
                ->addFilter('StringTrim');
        $this->addElement($eAvenida);

        $eSector = new Zend_Form_Element_Text(self::E_SECTOR);
        $eSector->setLabel('Sector:')
                ->setAttrib('size', '30')
                ->setAttrib('maxlength', '255')
                ->setAttrib('placeholder', 'Zona/Urb/Sector')
                ->addFilter('StringTrim');
        $this->addElement($eSector);

        $eCalle = new Zend_Form_Element_Text(self::E_CALLE);
        $eCalle->setLabel('Calle/Manzana:')
                ->setAttrib('size', '30')
                ->setAttrib('maxlength', '255')
                ->addFilter('StringTrim');
        $this->addElement($eCalle);

        $eTipo = new Zend_Form_Element_Select(self::E_TIPO);
        $eTipo->setLabel('Tipo de Vivienda:')
                ->setRequired()
                ->setAttrib('onchange', 'this.form.submit();')
                ->addMultiOptions(array('1' => 'Edificio', '2' => 'Casa'));
        $this->addElement($eTipo);

        $eEdificio = new Zend_Form_Element_Text(self::E_EDIFICIO);
        $eEdificio->setLabel('N° Edificio:')
                ->setRequired()
                ->setAttrib('size', '25')
                ->setAttrib('maxlength', '25')
                ->addFilter('StringTrim');
        $this->addElement($eEdificio);

        $ePiso = new Zend_Form_Element_Text(self::E_PISO);
        $ePiso->setLabel('Piso:')
                ->setRequired()
                ->setAttrib('size', '25')
                ->setAttrib('maxlength', '25')
                ->addFilter('StringTrim');
        $this->addElement($ePiso);

        $eNumero = new Zend_Form_Element_Text(self::E_NUMERO);
        $eNumero->setLabel('Número de Casa/Apto:')
                ->setRequired()
                ->setAttrib('size', '25')
                ->setAttrib('maxlength', '25')
                ->addFilter('StringTrim');
        $this->addElement($eNumero);

        $eLocalidad = new Zend_Form_Element_Select(self::E_LOCALIDAD);
        $eLocalidad->setLabel('Localidad:')
                ->setRequired()
                ->setAttrib('onchange', 'this.form.submit();')
                ->addMultiOption('', '(Seleccione)')
                ->addMultiOptions(Application_Model_Localidad::getPairs());
        $this->addElement($eLocalidad);    
        
        $eGrupoSanguineo = new Zend_Form_Element_Select(self::E_GRUPO_SANGUINEO);
        $eGrupoSanguineo->setLabel('Tipo de sangre:')
            ->addMultiOption('','(Seleccione el tipo de sangre...)')
            ->addMultiOptions(
                array(
                    'A+' => 'A+',
                    'B+' => 'B+',
                    'O+' => 'O+',
                    'AB+' => 'AB+',
                    'A-' => 'A-',
                    'B-' => 'B-',
                    'O-' => 'O-',
                    'AB-' => 'AB-'
                )
            );
        $this->addElement($eGrupoSanguineo);

        $eCorreo= new Zend_Form_Element_Text(self::E_CORREO);
        $eCorreo->setLabel('Correo Electrónico:')
        ->addValidator('EmailAddress');
        $this->addElement($eCorreo);
        
        $archivo = new Zend_Form_Element_File(self::E_FILE);
        $archivo//->setDescription("Tamaño máximo {$archivo->getMaxFileSize()}")
                ->setDescription("Debe ser un Archivo de tipo PDF")
                //->setRequired()
                ->addValidator('MimeType', false, array('application/pdf'))
                ->setLabel('Adjuntar Constancia de Residencia / Rif / Factura de Servicio:');



//  ->setDestination(realpath(__DIR__ . '/../../docum'));
        $this->addElement($archivo);

        //****************************************
        //********************BOTONES*************
        //**************************************** 

        $eleA = new Zend_Form_Element_Submit(self::E_ACEPTAR);
        $eleA->setLabel('Guardar')
                ->setIgnore(true);
        $this->addElement($eleA);

        $eleC = new Zend_Form_Element_Submit(self::E_VOLVER);
        $eleC->setLabel('Salir')
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
        //var_dump($servicio->toArray());exit;
        //$this->setDefault(self::E_SERVICIO, $idServicio);

        if ($direccion) {
            /* $this->getElement(self::E_CIUDAD)
              ->addMultiOptions(Application_Model_Zona::listarPares("id_zona = '$direccion->municipio'"));
              */
            //Zend_Debug::dd($direccion);

            $this->getElement(self::E_MUNICIPIO)
            ->addMultiOptions(Application_Model_Zona::listarPares("id_zona = '$direccion->estado'"));
            $this->getElement(self::E_PARROQUIA)
            ->addMultiOptions(Application_Model_Zona::listarPares("id_zona = '$direccion->municipio'"));
            $this->getElement(self::E_ZONA)
            ->addMultiOptions(Application_Model_Zona::listarPares("id_zona = '$direccion->parroquia'"));
            $this->getElement(self::E_LOCALIDAD)
            ->addMultiOptions(Application_Model_Localidad::getPairs("id = '$direccion->localidad'"));    
            $this->getElement(self::E_REFERENCIA)
            ->addMultiOptions(Application_Model_Referencia::getPairs($direccion->parroquia));     
            // $this->getElement(self::E_TELEFONO_CASA_EXT)
            //->addMultiOptions(Application_Model_Extension::listarPares("a.id_zona = '$direccion->estado'"));
            // $this->getElement(self::E_TELEFONO_OFICINA_EXT)
            //->addMultiOptions(Application_Model_Extension::listarPares("a.id_zona = '$direccion->estado'"));

            $tipo = $direccion->tipo;
            if ($tipo == '2') {
                $this->getElement(self::E_EDIFICIO)->setValue('')->setAttrib('disabled', 'disabled')->setRequired(false)->setValidators(array());
                $this->getElement(self::E_PISO)->setValue('')->setAttrib('disabled', 'disabled')->setRequired(false)->setValidators(array());
            }
            //var_dump($direccion);
            $this->setDefault(self::E_ESTADO, $direccion->estado)
            ->setDefault(self::E_MUNICIPIO, $direccion->municipio)
            //->setDefault(self::E_CIUDAD, $direccion->ciudad)
            ->setDefault(self::E_PARROQUIA, $direccion->parroquia)
            ->setDefault(self::E_REFERENCIA, $direccion->id_referencia)
            ->setDefault(self::E_ZONA, $direccion->id_zona)
            ->setDefault(self::E_TELEFONO_CASA_EXT, substr($direccion->telefono,0,3))
            ->setDefault(self::E_TELEFONO_CASA, substr($direccion->telefono,3,9))
            ->setDefault(self::E_TELEFONO_OFICINA_EXT, substr($direccion->telefono_oficina,0,3))
            ->setDefault(self::E_TELEFONO_OFICINA, substr($direccion->telefono_oficina,3,9))
            ->setDefault(self::E_GRUPO_SANGUINEO, $direccion->grupo_sanguineo)
            ->setDefault(self::E_CORREO, $direccion->correo)
            ->setDefault(self::E_SECTOR, $direccion->sector)
            ->setDefault(self::E_AVENIDA, $direccion->avenida)
            ->setDefault(self::E_CALLE, $direccion->calle)
            ->setDefault(self::E_EDIFICIO, $direccion->edificio)
            ->setDefault(self::E_TIPO, $direccion->tipo)
            ->setDefault(self::E_PISO, $direccion->piso)
            ->setDefault(self::E_NUMERO, $direccion->n_casa)
            ->setDefault(self::E_LOCALIDAD, $direccion->localidad);

            if ($direccion->celular != '0') {
                $this->setDefault(self::E_TELEFONO_CELULAR_EXT, substr($direccion->celular,0,3));
                $this->setDefault(self::E_TELEFONO_CELULAR, substr($direccion->celular,3,9));
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Método para guardar los datos en la Base de Datos
     * */
    public function guardar($cedula) {
        $tDireccion = new Application_Model_DbTable_Direccion();
        $web = new Fmo_WebService_Sadt();
        $sel = $tDireccion->select()->where("cedula = $cedula");
        $registro = $sel->getAdapter()->fetchAll($sel);
        $direccion = $celular = $telefono = $oficina = '';
        //$this->getValues();exit;
        if (count($registro) == 0) {
            $registro = $tDireccion->createRow();
            $registro->cedula = $cedula;
        } else {
            $registro = $tDireccion::findOneById($cedula);
        }
        $estado = $this->getValue(self::E_ESTADO);
        $municipio = $this->getValue(self::E_MUNICIPIO);
        $parroquia = $this->getValue(self::E_PARROQUIA);
        
        $localidad = $registro->localidad = strtoupper($this->getValue(self::E_LOCALIDAD));

        $sector = $registro->id_zona = $this->getValue(self::E_ZONA);
       
        if ($this->getValue(self::E_REFERENCIA)) {
            $registro->id_referencia = $this->getValue(self::E_REFERENCIA);
        } else {
            $registro->id_referencia = new Zend_Db_Expr('NULL');
        }
        if ($this->getValue(self::E_SECTOR)) {
            $direccion.= ' Sector. ' . $registro->sector = strtoupper($this->getValue(self::E_SECTOR));
        } else {
            $direccion.= ' Sector. ' . $registro->sector = '';
        }
        if ($this->getValue(self::E_AVENIDA)) {
            $direccion.= ' Av. ' . $registro->avenida = strtoupper($this->getValue(self::E_AVENIDA));
        } else {
            $direccion.= ' Av. ' . $registro->avenida = '';
        }
        if ($this->getValue(self::E_CALLE)) {
            $direccion.= ' Calle. ' . $registro->calle = strtoupper($this->getValue(self::E_CALLE));
        } else {
            $direccion.= ' Calle. ' . $registro->calle = '';
        }
        //TELEFONO CELULAR
        if ($this->getValue(self::E_TELEFONO_CELULAR)) {
            $celular = $registro->celular = $this->getValue(self::E_TELEFONO_CELULAR_EXT).$this->getValue(self::E_TELEFONO_CELULAR);
        } else {
            $celular = $registro->celular = '0';
        }
        //TELEFONO DE CASA
        if ($this->getValue(self::E_TELEFONO_CASA)) {
            $telefono = $registro->telefono = $this->getValue(self::E_TELEFONO_CASA_EXT).$this->getValue(self::E_TELEFONO_CASA);
        } else {
            $telefono = $registro->telefono = new Zend_Db_Expr('NULL');
        }
        //TELEFONO DE OFICINA
        if ($this->getValue(self::E_TELEFONO_OFICINA)) {
            $oficina = $registro->telefono_oficina = $this->getValue(self::E_TELEFONO_OFICINA_EXT).$this->getValue(self::E_TELEFONO_OFICINA);
        } else {
            $oficina = $registro->telefono_oficina = new Zend_Db_Expr('NULL');
        }

        //GRUPO SANGUINEO
        if($this->getValue(self::E_GRUPO_SANGUINEO)){
            $registro->grupo_sanguineo = $this->getValue(self::E_GRUPO_SANGUINEO);
        } else {
            $registro->grupo_sanguineo = '';
        }

        //CORREO ELECTRONICO
        if($this->getValue(self::E_CORREO)){
            $registro->correo = $this->getValue(self::E_CORREO);
        } else {
            $registro->correo = '';
        }

        //TIPO DE VIVIENDA (EDIFICIO 1 O CASA 2)
        $registro->tipo = $this->getValue(self::E_TIPO);
        if ($this->getValue(self::E_TIPO) == '1') {
            $direccion.= ' Edif. ' . $registro->edificio = strtoupper($this->getValue(self::E_EDIFICIO));
            $direccion.= ' Piso ' . $registro->piso = strtoupper($this->getValue(self::E_PISO));
        } 
        //NUMERO DE CASA
        if ($this->getValue(self::E_NUMERO)) {
            $direccion.= ' Num. ' . $registro->n_casa = strtoupper($this->getValue(self::E_NUMERO));
        } else {
            $direccion.= ' Num. ' . $registro->n_casa = '';
        }
              
        
        //Zend_Debug::dd($registro);
        // $registro->usr_mod = Fmo_Model_Seguridad::getUsuarioSesion()->siglado;
        // $registro->fch_mod = new Zend_Db_Expr('now()');
        $guardado = $registro->save();

        try{
            $respuesta = 'nada';
            if ($guardado) {
                $usuario = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::CEDULA};
                //var_dump('iiii-------------', $usuario, $estado, $municipio, $sector, $parroquia, $direccion, $celular, $telefono, $oficina, $localidad,$usuario);
                //exit;
                $respuesta = $web->guardarDireccion($cedula, strtoupper($estado), strtoupper($municipio),strtoupper( $sector), strtoupper($parroquia), strtoupper($direccion), $celular, $telefono, $oficina, strtoupper($localidad), $usuario);
            }
            return $guardado;
        } catch (Exception $ex) {
            return $guardado;
        }

        //Zend_Debug::dd($respuesta);
        return $guardado;
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

    public static function buscarTrabajador($id) {
        $activo = Application_Model_Direccion::trabajadoresActivos($id);
        if ($activo) {
            $persona = Fmo_Model_Personal::findOneByFicha($id);
            if (!$persona) {
                return Fmo_Model_Personal::findOneByCedula($id);
            } else {
                return $persona;
            }
        }
    }

    public function setDefaults(array $defaults) {

        $estado = $defaults[self::E_ESTADO];

        $municipio = $defaults[self::E_MUNICIPIO];
        //$ciudad = $defaults[self::E_CIUDAD];
        $parroquia = $defaults[self::E_PARROQUIA];
        //echo'<br/><br/> estado', var_dump($estado);



        if ($estado) {

            $this->getElement(self::E_MUNICIPIO)
                    ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$estado'"));

            if ($municipio) {
                /* $this->getElement(self::E_CIUDAD)
                  ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$municipio'"));
                  if ($ciudad != '' && ($municipio == substr($ciudad, 0, strlen($municipio)))) {
                 */ $this->getElement(self::E_PARROQUIA)
                        ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$municipio'"));

                if ($parroquia != '') {
                    if (Application_Model_Zona::isPadre($municipio, $parroquia)) {
                        $this->getElement(self::E_REFERENCIA)
                                ->addMultiOptions(Application_Model_Referencia::getPairs($parroquia));
                        $this->getElement(self::E_ZONA)
                                ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$parroquia'"));
                    }
                }
                //}
            }

            $this->getElement(self::E_TELEFONO_CASA_EXT)
            ->addMultiOptions(Application_Model_Extension::listarPares("a.id_zona = '$estado'"));
            $this->getElement(self::E_TELEFONO_OFICINA_EXT)
            ->addMultiOptions(Application_Model_Extension::listarPares("a.id_zona = '$estado'"));
        }

        $tipo = $defaults[self::E_TIPO];
        if ($tipo == '2') {
            $this->getElement(self::E_EDIFICIO)->setValue('')->setAttrib('disabled', 'disabled')->setRequired(false)->setValidators(array());
            $this->getElement(self::E_PISO)->setValue('')->setAttrib('disabled', 'disabled')->setRequired(false)->setValidators(array());
        }
        parent::setDefaults($defaults);
    }

    public function recargar(array $defaults) {
        $estado = $defaults[self::E_ESTADO];
        if ($estado) {
            $municipio = $defaults[self::E_MUNICIPIO];
            $this->getElement(self::E_MUNICIPIO)
                        ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$estado'"));
            if ($municipio) {
            $parroquia = $defaults[self::E_PARROQUIA];
                /* $this->getElement(self::E_CIUDAD)
                  ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$municipio'"));
                  if ($ciudad != '' && ($municipio == substr($ciudad, 0, strlen($municipio)))) {
                 */ $this->getElement(self::E_PARROQUIA)
                        ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$municipio'"));

                if ($parroquia != '') {
                    if (Application_Model_Zona::isPadre($municipio, $parroquia)) {
                        $this->getElement(self::E_REFERENCIA)
                                ->addMultiOptions(Application_Model_Referencia::getPairs($parroquia));
                        $this->getElement(self::E_ZONA)
                                ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$parroquia'"));
                    }
                }
                //}
            }
        }
        parent::setDefaults($defaults);
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
