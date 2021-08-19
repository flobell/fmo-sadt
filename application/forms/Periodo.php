<?php

/**
 * Formulario para agregar y editar períodos
 *
 * @author stalins
 */
class Application_Form_Periodo extends Fmo_Form_Abstract {

    //Elementos del formulario
    const E_CREAR = 'btnCrear';
    const E_EDITAR = 'btnEditar';
    const E_VOLVER = 'btnVolver';
    const ID_PERIODO     = Application_Model_Periodo::ID;
    const E_FECHA_INICIO = 'txtFechaInicio';
    const E_FECHA_FIN = 'txtFechaFin';
    const E_ESTADO = 'estado';
    const ID_ANTERIOR = "txtIdAnterior";

    //Evento para la creación del formulario Crear|Editar
    private $_evento;
    private $_idPeriodo;

    /*
      public function __construct($crear, $idPeriodo=NULL)
      {
      if($crear){
      $this->_evento = "Crear";
      }else{
      $this->_evento = "Editar";
      }

      if($idPeriodo){
      $this->_idPeriodo = $idPeriodo;
      }

      parent::__construct(null);
      } */

    public function init() {
        // Inicializando formulario
        $this->setAction($this->getView()->url())
                ->setLegend("$this->_evento Período")
                ->setMethod(self::METHOD_POST);

        $txtFechaInicio = new Fmo_Form_Element_DatePicker(self::E_FECHA_INICIO);
        $txtFechaInicio->setLabel("Fecha de inicio: ")
                ->setAttrib("placeholder", "DD/MM/AAAA")
                ->setRequired();
        $this->addElement($txtFechaInicio);

        $txtFechaFin = new Fmo_Form_Element_DatePicker(self::E_FECHA_FIN);
        $txtFechaFin->setLabel("Fecha de Finalización: ")
                ->setAttrib("placeholder", "DD/MM/AAAA")
                ->setRequired();
        $this->addElement($txtFechaFin);
        
        $eEstatus = new Zend_Form_Element_Select(self::E_ESTADO);
        $eEstatus->setLabel('Estado:')
                ->addMultiOptions(array('1' => 'Abierto', '0' => 'Cerrado'));
        $this->addElement($eEstatus);
        
        $btnCrear = new Zend_Form_Element_Submit(self::E_CREAR, array('class' => 'submit'));
        $btnCrear->setLabel("Crear");
        $this->addElement($btnCrear); /*
          if($this->_evento == "Crear"){

          $btnCrear = new Zend_Form_Element_Submit(self::E_CREAR, array('class' => 'submit'));
          $btnCrear->setLabel("Crear");
          $this->addElement($btnCrear);

          }else{
          $txtIdAnterior = new Zend_Form_Element_Hidden(self::ID_ANTERIOR);
          $txtIdAnterior->setValue($this->_idPeriodo);
          $this->addElement($txtIdAnterior);

          $btnEditar = new Zend_Form_Element_Submit(self::E_EDITAR, array('class' => 'submit'));
          $btnEditar->setLabel("Actualizar");
          $this->addElement($btnEditar);

          self::cargarDatos($this->_idPeriodo);
          } */

        // Botón que devuelve al usuario a la pantalla principal
        $btnVolver = new Zend_Form_Element_Submit(self::E_VOLVER, array('class' => 'submit'));
        $btnVolver->setLabel('Volver');
        $this->addElement($btnVolver);

        // Aplicando css de la empresa 
        $this->setCustomDecorators();
    }

    public function crear($params) {
        $fechaInicio = Fmo_Util::stringToZendDate($params[self::E_FECHA_INICIO]);
        $fechaFin = Fmo_Util::stringToZendDate($params[self::E_FECHA_FIN])->addHour(23)->addMinute(59)->addSecond(59);

        if ($fechaFin->compare($fechaInicio->get()) < 0) {
            throw new Exception("La Fecha de Inicio no puede ser superior a la fecha de finalizacion", 1);
        }

        $tblPeriodo = new Application_Model_DbTable_Periodo();

        $fechaActual = Fmo_Util::stringToZendDate(date("d/m/Y"));

        if ($fechaInicio->compare($fechaActual->get()) < 0) {
            throw new Exception("La Fecha de Inicio no puede ser inferior a la fecha actual", 1);
        }

        $periodosAbiertos = $tblPeriodo->fetchAll("estatus = 1 and ((fecha_fin >= '$fechaInicio' and fecha_inicio <='$fechaInicio')"
                . "                                            or (fecha_fin >= '$fechaFin' and fecha_inicio <='$fechaFin'))");

        if (count($periodosAbiertos)) {
            throw new Exception("No pueden haber dos períodos abiertos simultaneamente, por favor cierre uno antes de aperturar el otro.", 1);
        }

        $periodo = $tblPeriodo->createRow();
        //$periodo->id = $params[ self::ID_PERIODO];
        $periodo->fecha_inicio = $fechaInicio->getIso();
        $periodo->fecha_fin = $fechaFin->getIso();
        //$periodo->estado = $params[ self::E_ESTADO];
        $periodo->usuario_creacion = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::CEDULA};
        //$periodo->fecha_creacion = date('Y-m-d H:i:s');

