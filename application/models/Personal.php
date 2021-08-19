<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Personal
 *
 * @author Felix Patete - F8741 <felixpga@ferrominera.com>
 */
class Application_Model_Personal extends Fmo_Model_Personal {

    const ID = 'id';

    /**
     * Variable para almacenar las cédula consultadas.
     * 
     * @var array
     */
    private static $cache = array();
    public static function listarGerenciasActivas($gerencia = '') {
        // objeto de tabla generica
        $tDatosBasicos = new Fmo_DbTable_Rpsdatos_DatoBasico();
        $tCentroCosto = new Fmo_DbTable_Rpsdatos_CentroCosto();
        $existe = $tDatosBasicos->select()
                                          ->from(array('a' => $tDatosBasicos->info(Zend_Db_Table::NAME)), new Zend_Db_Expr(1), $tDatosBasicos->info(Zend_Db_Table::SCHEMA))
                                          ->where('a.datb_activi <> ?', 9)
                                          ->where('b.ceco_ceco = a.datb_ceco');
        $exp = "string_agg(a.id_extension::text, ', ')";
        if ($gerencia!='') {
            $tipo = 5;
        }else{
            $tipo = 2;
        }
        $sel = $tCentroCosto->select()->distinct()
                ->setIntegrityCheck(false)
                ->from(array('a' => $tCentroCosto->info(Zend_Db_Table::NAME)), array('ceco_ceco'=>'a.ceco_ceco', 'ceco_descri'=>'a.ceco_descri'), $tCentroCosto->info(Zend_Db_Table::SCHEMA))
                ->join(array('b' => $tCentroCosto->info(Zend_Db_Table::NAME)), "a.ceco_ceco = SUBSTRING(b.ceco_ceco FOR $tipo) ", null, $tCentroCosto->info(Zend_Db_Table::SCHEMA))
                ->where('EXISTS (?)', $existe)
                ->order('a.ceco_descri');
        if ($gerencia!='') {
            $sel->where("a.ceco_ceco like ?", $gerencia.'%');
        }
        return $sel->getAdapter()->fetchPairs($sel);
    }
    
    public static function listarExtensionesTrabajador($cedula, $ext = null) {
        // objeto de tabla generica

        $tPersonal = new Application_Model_DbTable_Personal();
        $sel = $tPersonal->select()
                ->setIntegrityCheck(false)
                ->from(array('a' => $tPersonal->info(Zend_Db_Table::NAME)), array('a.*'), $tPersonal->info(Zend_Db_Table::SCHEMA))
                ->where(" a.id_trabajador = ?", $cedula);
        
        if ($ext) {
            $sel->where("a.id_extension = ?", $ext);
        }
        return $sel->getAdapter()->fetchAll($sel);
    }

    public static function buscarExtension($ext, $cedula = null) {
        // objeto de tabla generica

        $tPersonal = new Application_Model_DbTable_Personal();

        $sel = $tPersonal->select()
                ->setIntegrityCheck(false)
                ->from(array('a' => $tPersonal->info(Zend_Db_Table::NAME)), array('a.*'), $tPersonal->info(Zend_Db_Table::SCHEMA))
                ->where(" a.id = ?", $ext);
        //var_dump($sel->__toString());
        return $sel->getAdapter()->fetchAll($sel);
    }

    public static function listarExtensiones($ceco = '', $cedula = '', $nombre = '') {
        // objeto de tabla generica

        $tPersonal = new Application_Model_DbTable_Personal();
        $tCentroCosto = new Fmo_DbTable_Rpsdatos_CentroCosto();
        $tDatosBasicos = new Fmo_DbTable_Rpsdatos_DatoBasico();
        $exp = "string_agg(a.id_extension::text, ', ')";
        $sel = $tPersonal->select()
                ->setIntegrityCheck(false)
                ->from(array('a' => $tPersonal->info(Zend_Db_Table::NAME)), array('ext' => new Zend_Db_Expr($exp), 'a.id_trabajador', 'b.datb_nombre', 'b.datb_apellid', 'b.datb_nrotrab', 'c.ceco_descri', 'c.ceco_ceco', 'c.ceco_descri', 'gerencia' => 'd.ceco_descri'), $tPersonal->info(Zend_Db_Table::SCHEMA))
                ->join(array('b' => $tDatosBasicos->info(Zend_Db_Table::NAME)), "a.id_trabajador = b.datb_cedula ", null, $tDatosBasicos->info(Zend_Db_Table::SCHEMA))
                ->join(array('c' => $tCentroCosto->info(Zend_Db_Table::NAME)), "c.ceco_ceco = b.datb_ceco ", null, $tCentroCosto->info(Zend_Db_Table::SCHEMA))
                ->join(array('d' => $tCentroCosto->info(Zend_Db_Table::NAME)), "d.ceco_ceco = substring(b.datb_ceco from 1 for 2) ", null, $tCentroCosto->info(Zend_Db_Table::SCHEMA))
                ->group(array('id_trabajador', 'datb_nombre', 'datb_apellid', 'datb_nrotrab', 'c.ceco_descri', 'c.ceco_ceco', 'd.ceco_descri'));
        if ($cedula) {
            $sel->where("(a.id_trabajador = ?", $cedula)
                    ->orWhere('b.datb_nrotrab = ?)', $cedula);
        }
        
        if ($ceco!='') {
            $sel->where(" c.ceco_ceco like ?", $ceco . '%');
        }

        if ($nombre) {
                $t2 = str_replace(' ','%',$nombre);
                $t3 = $tPersonal->getAdapter()->quoteInto('public.unaccent(\'public.unaccent\', ?)', '%'.$t2.'%');
                $sel->where("public.unaccent('public.unaccent', concat_ws(' ', b.datb_nombre, b.datb_apellid)) ILIKE $t3");

        }
        //var_dump($sel->__toString());         
        return $sel->getAdapter()->fetchAll($sel);
    }

