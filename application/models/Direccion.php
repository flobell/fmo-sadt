<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Personal
 *
 * @author Felix Patete - F12816 <felixpga@ferrominera.com>
 */
class Application_Model_Direccion {

    public static function buscar($cedula) {
        /*
          select a.id_zona as zona, b.id as parroquia,c.id as ciudad
          from direccion as a
          join zona as b on a.id_zona = b.id
          join zona as c on b.id_zona = c.id
         */
        $tDireccion = new Application_Model_DbTable_Direccion();
        $tZona = new Application_Model_DbTable_Zona();
        $tReferencia = new Application_Model_DbTable_Referencia();
        $tArchivo = new Application_Model_DbTable_Adjunto();
        $tLocalidad = new Application_Model_DbTable_Localidad();

        $sel = $tDireccion->select()->setIntegrityCheck(false)
                ->from(
                    array('a' => $tDireccion->info(Zend_Db_Table::NAME)), 
                    array(
                        'estado' => 'e.id_zona', 
                        'municipio' => 'c.id_zona', 
                        'nombre_parroquia' => 'c.nombre', 
                        'parroquia' => 'b.id_zona', 
                        'nombre_zona' => 'b.nombre', 
                        'nombre_municipio' => 'e.nombre', 
                        'a.*', 
                        'archivo' => 'd.nombre', 
                        'referencia' => 'f.descripcion', 
                        'desc_localidad' => 'l.descripcion'
                    ),
                    $tDireccion->info(Zend_Db_Table::SCHEMA)
                )
                ->join(array('b' => $tZona->info(Zend_Db_Table::NAME)), 'a.id_zona = b.id', null, $tZona->info(Zend_Db_Table::SCHEMA))
                ->join(array('c' => $tZona->info(Zend_Db_Table::NAME)), 'b.id_zona = c.id', null, $tZona->info(Zend_Db_Table::SCHEMA))
                ->join(array('e' => $tZona->info(Zend_Db_Table::NAME)), 'e.id = c.id_zona', null, $tZona->info(Zend_Db_Table::SCHEMA))
                ->joinLeft(array('l' => $tLocalidad->info(Zend_Db_Table::NAME)), 'a.localidad = l.id', null, $tLocalidad->info(Zend_Db_Table::SCHEMA))
                ->joinLeft(array('f' => $tReferencia->info(Zend_Db_Table::NAME)), 'a.id_referencia = f.id', null, $tReferencia->info(Zend_Db_Table::SCHEMA))
                ->joinLeft(array('d' => $tArchivo->info(Zend_Db_Table::NAME)), 'a.cedula = d.id', null, $tArchivo->info(Zend_Db_Table::SCHEMA))
                ->where('cedula = ? ', $cedula);
        //Zend_Debug::dd($sel->__toString());
        $resultado = $tDireccion->getAdapter()->fetchAll($sel);


        if ($resultado) {
            return $resultado[0];
        }
    }

