<?php

/**
 * Formulario para agregar y editar cauchos
 *
 * @author Pedro Flores <fmo16554@ferrominera.gob.ve>
 */
class Application_Form_Medicamento extends Fmo_Form_Abstract {

    const E_PATOLOGIA = 'patologia';
    const E_MEDICAMENTO = 'medicamento';
    const E_DOSIS = 'dosis'; // cada 1 - 24 horas
    const E_FRECUENCIA_TIPO = 'frecuencia_tipo'; // temporal o permanente
    const E_FRECUENCIA = 'frecuencia'; // 1,2... 
    const E_FRECUENCIA_UNIDAD = 'frecuencia_unidad'; // dia(s), semana(s), mes(es)
    const E_INDICACION = 'indicacion';
    const E_INFORME = 'informe';
    const E_ACEPTAR = 'guardar';
    const E_VOLVER = 'volver';

    public function init() {
        $this->setAction($this->getView()->url())
            ->setLegend('Patologias del trabajador')
            ->setEnctype(self::ENCTYPE_MULTIPART);

        $ePatologia = new Zend_Form_Element_Select(self::E_PATOLOGIA);
        $ePatologia->setLabel('Patología: ')->setRequired()
        ->addMultiOption('', '(Seleccione una patologia asociada)')
        ->addMultiOptions(Application_Model_Patologia::listarPares());
        $this->addElement($ePatologia);

        $eMedicamento = new Zend_Form_Element_Select(self::E_MEDICAMENTO);
        $eMedicamento->setLabel('Medicamento: ')->setRequired()
        ->addMultiOption('', '(Seleccione el medicamento)')
        ->addMultiOptions(Application_Model_Medicamento::listarPares());
        $this->addElement($eMedicamento);

        $eDosis = new Zend_Form_Element_Select(self::E_DOSIS);
        $eDosis->setLabel('Dósis diaria: ')->setRequired()                
        ->addMultiOption('1', '1')
        ->addMultiOption('2', '2')
        ->addMultiOption('3', '3')
        ->addMultiOption('4', '4')
        ->addMultiOption('5', '5')
        ->addMultiOption('6', '6')
        ->addMultiOption('7', '7')
        ->addMultiOption('8', '8')
        ->addMultiOption('9', '9')
        ->addMultiOption('10', '10')
        ->addMultiOption('11', '11')
        ->addMultiOption('12', '12')
        ->addMultiOption('13', '13')
        ->addMultiOption('14', '14')
        ->addMultiOption('15', '15')
        ->addMultiOption('16', '16')
        ->addMultiOption('17', '17')
        ->addMultiOption('18', '18')
        ->addMultiOption('19', '19')
        ->addMultiOption('21', '21')
        ->addMultiOption('22', '22')
        ->addMultiOption('23', '23')
        ->addMultiOption('24', '24');
        $this->addElement($eDosis);

        $eFrecuenciaTipo = new Zend_Form_Element_Select(self::E_FRECUENCIA_TIPO);
        $eFrecuenciaTipo->setLabel('Tipo de Frecuencia: ')->setRequired()
        ->setAttrib('onchange','mostrarTiempo(this)')           
        ->addMultiOption('1', 'Permanente')
        ->addMultiOption('2','Temporal');
        $this->addElement($eFrecuenciaTipo);

        $eFrecuencia = new Zend_Form_Element_Text(self::E_FRECUENCIA);
        $eFrecuencia->setLabel('Frecuencia: ')              
        ->addValidator('StringLength', false, array('min' => 1, 'max' => 2, 'encoding' => $this->getView()->getEncoding()))
        ->addValidator('Digits', true)
        ->addValidator('Between', true, array('inclusive' => true, 'min' => 1, 'max' => 99999999))
        ->setAttrib('size', '2')
        ->setAttrib('maxlength', '2')
        ->setAttrib('style', 'text-align: center;')
        ->addFilter('StringTrim');
        $this->addElement($eFrecuencia);

        $eFrecuenciaUnidad = new Zend_Form_Element_Select(self::E_FRECUENCIA_UNIDAD);
        $eFrecuenciaUnidad->addMultiOption('DIA(S)','Día(s)')
        ->addMultiOption('SEMANA(S)','Semanas(s)')
        ->addMultiOption('MESES(ES)','Mes(es)');
        $this->addElement($eFrecuenciaUnidad);

        // $eIndicacion = new Zend_Form_Element_Textarea(self::E_INDICACION);
        // $eIndicacion->setLabel('Indicaciones del medicamento')
        // ->setAttrib('style','width: 99%;')
        // ->addValidator('StringLength', false, array('min' => 0, 'max' => 250, 'encoding' => $this->getView()->getEncoding()));
        // $this->addElement($eIndicacion);

        
        $eInforme = new Zend_Form_Element_File(self::E_INFORME);
        $eInforme->setLabel('Subir Informe Medico:')
        ->setDescription('Formatos válidos: pdf.')
        ->setDestination(Fmo_Config::PATH_UPLOAD_FILES)
        ->addValidators(array(
            array('Count', false, 1),
            array('Extension', false, 'pdf'),
            array('Size', false, 512000),
        ));
        $this->addElement($eInforme);

        $eAceptar = new Zend_Form_Element_Submit(self::E_ACEPTAR);
        $eAceptar->setLabel('Guardar')
        ->setIgnore(true);
        $this->addElement($eAceptar);

        $eVolver = new Zend_Form_Element_Submit(self::E_VOLVER);
        $eVolver->setLabel('Salir')
        ->setIgnore(true);
        $this->addElement($eVolver);

        $this->setCustomDecorators();
    }


