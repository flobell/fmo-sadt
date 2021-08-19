<?php

/**
 * Formulario para agregar y editar cauchos
 *
 * @author Pedro Flores <fmo16554@ferrominera.gob.ve>
 */
class Application_Form_Patologia extends Fmo_Form_Abstract {

    const E_PATOLOGIA = 'patologia';
    const E_ACEPTAR = 'guardar';
    const E_VOLVER = 'volver';

    public function init() {
        $this->setAction($this->getView()->url())
            ->setLegend('Patologias del trabajador')
            ->setEnctype(self::ENCTYPE_MULTIPART);

        $ePatologia = new Zend_Form_Element_MultiCheckbox(self::E_PATOLOGIA);
        $ePatologia->addMultiOptions(Application_Model_Patologia::listarPares());
        $this->addElement($ePatologia);

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
    public function valoresPorDefecto($patologias) {
        //$tDireccion = new Application_Model_DbTable_Direccion();
        //var_dump($servicio->toArray());exit;
        //$this->setDefault(self::E_SERVICIO, $idServicio);

        
        if ($patologias) {
            // Zend_Debug::dd($patologias);
            $pExistentes = array();
            foreach($patologias as $patologia){
                $pExistentes[] = $patologia->id_patologia;
            }
            //Zend_Debug::dd($pExistentes);
            //Zend_Debug::dd(Application_Model_Patologia::listarExistentes($patologia[0]->cedula));
            $this->getElement(self::E_PATOLOGIA)->setValue($pExistentes);
            return true;
        } else {
            return false;
        }
    }


    /**
     * Método para guardar los datos en la Base de Datos
     * */
    public function guardar($usuario,$usuarioSesion) {
        //Zend_Debug::dd($this->getValue(self::E_PATOLOGIA));
        $tPatologiaTrabajador = new Application_Model_DbTable_TrabajadorPatologia();

        $patologias = $this->getValue(self::E_PATOLOGIA);

        $pGuardar = array();
        if($patologias){
            foreach($patologias as $patologia){
                if(!(Application_Model_Patologia::existe($usuario->cedula, $patologia))){
                    $newRow = $tPatologiaTrabajador->createRow();
                    $newRow->cedula = $usuario->cedula;
                    $newRow->id_patologia = $patologia;
                    $newRow->anadido_por = $usuarioSesion->siglado;
                    $newRow->save();
                }
            }
    
            foreach($patologias as $patologia){
                $pGuardar[] = $patologia;
            }
            
            $where = $tPatologiaTrabajador->getAdapter()->quoteInto("cedula = $usuario->cedula AND id_patologia NOT IN (?)", $pGuardar);
        } else {
            $where = $tPatologiaTrabajador->getAdapter()->quoteInto("cedula = ?",$usuario->cedula);
        }

        $tPatologiaTrabajador->delete($where);
        // $where = $tTrabajadorPatologia->getAdapter()->quoteInto("cedula = $usuario->cedula AND id NOT IN (?)",$patologias);
        // $tPatologiaTrabajador->delete($where);
        
        

        $guardado = true;

        //Zend_Debug::dd($respuesta);
        return $guardado;
    }

}