    public static function listarTrabajadores($zona = '', $referencia = '',$gerencia = '', $ruta='', $localidad='') {
        $tZona = new Application_Model_DbTable_Zona();
        $tDireccion = new Application_Model_DbTable_Direccion();
        $tFerrominero = new Fmo_DbTable_Rpsdatos_DatoBasico();
        $tReferencia = new Application_Model_DbTable_Referencia();
        $tLocalidad = new Application_Model_DbTable_Localidad();
        //$tRutaTrab = new Application_Model_DbTable_Ruta();
        $tArchivo = new Application_Model_DbTable_Adjunto();
        $sqlCteA = $tZona->select()
                ->setIntegrityCheck(FALSE)
                ->from(array($tZona->info(Zend_Db_Table::NAME)), array(
                    "id",
                    "id_zona",
                    "nombre",
                    "nivel",
                    "ruta" => new Zend_Db_Expr("array[id]"),
                    "ruta_string" => new Zend_Db_Expr("nombre::text")
                        ), $tZona->info(Zend_Db_Table::SCHEMA))
                ->where("id_zona IS NULL");
        
        $sqlCteB = $tZona->select()
                ->setIntegrityCheck(FALSE)
                ->from(array("hijo" => $tZona->info(Zend_Db_Table::NAME)), array(
                    "hijo.id",
                    "hijo.id_zona",
                    "nombre",
                    "hijo.nivel",
                    "ruta" => new Zend_Db_Expr("ruta || hijo.id"),
                    "ruta_string" => new Zend_Db_Expr("ruta_string || ' > ' || hijo.nombre::text")
                        ), $tZona->info(Zend_Db_Table::SCHEMA))
                ->join(array("padre" => "descendientes"), 'padre.id = hijo.id_zona', array());

        $sqlCteC = $tZona->select()
                ->union(array($sqlCteA, $sqlCteB));

        $sel = $tDireccion->select()->setIntegrityCheck(false)
                ->from(array('a' => $tDireccion->info(Zend_Db_Table::NAME)), array('a.id_zona', 'nombre' => 'b.datb_nombre', 'apellido' => 'b.datb_apellid', 'ficha' => 'b.datb_nrotrab', 'cedula' => 'b.datb_cedula', 'gerencia' => 'c.ceco_descri', 'zona' => 'e.nombre', 'referencia' => 'f.descripcion', 'archivo' => 'd.nombre', 'desc_localidad' => 'l.descripcion'), $tDireccion->info(Zend_Db_Table::SCHEMA))
                ->join(array('b' => $tFerrominero->info(Zend_Db_Table::NAME)), 'a.cedula = b.datb_cedula', null, $tFerrominero->info(Zend_Db_Table::SCHEMA))
                ->join(array('c' => 'sn_tcecos'), 'SUBSTRING(b.datb_ceco FROM 1 FOR 2) = c.ceco_ceco', null, 'sch_rpsdatos')
                ->joinLeft(array('d' => $tArchivo->info(Zend_Db_Table::NAME)), 'a.cedula = d.id', null, $tArchivo->info(Zend_Db_Table::SCHEMA))
                ->joinLeft(array('l' => $tLocalidad->info(Zend_Db_Table::NAME)), 'a.localidad = l.id', null, $tLocalidad->info(Zend_Db_Table::SCHEMA))
                ->join(array('e' => $tZona->info(Zend_Db_Table::NAME)), 'a.id_zona = e.id', null, $tZona->info(Zend_Db_Table::SCHEMA))
                ->joinLeft(array('f' => $tReferencia->info(Zend_Db_Table::NAME)), 'a.id_referencia = f.id', null, $tReferencia->info(Zend_Db_Table::SCHEMA))
                //->joinLeft(array('g' => $tRuta->info(Zend_Db_Table::NAME)), 'b.datb_cedula = f.id', null, $tReferencia->info(Zend_Db_Table::SCHEMA))
                ;
        if ($localidad != '') {
            $sel->where('a.localidad = ?', $localidad);
        }        
        if ($zona != '') {
            $sel->where('a.id_zona in (?)', new Zend_Db_Expr("(select id from descendientes where ruta @> array[$zona])"));
        }
        if ($referencia != '') {
            $sel->where('a.id_referencia = ?', $referencia);
        }
        if ($ruta != '') {
            $sel->where('a.id_referencia = ?', $ruta);
        }
        if ($gerencia != '') {
            $sel->where('SUBSTRING(b.datb_ceco FROM 1 FOR 2) = ? ', $gerencia);
        }



        $sqlCte = 'WITH RECURSIVE descendientes AS ('
                . $sqlCteC->__toString()
                . ')'
                . $sel->__toString();

        //var_dump($sqlCte);
//Zend_Debug::dd($localidad);
        return $tDireccion->getAdapter()->fetchAll($sqlCte);
    }
    
