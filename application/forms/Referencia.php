<?php

/**
 * Formulario para agregar referencias
 *
 * @author eucarisg
 */
class Application_Form_Referencia extends Fmo_Form_Abstract {

    //Elementos del formulario
    
    const E_GUARDAR= 'guardar';
    const E_VOLVER = 'volver';
    const E_REFERENCIA = 'descripcion';

    public function init() {
        $this->setAction($this->getView()->url())
                ->setLegend('Registro de Referencias')
                ;
      
        
        $eReferencia = new Zend_Form_Element_Text(self::E_REFERENCIA);
        $eReferencia->setLabel('Referencia')
                ->setRequired()
                ->setAttrib('maxlength', 100);
        $this->addElement($eReferencia);


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
    
    
    public function guardar() {
        try{
           
            $tReferencia = new Application_Model_DbTable_Referencia();
            $registro = $tReferencia->createRow();
            $registro->descripcion = $this->getValue(self::E_REFERENCIA);
               
            return $registro->save();
  
           }catch (Exception $ex){

        }
       
    }


}