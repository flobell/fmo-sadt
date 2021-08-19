<?php

/**
 * Formulario para agregar y editar cauchos
 *
 * @author felixpga
 */
class Application_Form_Trabajador extends Fmo_Form_Abstract {

    //Elementos del formulario
    
    const E_FICHA = 'ficha';
    const E_BUSCAR = 'buscar';
    const E_VOLVER = 'volver';

    public function init() {
        $this->setAction($this->getView()->url())
                ->setLegend('Registro de Servicios')
                ;//->setEnctype(self::ENCTYPE_MULTIPART);
        

        
        $eRuta = new Zend_Form_Element_Text(self::E_FICHA);
        $eRuta->setLabel('C.I. / Ficha')
                ->setRequired()
                ->addValidator('StringLength', false, array('min' => 4, 'max' => 10, 'encoding' => $this->getView()->getEncoding()))
                ->addValidator('Digits', true)
                ->addValidator('Between', true, array('inclusive' => true, 'min' => 1, 'max' => 9999999999))
                ->setAttrib('size', '12')
                ->setAttrib('maxlength', '10')
                ->addFilter('StringTrim');
        $this->addElement($eRuta);


        //****************************************
        //********************BOTONES*************
        //**************************************** 

        $eleA = new Zend_Form_Element_Submit(self::E_BUSCAR);
        $eleA->setLabel('Buscar')
                ->setIgnore(true);
        $this->addElement($eleA);

        $eleC = new Zend_Form_Element_Submit(self::E_VOLVER);
        $eleC->setLabel('Salir')
                ->setIgnore(true);
        $this->addElement($eleC);

        $this->setCustomDecorators();
    }


}