    public static function listarTrabajadoresRutas($zona = null, $horario=null, $ruta=null, $gerencia=null, $tpno=null) {
        
        $tDireccion = new Application_Model_DbTable_Direccion();
        $tReferencia = new Application_Model_DbTable_Referencia();
        $tRefeZona= new Application_Model_DbTable_RefeZona();
        $tRecorrido = new Application_Model_DbTable_Recorrido();
        $tRuta = new Application_Model_DbTable_Ruta();
        $tZona = new Application_Model_DbTable_Zona();
        $tFerrominero = new Fmo_DbTable_Rpsdatos_DatoBasico();
        
        $sqlCteA = $tZona->select()
                ->setIntegrityCheck(FALSE)
                ->from(array($tZona->info(Zend_Db_Table::NAME)), array(
                    "id",
                    "id_zona",
                    "nombre",
                    "nivel",
                    "ruta" => new Zend_Db_Expr("array[id]"),
                    "ruta_string" => new Zend_Db_Expr("nombre::text")
                        ), $tZona->info(Zend_Db_Table::SCHEMA))
                ->where("id_zona IS NULL");

        $sqlCteB = $tZona->select()
                ->setIntegrityCheck(FALSE)
                ->from(array("hijo" => $tZona->info(Zend_Db_Table::NAME)), array(
                    "hijo.id",
                    "hijo.id_zona",
                    "nombre",
                    "hijo.nivel",
                    "ruta" => new Zend_Db_Expr("ruta || hijo.id"),
                    "ruta_string" => new Zend_Db_Expr("ruta_string || ' > ' || hijo.nombre::text")
                        ), $tZona->info(Zend_Db_Table::SCHEMA))
                ->join(array("padre" => "descendientes"), 'padre.id = hijo.id_zona', array());

        $sqlCteC = $tZona->select()
                ->union(array($sqlCteA, $sqlCteB));

        $select = $tDireccion->select()->setIntegrityCheck(false)                                                           
                ->from(array('a' => $tDireccion->info(Zend_Db_Table::NAME)), array('cedula' =>'a.cedula', 'pto_ref' => 'b.descripcion', 'posibles_rutas' => new Zend_Db_Expr("string_agg(e.descripcion, ', ')"), 'desc_parroquia' => 'f.nombre', 'desc_localidad' =>  'g.nombre', 'nombre' => 'h.datb_nombre','apellido' => 'h.datb_apellid', 'ficha'  => 'h.datb_nrotrab', 'gerencia' => 'i.ceco_descri', 'tpno' => 'j.tpno_descri'), $tDireccion->info(Zend_Db_Table::SCHEMA))
                ->join(array('b'=>$tReferencia->info(Zend_Db_Table::NAME)), 'a.id_referencia=b.id', null, $tReferencia->info(Zend_Db_Table::SCHEMA))
                ->join(array('c'=>$tRefeZona->info(Zend_Db_Table::NAME)), 'b.id=c.id_referencia', null, $tRefeZona->info(Zend_Db_Table::SCHEMA))
                ->join(array('d'=>$tRecorrido->info(Zend_Db_Table::NAME)), 'c.id= d.id_refezona', null, $tRecorrido->info(Zend_Db_Table::SCHEMA))
                ->join(array('e'=>$tRuta->info(Zend_Db_Table::NAME)), 'd.id_ruta=e.id', null, $tRuta->info(Zend_Db_Table::SCHEMA))
                ->join(array('f'=>$tZona->info(Zend_Db_Table::NAME)), 'c.id_zona= f.id', null, $tZona->info(Zend_Db_Table::SCHEMA))
                ->join(array('g'=>$tZona->info(Zend_Db_Table::NAME)), 'a.id_zona= g.id', null, $tZona->info(Zend_Db_Table::SCHEMA))
                ->join(array('h'=>$tFerrominero->info(Zend_Db_Table::NAME)), 'h.datb_cedula= a.cedula', null, $tFerrominero->info(Zend_Db_Table::SCHEMA))
                ->join(array('i'=>'sn_tcecos'), 'i.ceco_ceco = substring(h.datb_ceco,1,2)', null, 'sch_rpsdatos')
                ->join(array('j'=>'sn_ttpno'), 'j.tpno_tpno = h.datb_tpno', null, 'sch_rpsdatos')
                ->group('a.cedula')
                ->group('b.descripcion')
                ->group('f.nombre')
                ->group('g.nombre')
                ->group('h.datb_nombre')
                ->group('h.datb_apellid')
                ->group('h.datb_nrotrab')
                ->group('i.ceco_descri')
                ->group('j.tpno_descri');

        if (!empty($zona)) {
            $select->where('f.id in (?)', new Zend_Db_Expr("(select id from descendientes where ruta @> array[$zona])"));
        }
        
        if (!empty($horario)){
            $select->where('e.id_horario = (?)', $horario);
        }
        
        if (!empty($ruta)){
            $select->where('f.id = (?)', $ruta);
        }
        
        if (!empty($gerencia)){
            $select->where('i.ceco_ceco = (?)', $gerencia);
        }
        
        if (!empty($tpno)){
            $select->where('j.tpno_tpno = (?)', $tpno);
        }
        
        $sqlCte = 'WITH RECURSIVE descendientes AS ('
                . $sqlCteC->__toString()
                . ')'
                . $select->__toString();

//        print_r ($sqlCte);
//        exit;
        //var_dump($sqlCte);

        return $tDireccion->getAdapter()->fetchAll($sqlCte);
    }
    