        return $periodo->save();
    }

    public function cargarDatos($idPeriodo) {
        $periodo = Application_Model_DbTable_Periodo::findOneById($idPeriodo);

        $this->setDefault(self::E_FECHA_INICIO, Fmo_Util::stringToZendDate($periodo->fecha_inicio)->get(Zend_Date::DATES));
        $this->setDefault(self::E_FECHA_FIN, Fmo_Util::stringToZendDate($periodo->fecha_fin)->get(Zend_Date::DATES));
        $this->setDefault(self::E_ESTADO, $periodo->estatus);
    }

    public function editar($params, $id) {
        $tblPeriodo = new Application_Model_DbTable_Periodo();

        $fechaInicio = Fmo_Util::stringToZendDate($params[self::E_FECHA_INICIO]);
        $fechaFin = Fmo_Util::stringToZendDate($params[self::E_FECHA_FIN])->addHour(23)->addMinute(59)->addSecond(59);

        if ($fechaFin->compare($fechaInicio->get()) < 0) {
            throw new Exception("La Fecha de Inicio no puede ser superior a la fecha de finalizacion", 1);
        }

        $fechaActual = Fmo_Util::stringToZendDate(date("d/m/Y"));
        $sinmodificar = Application_Model_DbTable_Periodo::findOneById($id);
        $inicio_anterior = Fmo_Util::stringToZendDate($sinmodificar->fecha_inicio);//Fmo_Util::stringToZendDate($sinmodificar->fecha_inicio)->get(Zend_Date::DATES);
        if ($fechaInicio->compare($fechaActual->get()) < 0 && $fechaInicio->compare($inicio_anterior->get())!=0) {
            throw new Exception("La Fecha de Inicio no puede ser inferior a la fecha actual", 1);
        }

        $periodosAbiertos = $tblPeriodo->fetchAll("id <> $id and estatus = 1 and ((fecha_fin >= '$fechaInicio' and fecha_inicio <='$fechaInicio')"
                . "                                                            or (fecha_fin >= '$fechaFin' and fecha_inicio <='$fechaFin'))");

        if (count($periodosAbiertos)) {
            throw new Exception("No pueden haber dos períodos abiertos Simultaneamente, por favor cierre uno antes de aperturar el otro.", 1);
        }

        Zend_Db_Table::getDefaultAdapter()->beginTransaction();
        try {
            /* $tblHistorial = new Application_Model_DbTable_HistorialPeriodo();
            $historial = $tblHistorial->createRow();
              /*
              $historial->id_periodo = $periodo->id;
              $historial->fecha_inicio = $periodo->fecha_inicio;
              $historial->fecha_fin = $periodo->fecha_fin;
              $historial->estado = $periodo->estado;
              $historial->usuario_modificacion = Fmo_Model_Seguridad::getUsuarioSesion()->{Fmo_Model_Personal::CEDULA};
              $historial->fecha_modificacion = date('Y-m-d H:i:s');
              $historial->save();
             */
            $periodo = Application_Model_DbTable_Periodo::findOneById($id);
            $periodo->fecha_inicio = $fechaInicio->getIso();
            $periodo->fecha_fin = $fechaFin->getIso();
            $periodo->estatus = $this->getValue(self::E_ESTADO);
            $periodo->save();

            Zend_Db_Table::getDefaultAdapter()->commit();

            return $id;
        } catch (Exception $e) {
            Zend_Db_Table::getDefaultAdapter()->rollBack();
            throw $e;
        }
    }

    public function getIdPeriodo() {
        return $this->_idPeriodo;
    }

}
