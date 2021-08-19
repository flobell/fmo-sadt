<?php

/*
 * Copyright (C) 2018 Enoc Carrasquero <enocc@ferrominera.com>
 */

/**
 * Description of Período
 *
 * @author enocc
 */
class Application_Model_RutaZona
{
    //Campos de la tabla período
    const ID            = 'id';
    const ABREV  = 'abrev';
    const DESCRIPCION     = 'descripcion';
    const STATUS = 'status';

    
    public static function getAll($id=null){
        
        $tRutaZona = new Application_Model_DbTable_RutaZona();
        $tRuta = new Application_Model_DbTable_Ruta();
        $tZona = new Application_Model_DbTable_Zona();
        $tHorario= new Application_Model_DbTable_Horario();
        
        $select = $tRutaZona->select()
                          ->setIntegrityCheck(false)
                          ->from(array('a' => $tRutaZona->info(Application_Model_DbTable_RutaZona::NAME)), array('a.id', 'a.id_zona', 'a.id_ruta', 'a.id_horario', 'b.descripcion as ruta_des', 'c.id_zona as zona_id', 'c.nombre', 'c.nivel', 'd.descripcion as horario_des'), $tRutaZona->info(Application_Model_DbTable_RutaZona::SCHEMA))
                          ->join(array('b' => $tRuta->info(Application_Model_DbTable_Ruta::NAME)), 'a.id_ruta = b.id', null, $tRuta->info(Application_Model_DbTable_Ruta::SCHEMA))
                          ->join(array('c' => $tZona->info(Application_Model_DbTable_Zona::NAME)), 'a.id_zona = c.id', null, $tZona->info(Application_Model_DbTable_Zona::SCHEMA))
                          ->join(array('d' => $tHorario->info(Application_Model_DbTable_Horario::NAME)), 'a.id_horario = d.id', null, $tHorario->info(Application_Model_DbTable_Horario::SCHEMA));
                  //var_dump($select->getAdapter()->fetchAll($select));
        
        //print_r ($select->__toString());
        //$select->getAdapter()->fetchAll($select);
        //$select->__toString();      
        //exit;
        return $select->getAdapter()->fetchAll($select);
        
    }
    
//        public static function getRutaZona($id_horario=null,$id_ruta=null, $id_zona=null){
//        
//        $tRutaZona = new Application_Model_DbTable_RutaZona();
//        $tRuta = new Application_Model_DbTable_Ruta();
//        $tZona = new Application_Model_DbTable_Zona();
//        $tHorario= new Application_Model_DbTable_Horario();
//        
//        $select = $tRutaZona->select()
//                          ->setIntegrityCheck(false)
//                          ->from(array('a' => $tRutaZona->info(Application_Model_DbTable_RutaZona::NAME)), array('a.id', 'a.id_zona', 'a.id_ruta', 'a.id_horario', 'b.descripcion as ruta_des', 'c.id_zona as zona_id', 'c.nombre', 'c.nivel', 'd.descripcion as horario_des'), $tRutaZona->info(Application_Model_DbTable_RutaZona::SCHEMA))
//                          ->join(array('b' => $tRuta->info(Application_Model_DbTable_Ruta::NAME)), 'a.id_ruta = b.id', null, $tRuta->info(Application_Model_DbTable_Ruta::SCHEMA))
//                          ->join(array('c' => $tZona->info(Application_Model_DbTable_Zona::NAME)), 'a.id_zona = c.id', null, $tZona->info(Application_Model_DbTable_Zona::SCHEMA))
//                          ->join(array('d' => $tHorario->info(Application_Model_DbTable_Horario::NAME)), 'a.id_horario = d.id', null, $tHorario->info(Application_Model_DbTable_Horario::SCHEMA));
//                 
//        if (!empty($id_horario)){
//            $select->where('d.id = ?', $id_horario);    
//        }
//        
//        
//        if (!empty($id_ruta)){
//            $select->where('a.id_ruta = ?', $id_ruta);    
//        }
//        
//        if (!empty($id_zona)){
//            $select->where('a.id_zona like ?', $id_zona.'%');    
////             print_r ($select->__toString());
////        //$select->getAdapter()->fetchAll($select);
////        //$select->__toString();      
////        exit;
//        }
//        
//        //var_dump($select->getAdapter()->fetchAll($select));
//        
////        print_r ($select->__toString());
////        //$select->getAdapter()->fetchAll($select);
////        //$select->__toString();      
////        exit;
//        
//        
//        return $select->getAdapter()->fetchAll($select);
//        
//    }
    
    public static function getRutaZona ($id_horario=null,$id_ruta=null, $id_zona=null){
        
        $tRutaZona = new Application_Model_DbTable_RutaZona();
        $tRuta = new Application_Model_DbTable_Ruta();
        $tZona = new Application_Model_DbTable_Zona();
        $tHorario= new Application_Model_DbTable_Horario();
        
                $select1 =$tRutaZona->select()
                            ->setIntegrityCheck(false)
                            ->from(array('rza' => $tRutaZona->info(Application_Model_DbTable_RutaZona::NAME)), array('rza.id_ruta', 'z.nombre', 'rza.orden', 'z.id'), $tRutaZona->info(Application_Model_DbTable_RutaZona::SCHEMA))
                            ->join(array('z' => $tZona->info(Application_Model_DbTable_Ruta::NAME)), 'rza.id_zona = z.id', null, $tZona->info(Application_Model_DbTable_Ruta::SCHEMA))
                            ->order('rza.id_ruta DESC')
                            ->order('rza.orden DESC');

                $select = $tRuta->select()
                          ->setIntegrityCheck(false)
                          ->from(array('r' => $tRuta->info(Application_Model_DbTable_Ruta::NAME)), 
                                      array(
                                          'ruta' => 'r.descripcion',
                                          'horario' => 'h.descripcion',
                                          'recorrido' => new Zend_Db_Expr("string_agg(rzb.nombre, ', ')")
                                          ), 
                                  $tRuta->info(Application_Model_DbTable_Ruta::SCHEMA))
                          ->join(array('rzb' => $select1), 'r.id = rzb.id_ruta', array())
                          ->join(array('h'=>$tHorario->info(Application_Model_DbTable_Horario::NAME)), 'r.id_horario = h.id', null, $tHorario->info(Application_Model_DbTable_Horario::SCHEMA))
                          ->group('r.descripcion')
                          ->group('h.descripcion')
                          ->order('r.descripcion');

        if (!empty($id_horario)){
            $select->where('h.id = ?', $id_horario);    
        }
        
        if (!empty($id_ruta)){
            $select->where('r.id = ?', $id_ruta);
//            print_r ($select->__toString());
//            exit;
            
        }
        
        if (!empty($id_zona)){
            $select->where('rzb.id like ?', $id_zona.'%');
//              print_r ($select->__toString());
//            exit;
        }
                
//         print_r ($select->__toString());
//        exit;
        
        return $select->getAdapter()->fetchAll($select);
    }
    
    

    
}