    public static function listarGerencias(){
        
        $tDireccion = new Application_Model_DbTable_Direccion();
        $tFerrominero = new Fmo_DbTable_Rpsdatos_DatoBasico();
        
        $select= $tDireccion->select()->setIntegrityCheck(false)             
                ->from(array('a' => $tDireccion->info(Zend_Db_Table::NAME)), array('distinct(c.ceco_ceco)', 'c.ceco_descri'), $tDireccion->info(Zend_Db_Table::SCHEMA))
                ->join(array('b' =>$tFerrominero->info(Zend_Db_Table::NAME)), 'b.datb_cedula= a.cedula', null, $tFerrominero->info(Zend_Db_Table::SCHEMA))
                ->join(array('c' =>'sn_tcecos'), 'c.ceco_ceco=substring(b.datb_ceco,1,2)', null, 'sch_rpsdatos')
                ->join(array('d' =>'sn_tcecos'), 'substring(c.ceco_ceco,1,2) = d.ceco_ceco', null, 'sch_rpsdatos')
                ->where('c.ceco_status = (?)', 'A')
                ->order('c.ceco_descri desc');
        $datos = $tDireccion->getAdapter()->fetchPairs($select);
                //var_dump($datos);
        return $tDireccion->getAdapter()->fetchPairs($select);
    }
    
    public static function listarGerencias2(){
        
        $tDireccion = new Application_Model_DbTable_Direccion();
        $tFerrominero = new Fmo_DbTable_Rpsdatos_DatoBasico();
        
        $select= $tDireccion->select()->setIntegrityCheck(false)             
                ->from(array('a' => $tDireccion->info(Zend_Db_Table::NAME)), array('distinct(c.ceco_ceco)', 'c.ceco_descri'), $tDireccion->info(Zend_Db_Table::SCHEMA))
                ->join(array('b' =>$tFerrominero->info(Zend_Db_Table::NAME)), 'b.datb_cedula= a.cedula', null, $tFerrominero->info(Zend_Db_Table::SCHEMA))
                ->join(array('c' =>'sn_tcecos'), 'c.ceco_ceco=substring(b.datb_ceco,1,2)', null, 'sch_rpsdatos')
                ->join(array('d' =>'sn_tcecos'), 'substring(c.ceco_ceco,1,2) = d.ceco_ceco', null, 'sch_rpsdatos')
                ->where('c.ceco_status = (?)', 'A')
                ->order('c.ceco_descri desc');
        $datos = $tDireccion->getAdapter()->fetchPairs($select);
                //var_dump($datos);
        return $tDireccion->getAdapter()->fetchPairs($select);
    }
    
    public static function listarTpno(){
        
//        select distinct(c.tpno_tpno), c.tpno_descri
//        from direccion a
//        join sch_rpsdatos.sn_tdatbas b on b.datb_cedula= a.cedula
//        join sch_rpsdatos.sn_ttpno c on c.tpno_tpno=b.datb_tpno
//        order by c.tpno_descri desc
        $tDireccion = new Application_Model_DbTable_Direccion();
        $tFerrominero = new Fmo_DbTable_Rpsdatos_DatoBasico();
        
        $select= $tDireccion->select()->setIntegrityCheck(false)             
                ->from(array('a' => $tDireccion->info(Zend_Db_Table::NAME)), array('distinct(c.tpno_tpno)', 'c.tpno_descri'), $tDireccion->info(Zend_Db_Table::SCHEMA))
                ->join(array('b' =>$tFerrominero->info(Zend_Db_Table::NAME)), 'b.datb_cedula= a.cedula', null, $tFerrominero->info(Zend_Db_Table::SCHEMA))
                ->join(array('c' =>'sn_ttpno'), 'c.tpno_tpno=b.datb_tpno', null, 'sch_rpsdatos')
                ->order('c.tpno_descri desc');
        
        return $tDireccion->getAdapter()->fetchPairs($select);
        
        
    }
    
    
/*
    public static function buscarTrabajador($id) {
        if ($this->trabajadoresAct($id)) {
            $persona = Fmo_Model_Personal::findOneByFicha($id);
            if (!$persona) {
                return Fmo_Model_Personal::findOneByCedula($id);
            } else {
                return $persona;
            }
        }
    }*/

    public static function trabajadoresActivos($id) {
        /*  select * 
          from sch_rpsdatos.rh_tdanom as a
          where a.datb_activi = 1 and datb_carg not ilike '6150%'
          and datb_nrotrab = 'id' or datb_cedula = 'id'; */
        $tFerrominero = new Fmo_DbTable_Rpsdatos_DatoBasico();
        $select = $tFerrominero->select()
                ->from(array('a' => $tFerrominero->info(Zend_Db_Table::NAME)), array('cedula' => 'a.datb_cedula'), $tFerrominero->info(Zend_Db_Table::SCHEMA))
                ->where('datb_activi <> 9 ')
                ->where('datb_carg not like ? ', '6150%')
                ->where('(datb_nrotrab = ? ', $id)
                ->orWhere('datb_cedula = ? )', $id);
        
        return $tFerrominero->getAdapter()->fetchRow($select);
    }

}