    /**
     * Método para consultar y devolver el formato texto de una persona por el
     * nombre.
     * 
     * @param integer $cedula CI N° de la persona a consultar
     * @param string $format Código del formato a utilizar para la clase 
     *                       Fmo_DisplayName los código utilizados son los mismos
     * @return boolean $escape Indica si realiza la codifición.
     */
    public static function formatNameByCedula($cedula, $format = Fmo_DisplayName::FORMAT_NET, $escape = true) {
        if (!array_key_exists($cedula, self::$cache)) {
            $tPer = new Fmo_DbTable_Rpsdatos_DatoBasico();


            $select = $tPer->select()
                    ->setIntegrityCheck(false)
                    ->from(array('p' => $tPer->info(Zend_Db_Table::NAME)), array(Fmo_Model_Personal::CEDULA => 'p.datb_cedula',
                        Fmo_Model_Personal::NOMBRE => 'p.datb_nombre',
                        Fmo_Model_Personal::APELLIDO => 'p.datb_apellid',
                        Fmo_Model_Personal::FICHA => 'p.datb_nrotrab'), $tPer->info(Zend_Db_Table::SCHEMA))
                    ->where('p.datb_cedula = ?', $cedula, Zend_Db::INT_TYPE);
            self::$cache[$cedula] = Fmo_DisplayName::display($select->getAdapter()->fetchRow($select), $format, $escape);
        }
        return self::$cache[$cedula];
    }

    public static function getBusquedaPersonas($busqueda) {

        $tFerrominero = new Fmo_DbTable_Rpsdatos_DatoBasico();

        $sel = $tFerrominero->select()
                ->distinct()
                ->setIntegrityCheck(false)
                ->from(array('a' => $tFerrominero->info(Zend_Db_Table::NAME)), array('a.*'), $tFerrominero->info(Zend_Db_Table::SCHEMA))
                ->where(" a.id IN ({$busqueda->getSelect()->__toString()})");

        return $sel;
    }
    
    
    