    /**
     * Método para inicializar los valores por defecto en el formulario
     *
     */
    public function valoresPorDefecto($usuario) {

        // $this->getElement(self::E_PATOLOGIA)
        // ->addMultiOptions(Application_Model_Patologia::listarParesExistente($usuario->cedula));
        //$tDireccion = new Application_Model_DbTable_Direccion();
        //var_dump($servicio->toArray());exit;
        //$this->setDefault(self::E_SERVICIO, $idServicio);

        
        // if ($medimamentos) {
        //     // Zend_Debug::dd($patologias);
        //     $mExistentes = array();
        //     foreach($medimamentos as $medicamento){
        //         $pExistentes[] = $medicamento->id_patologia;
        //     }
        //     //Zend_Debug::dd($pExistentes);
        //     //Zend_Debug::dd(Application_Model_Patologia::listarExistentes($patologia[0]->cedula));
        //     $this->getElement(self::E_MEDICAMENTOS)->setValue($pExistentes);
        //     return true;
        // } else {
        //     return false;
        // }
    }

    /**
     * Método para guardar los datos en la Base de Datos
     * */
    public function guardar($usuario,$usuarioSesion) {

        try{
            Zend_Db_Table::getDefaultAdapter()->beginTransaction();

            //Zend_Debug::dd($this->getValue(self::E_PATOLOGIA));
            $patologia = $this->getValue(self::E_PATOLOGIA);
            $medicamento = $this->getValue(self::E_MEDICAMENTO);
            $dosis = '1 UNIDAD CADA '.$this->getValue(self::E_DOSIS).' HORA(S)';
            $frecuencia = NULL;
            if($this->getValue(self::E_FRECUENCIA_TIPO) == '1'){
                $frecuencia = 'POR SIEMPRE';
            } else {
                $frecuencia = 'POR '.$this->getValue(self::E_FRECUENCIA).' '.$this->getValue(self::E_FRECUENCIA_UNIDAD);
            }

            //Zend_Debug::dd(strtoupper($dosis.' '.$frecuencia));
            //$indicacion = $this->getValue(self::E_INDICACION);

            if(Application_Model_Medicamento::existe($usuario->cedula, $medicamento)){
                return false;
            }

            $fileName = 'INFORME-'.$usuario->cedula.'-'.time();

            //Recibe el archivo en el directorio temporal del servidor
            $upload = new Zend_File_Transfer();
            $upload->receive(); 
            $files = $upload->getFileInfo(); // Zend_Debug::dd($files);
            $idInforme=null;
            foreach ($files as $info) {
                if($info["received"] == TRUE){
                    $rutaTemporal = $info['tmp_name'];

                    $directorio = Fmo_Config::PATH_UPLOAD_FILES . DIRECTORY_SEPARATOR . 'informes'; // Zend_Debug::dd($directorio);

                    //Verifica si el directorio existe
                    if (!file_exists($directorio)) {
                        mkdir($directorio, 0755, true);
                    }

                    $ruta = $directorio. DIRECTORY_SEPARATOR . $fileName . '.pdf';
                    //Se mueve el archivo desde el directorio temporal al directorio del sistema
                    rename($rutaTemporal, $ruta);

                    $adjuntoInforme = new Application_Model_DbTable_AdjuntoInforme();
                    $registro = $adjuntoInforme->createRow();
                    $registro->nombre = $fileName;
                    $registro->ruta_almacenamiento = $ruta;
                    $registro->descripcion = 'INFORME MEDICO';
                    $registro->tamanio = $info['size'];
                    $registro->suma_comprobacion = md5_file($ruta);
                    $registro->tipo_mime = 'application/pdf';
                    $idInforme = $registro->save();
                }
            }

            $tPatologiaMedicamento = new Application_Model_DbTable_TrabajadorMedicamento();
            $newRow = $tPatologiaMedicamento->createRow();
            $newRow->cedula = $usuario->cedula;
            //if($patologia) $newRow->id_patologia = $patologia;
            $newRow->id_patologia = $patologia;
            $newRow->id_medicamento = $medicamento;
            //if($indicacion) $newRow->indicacion = strtoupper($indicacion);
            $newRow->indicacion = strtoupper($dosis.' '.$frecuencia);
            $newRow->dosis = $dosis;
            $newRow->frecuencia = $frecuencia;
            $newRow->anadido_por = $usuarioSesion->siglado;
            $newRow->informe_adjunto_id = $idInforme;
            $id = $newRow->save();

            Zend_Db_Table::getDefaultAdapter()->commit();
            return true;
        } catch (Exception $e) {
            Zend_Db_Table::getDefaultAdapter()->rollBack();
            throw $e;
        }
        
    }

}
