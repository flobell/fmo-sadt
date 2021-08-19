<?php

/**
 * Formulario para agregar y editar cauchos
 *
 * @author enocc
 */
class Application_Form_Crear extends Fmo_Form_Abstract {

    //Elementos del formulario
    
    const E_GUARDAR= 'guardar';
    const E_VOLVER = 'volver';
    const E_RUTA= 'ruta';
    const E_HORARIO= 'horario';
    const E_TIEMPO = 'tiempo';
    const E_DISTANCIA = 'distancia';

    public function init() {
        $this->setAction($this->getView()->url())
                ->setLegend('Registro de Servicios')
                ;//->setEnctype(self::ENCTYPE_MULTIPART);
        

        
        $eHorario = new Zend_Form_Element_Select(self::E_HORARIO);
        $eHorario->setLabel('Horario:')
                ->setRequired()
                ->setAttrib('onchange', 'this.form.submit();')
                ->addMultiOption('', '(Seleccione)')->addMultiOptions(Application_Model_Horario::getPairs());
        $this->addElement($eHorario);
        
        $eRuta = new Zend_Form_Element_Text(self::E_RUTA);
        $eRuta->setLabel('Descripción de la Ruta')
                ->setAttrib('maxlength', 15);
        $this->addElement($eRuta);
        
        $eTiempo = new Zend_Form_Element_Text(self::E_TIEMPO);
        $eTiempo->setLabel('Tiempo en Min.')
                ->setAttrib('maxlength', 3);
        $this->addElement($eTiempo);
        
        $eDistancia = new Zend_Form_Element_Text(self::E_DISTANCIA);
        $eDistancia->setLabel('Distancia en Km.')
                ->addValidator('Digits', true)
                ->setAttrib('maxlength', 5);
        $this->addElement($eDistancia);


        //****************************************
        //********************BOTONES*************
        //**************************************** 

        $eleA = new Zend_Form_Element_Submit(self::E_GUARDAR);
        $eleA->setLabel('GUARDAR')
                ->setIgnore(true);
        $this->addElement($eleA);

        $eleC = new Zend_Form_Element_Submit(self::E_VOLVER);
        $eleC->setLabel('Guardar')
                ->setIgnore(true);
        $this->addElement($eleC);

        $this->setCustomDecorators();
    }
    
    
    public function guardar() {
        try{
            if ($this->getValue(self::E_HORARIO) && $this->getValue(self::E_RUTA)){
                

                $tRuta = new Application_Model_DbTable_Ruta();

                $registro = $tRuta->createRow();


                $registro->descripcion = $this->getValue(self::E_RUTA);
                $registro->id_horario = $this->getValue(self::E_HORARIO);
                $registro->tiempo = $this->getValue(self::E_TIEMPO);
                $registro->distancia = $this->getValue(self::E_DISTANCIA);
                return $registro->save();
            }else {
                echo "error";
                throw new Exception("Debe seleccionar el Horario.", -1);
            }

        
        
        
        }catch (Exception $ex){
            switch ($ex->getCode()){
                case -1:{
                    throw new Exception($ex->getMessage());
                }break;

                default :{
                    throw new Exception("No se logró agregar la Ruta.");
                }break;
            }
        }
       
    }


}