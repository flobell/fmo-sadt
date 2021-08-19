<?php

/**
 * Formulario para asignar referencias a parroquias
 *
 * @author eucarisg
 */
class Application_Form_Asignar extends Fmo_Form_Abstract {

    //Elementos del formulario
    
    const E_GUARDAR= 'guardar';
    const E_VOLVER = 'volver';    
    const E_REFERENCIA = 'id_referencia';
    const E_PARROQUIA = 'id_zona';    

    public function init() {
        $this->setAction($this->getView()->url())
                ->setLegend('Asignar Referencias a Parroquias')
                ;       
        
        $eReferencia = new Zend_Form_Element_Select(self::E_REFERENCIA);
        $eReferencia->setLabel('Referencia:')
                ->setRequired()
                ->setAttrib('onchange', 'this.form.submit();')
                ->addMultiOption('', '(Seleccione)')
                ->addMultiOptions(Application_Model_Referencia::getReferencias());
        $this->addElement($eReferencia); 
        
        $eParroquia = new Zend_Form_Element_Select(self::E_PARROQUIA);
        $eParroquia->setLabel('Parroquia:')
                ->setRequired()
                ->setAttrib('onchange', 'this.form.submit();')
                ->addMultiOption('', '(Seleccione)')
                ->addMultiOptions(Application_Model_DbTable_Zona::getPairs(null,null,'nivel = 4'));                        
        $this->addElement($eParroquia);        

        
        //****************************************
        //********************BOTONES*************
        //**************************************** 

        $eleA = new Zend_Form_Element_Submit(self::E_GUARDAR);
        $eleA->setLabel('GUARDAR')
                ->setIgnore(true);
        $this->addElement($eleA);  
        
        $eleC = new Zend_Form_Element_Submit(self::E_VOLVER);
        $eleC->setLabel('Volver')
                ->setIgnore(true);
        $this->addElement($eleC);
               

        $this->setCustomDecorators();
    }
    
    
    /**
     * Método para asignar referencia a parroquia
     * */
    public function guardar() {
        try {
            if ($this->getValue(self::E_REFERENCIA) || $this->getValue(self::E_PARROQUIA)) {

                $tRefezona = new Application_Model_DbTable_RefeZona();
                $registro = $tRefezona->createRow();
                $registro->id_referencia = $this->getValue(self::E_REFERENCIA);
                $registro->id_zona = $this->getValue(self::E_PARROQUIA);
                 //INSERTAR REFEZONA   
//                var_dump($registro->orden);
//                exit;

                return $registro->save();
            } else {
                throw new Exception("Debe seleccionar la referencia y la Parroquia.", -1);
            }
        } catch (Exception $ex) {
            switch ($ex->getCode()) {
                case -1: {
                        throw new Exception($ex->getMessage());
                    }break;

                default : {
                        throw new Exception("No se logró Asignar la Referencia.");
                    }break;
            }
        }
    }


}