<?php

/**
 * Formulario para agregar y editar cauchos
 *
 * @author felixpga
 */
class Application_Form_Zona extends Fmo_Form_Abstract {

    //Elementos del formulario
    const E_PADRE = 'padre';
    const E_NOMBRE = 'nombre';
    const E_ACEPTAR = 'guardar';
    const E_VOLVER = 'volver';

    public function init() {
        $this->setAction($this->getView()->url())
                ->setLegend('Registro de Servicios')
                ->setEnctype(self::ENCTYPE_MULTIPART);

        $ePadre = new Zend_Form_Element_Select(self::E_PADRE);
        $ePadre->setLabel('Estado:');
        //->setRequired();
        $this->addElement($ePadre);

        $eNombre = new Zend_Form_Element_Text(self::E_NOMBRE);
        $eNombre->setLabel('Nombre:')
                ->setRequired()
                ->setAttrib('size', '50')
                ->setAttrib('maxlength', '50')
                ->addFilter('StringTrim');
        $this->addElement($eNombre);

        //****************************************
        //********************BOTONES*************
        //**************************************** 

        $eleA = new Zend_Form_Element_Submit(self::E_ACEPTAR);
        $eleA->setLabel('Guardar')
                ->setIgnore(true);
        $this->addElement($eleA);

        $eleC = new Zend_Form_Element_Submit(self::E_VOLVER);
        $eleC->setLabel('Volver')
                ->setIgnore(true);
        $this->addElement($eleC);

        $this->setCustomDecorators();
    }

    /**
     * Método para inicializar los valores por defecto en el formulario
     *
     */
    public function valoresPorDefecto($id, $accion = 'editar') {
        $zona = Application_Model_Zona::getZona($id);
        if ($zona) {
            $nivel = $zona->nivel;
            if ($accion == 'editar') {
                $nivel--;
            }
            $tNivel = Application_Model_Nivel::getNivel($nivel);

            $this->getElement(self::E_PADRE)
                    ->setLabel($tNivel->nombre);
            if ($accion == 'editar') {
                //echo 'este es editar';
                $this->getElement(self::E_PADRE)
                        ->addMultiOptions(Application_Model_Zona::listarPares("nivel = $tNivel->id ", 1));
                $this->setDefault(self::E_PADRE, $zona->id_zona)
                        ->setDefault(self::E_NOMBRE, $zona->nombre);
            } else {
                //echo 'este es nuevo';
                $this->getElement(self::E_PADRE)
                        ->addMultiOptions(Application_Model_Zona::listarPares("nivel = $tNivel->id", 1));
                $this->setDefault(self::E_PADRE, $zona->id);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Método para guardar los datos en la Base de Datos
     * */
    public function guardar($id) {
        $tZona = new Application_Model_DbTable_Zona();
        $registro = $tZona->createRow();
       // $next = Application_Model_Zona::getMax($id);
        /*if ($next) {
            //var_dump($id . '-' . $next->valor, $next->nivel);
            $registro->id = $id . '-' . $next->valor;
            $registro->nivel = $next->nivel;
        } else {
            $registro->id = $id . '-1';
         */  
            $padre = Application_Model_DbTable_Zona::findOneById($id);
            $registro->nivel = $padre->nivel + 1;
        //}
        $registro->id_zona = $id;
        $registro->nombre = $this->getValue(self::E_NOMBRE);

        return $registro->save();
    }

    /**
     * Método para guardar los datos en la Base de Datos
     * */
    public function guardarCambios($id) {
        $registro = Application_Model_DbTable_Zona::findOneById($id);
        if ($this->getValue(self::E_PADRE) != $registro->id_zona) {
            //aqui debe cambiar el padre, el codigo el de todos los hijos
        }
        $registro->id_zona = $this->getValue(self::E_PADRE);
        $registro->nombre = $this->getValue(self::E_NOMBRE);
        return $registro->save();
    }

    public function setDefaults(array $defaults) {
        $municipio = $defaults[self::E_MUNICIPIO];
        $parroquia = $defaults[self::E_PARROQUIA];
        //echo'<br/><br/> municipio',  var_dump($municipio);
        if ($municipio) {
            /* ->getElement(self::E_CIUDAD)
              ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$municipio'"));
              if ($ciudad != '' && ($municipio == substr($ciudad, 0, strlen($municipio)))) {
             */$this->getElement(self::E_PARROQUIA)
                    ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$ciudad'"));

            if ($parroquia != ''/* && ($ciudad == substr($parroquia, 0, strlen($ciudad))) */) {
                $this->getElement(self::E_ZONA)
                        ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$parroquia'"));
            }
            //}
        }

        $tipo = $defaults[self::E_TIPO];
        if ($tipo == '2') {
            $this->getElement(self::E_EDIFICIO)->setValue('')->setAttrib('disabled', 'disabled')->setRequired(false)->setValidators(array());
            $this->getElement(self::E_PISO)->setValue('')->setAttrib('disabled', 'disabled')->setRequired(false)->setValidators(array());
        }
        parent::setDefaults($defaults);
    }

    public function recargar(array $defaults) {
        $municipio = $defaults[self::E_MUNICIPIO];
        //$ciudad = $defaults[self::E_CIUDAD];
        $parroquia = $defaults[self::E_PARROQUIA];
        //echo'<br/><br/> municipio',  var_dump($municipio);
        if ($municipio) {
            $this->getElement(self::E_CIUDAD)
                    ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$municipio'"));
            if ($ciudad != '' && ($municipio == substr($ciudad, 0, strlen($municipio)))) {
                $this->getElement(self::E_PARROQUIA)
                        ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$ciudad'"));

                if ($parroquia != '' && ($ciudad == substr($parroquia, 0, strlen($ciudad)))) {
                    $this->getElement(self::E_ZONA)
                            ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null, null, "id_zona = '$parroquia'"));
                }
            }
        }
        parent::setDefaults($defaults);
    }

}