    /**
     * Devuelve una matriz de pares indicado el código del teléfono y 
     * el número de teléfono.
     * 
     * @param integer $cedula
     * @param integer|false $extension
     * @return array
     */
    public static function getAllTelefonoByCedula($cedula, $extension = false)
    {
        $tDireccion = new Application_Model_DbTable_Direccion();
        $tDatb = new Fmo_DbTable_Rpsdatos_DatoBasico();
        //$tPerm = new Application_Model_DbTable_Permiso();
        //$tFper = new Fmo_DbTable_Rpsdatos_FmoPermiso();
        $tFper = new Application_Model_DbTable_FmoPermiso(); //1.00.002
        $tFggl = new Fmo_DbTable_Rpsdatos_FmoGgral();
        /*
            select a.* 
            from "sch_rpsdatos"."sn_tdatbas" as a 
            join "sch_rpsdatos"."fmo_tpermiso" as b on perm_ceco = datb_ceco::integer
            where b.perm_cedula = 15782860 -- and a.datb_cedula = 12128745 
            and perm_fecini::date<= now()::date and perm_fecfin::date >=now()::date and datb_activi <>9
         */
        $select = $tDireccion->select()
                        ->setIntegrityCheck(false)
                        ->distinct()
                        ->from(array('b' => $tDatb->info(Zend_Db_Table::NAME)), array('b.datb_ceco'), $tDatb->info(Zend_Db_Table::SCHEMA))
                        //->joinLeft(array('p' => $tPers->info(Zend_Db_Table::NAME)), 'p.id_trabajador = b.datb_cedula', null, $tPers->info(Zend_Db_Table::SCHEMA))
                        //->joinLeft(array('perm' => $tPerm->info(Zend_Db_Table::NAME)), 'perm.cedula = b.datb_cedula', null, $tPerm->info(Zend_Db_Table::SCHEMA))
                        ->joinLeft(array('fperm' => $tFper->info(Zend_Db_Table::NAME)), 'fperm.perm_cedula = b.datb_cedula', null, $tFper->info(Zend_Db_Table::SCHEMA))
                        ->where('b.datb_activi <> ?', 9, Zend_Db::INT_TYPE)
                        ->where('b.datb_cedula = ?', $cedula, Zend_Db::INT_TYPE)
                        ->where('EXISTS (?)', $tFper->select()
                                                      ->setIntegrityCheck(false)
                                                      ->from(array('p1' => $tFper->info(Zend_Db_Table::NAME)), new Zend_Db_Expr(1), $tFper->info(Zend_Db_Table::SCHEMA))
                                                      ->join(array('g1' => $tFggl->info(Zend_Db_Table::NAME)), 'g1.ceco_ceco = SUBSTRING(CAST(p1.perm_ceco AS TEXT) FOR 2)', null, $tFggl->info(Zend_Db_Table::SCHEMA))
                                                      ->where("LOCALTIMESTAMP BETWEEN date_trunc('day', p1.perm_fecini) AND date_trunc('day', p1.perm_fecfin)")
                                                      ->where('p1.perm_cedula = ? ', $usuario)
                                                      ->where('(p1.perm_nivel_supervisorio ISNULL')
                                                      ->orWhere('p1.perm_nivel_supervisorio = ?', 0, Zend_Db::INT_TYPE)
                                                      ->orWhere('(p1.perm_nivel_supervisorio = ?', 1, Zend_Db::INT_TYPE)
                                                      ->where('SUBSTRING(b.datb_ceco FOR 2) = g1.ceco_padre)')
                                                      ->orWhere('(p1.perm_nivel_supervisorio = ?', 2, Zend_Db::INT_TYPE)
                                                      ->where('SUBSTRING(b.datb_ceco FOR 2) = g1.ceco_ceco)')
                                                      ->orWhere('(p1.perm_nivel_supervisorio = ?', 3, Zend_Db::INT_TYPE)
                                                      ->where('SUBSTRING(b.datb_ceco FOR 5) = CAST(p1.perm_ceco AS TEXT))')
                                                      ->orWhere('(p1.perm_nivel_supervisorio = ?', 4, Zend_Db::INT_TYPE)
                                                      ->where('b.datb_ceco = CAST(p1.perm_ceco AS TEXT)))'))
                        /*->order(array('id_extension', 'id'))*/;
       /* if ($extension) {
            $select->where('(b.datb_cedula = ?', $extension, Zend_Db::INT_TYPE)
                    ->orWhere('b.datb_nrotrab = ?)', $extension, Zend_Db::INT_TYPE);
        }*/
        //echo '<br/><br/>',var_dump($select->__toString()),'<br/><br/>';
        return $tDireccion->getAdapter()->fetchAll($select);
    }
    
    /**
     * Devuelve una matriz de pares indicado el código del teléfono y 
     * el número de teléfono.
     * 
     * @param integer $cedula
     * @param integer|false $extension
     * @return array
     */
    public static function getAllPermisosByCedula($usuario, $cedula)
    {
        $tDireccion = new Application_Model_DbTable_Direccion();
        $tDatb = new Fmo_DbTable_Rpsdatos_DatoBasico();
        //$tFper = new Fmo_DbTable_Rpsdatos_FmoPermiso();
        $tFper = new Application_Model_DbTable_FmoPermiso(); //1.00.002
        /*
            select a.* 
            from "sch_rpsdatos"."sn_tdatbas" as a 
            join "sch_rpsdatos"."fmo_tpermiso" as b on perm_ceco = datb_ceco::integer
            where b.perm_cedula = 15782860 -- and a.datb_cedula = 12128745 
            and perm_fecini::date<= now()::date and perm_fecfin::date >=now()::date and datb_activi <>9
         */
        $select = $tDireccion->select()
                        ->setIntegrityCheck(false)
                        ->distinct()
                        ->from(array('a' => $tDatb->info(Zend_Db_Table::NAME)), array('a.*'), $tDatb->info(Zend_Db_Table::SCHEMA))
                        ->join(array('b' => $tFper->info(Zend_Db_Table::NAME)), 'perm_ceco = datb_ceco::integer', null, $tFper->info(Zend_Db_Table::SCHEMA))
                        ->where('a.datb_activi <> ?', 9, Zend_Db::INT_TYPE);
        if($cedula!=''){
            $select->where('a.datb_cedula = ? ', $cedula, Zend_Db::INT_TYPE);
        }
        if($usuario!=''){
            $select->where('b.perm_cedula = ? ', $usuario, Zend_Db::INT_TYPE);
        }
                        
                        
                        /*->order(array('id_extension', 'id'))*/;
       /* if ($extension) {
            $select->where('(b.datb_cedula = ?', $extension, Zend_Db::INT_TYPE)
                    ->orWhere('b.datb_nrotrab = ?)', $extension, Zend_Db::INT_TYPE);
        }*/
        //echo '<br/><br/>',var_dump($select->__toString()),'<br/><br/>';
        return $tDireccion->getAdapter()->fetchAll($select);
    }
    
    
    
}
