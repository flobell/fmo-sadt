<?php

/*
 * Copyright (C) 2018 Enoc Carrasquero <enocc@ferrominera.com>
 */

/**
 * Description of Período
 *
 * @author enocc
 */
class Application_Model_Recorrido
{
    //Campos de la tabla período
    const ID  = 'id';
    const ABREV  = 'abrev';
    const DESCRIPCION     = 'descripcion';
    const STATUS = 'status';

    
    public static function getAll($id=null){

        $tRecorrido = new Application_Model_DbTable_Recorrido();
        $tRuta = new Application_Model_DbTable_Ruta();
        $tRefeZona= new Application_Model_DbTable_RefeZona();
        $tReferencia= new Application_Model_DbTable_Referencia();
        $tHorario= new Application_Model_DbTable_Horario();
        $tZona = new Application_Model_DbTable_Zona();
        
        $select = $tRutaZona->select()
                          ->setIntegrityCheck(false)
                          ->from(array('a' => $tRecorrido->info(Application_Model_DbTable_Recorrido::NAME)), array('a.id', 'a.id_ruta', 'a.id_refezona', 'b.descripcion as ruta_des', 'b.tiempo', 'b.distancia','d.id as id_referencia', 'd.descripcion as refe_des', 'e.id as id_horario', 'e.descripcion as hora_des', 'f.nombre as zona_desc'), $tRutaZona->info(Application_Model_DbTable_Recorrido::SCHEMA))
                          ->join(array('b' => $tRuta->info(Application_Model_DbTable_Ruta::NAME)), 'a.id_ruta=b.id', null, $tRuta->info(Application_Model_DbTable_Ruta::SCHEMA))
                          ->join(array('c' => $tRefeZona->info(Application_Model_DbTable_RefeZona::NAME)), 'a.id_refezona= c.id', null, $tRefeZona->info(Application_Model_DbTable_RefeZona::SCHEMA))
                          ->join(array('d' => $tReferencia->info(Application_Model_DbTable_Referencia::NAME)), 'c.id_referencia= d.id', null, $tReferencia->info(Application_Model_DbTable_Referencia::SCHEMA))
                          ->join(array('e' => $tHorario->info(Application_Model_DbTable_Referencia::NAME)), 'b.id_horario= e.id', null, $tHorario->info(Application_Model_DbTable_Referencia::SCHEMA))
                          ->join(array('f' => $tZona->info(Application_Model_DbTable_Zona::NAME)), 'c.id_zona=f.id', null, $tHorario->info(Application_Model_DbTable_Zona::SCHEMA))
                          ->where('b.status = ?', 'A');

        return $select->getAdapter()->fetchAll($select);
        
    }
    
    
    //DEVUELVE EL ORDEN DE UN RECORRIDO DADO SU ID
    public static function getOrden ($id_ruta=null, $orden=null){
        $tRecorrido = new Application_Model_DbTable_Recorrido();
        $tRefeZona= new Application_Model_DbTable_RefeZona();
        $tReferencia= new Application_Model_DbTable_Referencia();
        $tRuta = new Application_Model_DbTable_Ruta();
        $tZona = new Application_Model_DbTable_Zona();

         
        
         $select =$tRecorrido->select()
                            ->setIntegrityCheck(false)
                            ->from(array('re' => $tRecorrido->info(Application_Model_DbTable_Recorrido::NAME)), array('re.id','re.orden'), $tRecorrido->info(Application_Model_DbTable_Recorrido::SCHEMA))
                            ->join(array('rz' => $tRefeZona->info(Application_Model_DbTable_RefeZona::NAME)), 're.id_refezona = rz.id', null, $tRefeZona->info(Application_Model_DbTable_RefeZona::SCHEMA))
                            ->join(array('ref' => $tReferencia->info(Application_Model_DbTable_Referencia::NAME)), 'rz.id_referencia = ref.id', null, $tReferencia->info(Application_Model_DbTable_Referencia::SCHEMA))
                            ->join(array('r' => $tRuta->info(Application_Model_DbTable_Ruta::NAME)), 're.id_ruta = r.id', null, $tRuta->info(Application_Model_DbTable_Ruta::SCHEMA))
                            ->join(array('z' => $tZona->info(Application_Model_DbTable_Ruta::NAME)), 'rz.id_zona = z.id', null, $tZona->info(Application_Model_DbTable_Ruta::SCHEMA))
                            ->where('r.status = ?', 'A')
                            ->order('re.orden');
                            //->group('re.orden');

         
         if (!empty($id_ruta)){
             
             $select->where('r.id = ?', $id_ruta);
             
         }
         
         if (!empty($orden)){
             
             $select->where('re.orden not in (?)', $orden);
             
         }
        
         //print_r($select->__toString());
         
        return $tZona->getAdapter()->fetchPairs($select);
    }
    
    public static function updateOrden ($orden_actual, $orden_nuevo, $ordenes){
        
            
            if ($orden_actual<$orden_nuevo){

                for($j=$orden_actual;$j<=$orden_nuevo;$j++){    

                    if ($j!=$orden_actual){

                        $indice = array_search($j, $ordenes);
                        
                        if ($indice!=0){
                            
                            $contenido=($ordenes[$indice]);
                            
                            $recorrido = Application_Model_DbTable_Recorrido::findOneById($indice);

                            $recorrido->orden= $contenido-1;

                            $a =$recorrido->save();
                            

                        }

                    }

                    if ($j==$orden_nuevo){
                        
                        $indice = array_search($orden_actual, $ordenes);
                        
                        $contenido=($ordenes[$indice]);
                            
                        $recorrido = Application_Model_DbTable_Recorrido::findOneById($indice);

                        $recorrido->orden= $orden_nuevo;

                        $a =$recorrido->save();
                        

                    } 
                }

            }else{

                    for($j=$orden_actual;$j>=$orden_nuevo;$j--){    

                        if ($j!=$orden_actual){

                            $indice = array_search($j, $ordenes);

                            if ($indice!=0){

                                $contenido=($ordenes[$indice]);

                                $recorrido = Application_Model_DbTable_Recorrido::findOneById($indice);

                                $recorrido->orden= $contenido+1;

                                $a =$recorrido->save();

                            }

                        }

                        if ($j==$orden_nuevo){

                            $indice = array_search($orden_actual, $ordenes);

                            $recorrido = Application_Model_DbTable_Recorrido::findOneById($indice);

                            $recorrido->orden= $orden_nuevo;

                            $a =$recorrido->save();

                        } 
                    }

            }

        return $indice;

    }
    
        //DEVUELVE EL NÚMERO MÁXIMO DE ORDENES DE UN RECORRIDO
        public static function getmaxOrden ($id_ruta=null){
        
        $tRecorrido = new Application_Model_DbTable_Recorrido();
        $tRefeZona= new Application_Model_DbTable_RefeZona();
        $tReferencia= new Application_Model_DbTable_Referencia();
        $tZona = new Application_Model_DbTable_Zona();

         
        
         $select =$tRecorrido->select()
                            ->setIntegrityCheck(false)
                            ->from(array('re' => $tRecorrido->info(Application_Model_DbTable_Recorrido::NAME)), array('orden' => new Zend_Db_Expr("max(re.orden) + 1"),), $tRecorrido->info(Application_Model_DbTable_Recorrido::SCHEMA))
                            ->join(array('rz' => $tRefeZona->info(Application_Model_DbTable_RefeZona::NAME)), 're.id_refezona = rz.id', null, $tRefeZona->info(Application_Model_DbTable_RefeZona::SCHEMA))
                            ->join(array('ref' => $tReferencia->info(Application_Model_DbTable_Referencia::NAME)), 'rz.id_referencia = ref.id', null, $tReferencia->info(Application_Model_DbTable_Referencia::SCHEMA))
                            ->join(array('z' => $tZona->info(Application_Model_DbTable_Ruta::NAME)), 'rz.id_zona = z.id', null, $tZona->info(Application_Model_DbTable_Ruta::SCHEMA));
                            //->group('re.orden');

         
         if (!empty($id_ruta)){
             
             $select->where('re.id_ruta = ?', $id_ruta);
             
         }
//         print_r ($select->__toString());
//           exit;
         $array= $select->getAdapter()->fetchAll($select);
         if (!empty( $array[0]->orden)){

             return $array[0]->orden;
         }else{
             return 1;
         }
        
    }
    
    
    public static function getRecorrido ($id_ruta=null, $id_orden=null, $id_recorrido=null){
        
        $tRecorrido = new Application_Model_DbTable_Recorrido();
        $tRefeZona= new Application_Model_DbTable_RefeZona();
        $tReferencia= new Application_Model_DbTable_Referencia();
        $tRuta = new Application_Model_DbTable_Ruta();
        $tZona = new Application_Model_DbTable_Zona();
        $tHorario= new Application_Model_DbTable_Horario();
        
        $select =$tRecorrido->select()
                            ->setIntegrityCheck(false)
                            ->from(array('re' => $tRecorrido->info(Application_Model_DbTable_Recorrido::NAME)), array('re.id as id_recorrido', 're.id_ruta as id_ruta', 'ref.descripcion', 're.orden as orden', 'z.id_zona as id_padre', 'z.id as id_hijo', 'r.descripcion as des_ruta', 'h.descripcion as desc_horario'), $tRecorrido->info(Application_Model_DbTable_Recorrido::SCHEMA))
                            ->join(array('rz' => $tRefeZona->info(Application_Model_DbTable_RefeZona::NAME)), 're.id_refezona = rz.id', null, $tRefeZona->info(Application_Model_DbTable_RefeZona::SCHEMA))
                            ->join(array('ref' => $tReferencia->info(Application_Model_DbTable_Referencia::NAME)), 'rz.id_referencia = ref.id', null, $tReferencia->info(Application_Model_DbTable_Referencia::SCHEMA))
                            ->join(array('r' => $tRuta->info(Application_Model_DbTable_Ruta::NAME)), 're.id_ruta= r.id', null, $tRuta->info(Application_Model_DbTable_Ruta::SCHEMA))
                            ->join(array('z' => $tZona->info(Application_Model_DbTable_Ruta::NAME)), 'rz.id_zona = z.id', null, $tZona->info(Application_Model_DbTable_Ruta::SCHEMA))
                            ->join(array('h'=>$tHorario->info(Application_Model_DbTable_Horario::NAME)), 'r.id_horario = h.id', null, $tHorario->info(Application_Model_DbTable_Horario::SCHEMA))
                            ->where('r.status = ?', 'A')
                            ->order('re.orden');
        if (!empty($id_ruta)){
             
             $select->where('re.id_ruta = ?', $id_ruta);
             
         }
         
         if (!empty($id_orden)){
             
             $select->where('re.orden = ?', $id_orden);
             
         }
         
         if (!empty($id_recorrido)){
             
             $select->where('re.id = ?', $id_recorrido);
//           pint_r ($select->__toString());
//           exit;
             
         }
         
        return $select->getAdapter()->fetchAll($select);
    }
    
    
    public static function getRecorridoRutas ($id_horario=null,$id_ruta=null, $id_municipio=null, $id_parroquia = null){
        
        $tRecorrido = new Application_Model_DbTable_Recorrido();
        $tRuta = new Application_Model_DbTable_Ruta();
        $tRefeZona= new Application_Model_DbTable_RefeZona();
        $tReferencia= new Application_Model_DbTable_Referencia();
        $tHorario= new Application_Model_DbTable_Horario();
        $tZona = new Application_Model_DbTable_Zona();

        
        $select1 =$tRecorrido->select()
                            ->setIntegrityCheck(false) 
                            ->from(array('re' => $tRecorrido->info(Application_Model_DbTable_Recorrido::NAME)), array('re.id_ruta', 'ref.descripcion', 're.orden', 'z.id_zona', 'z.id'), $tRecorrido->info(Application_Model_DbTable_Recorrido::SCHEMA))
                            ->join(array('rz' => $tRefeZona->info(Application_Model_DbTable_RefeZona::NAME)), 're.id_refezona = rz.id', null, $tRefeZona->info(Application_Model_DbTable_RefeZona::SCHEMA))
                            ->join(array('ref' => $tReferencia->info(Application_Model_DbTable_Referencia::NAME)), 'rz.id_referencia = ref.id', null, $tReferencia->info(Application_Model_DbTable_Referencia::SCHEMA))
                            ->join(array('z' => $tZona->info(Application_Model_DbTable_Ruta::NAME)), 'rz.id_zona = z.id', null, $tZona->info(Application_Model_DbTable_Ruta::SCHEMA))
                            ->order('re.id_ruta DESC')
                            ->order('re.orden DESC');

                $select = $tRuta->select()
                          ->setIntegrityCheck(false)
                          ->from(array('r' => $tRuta->info(Application_Model_DbTable_Ruta::NAME)), 
                                      array(
                                          'id_ruta' => 'reb.id_ruta',
                                          'ruta' => 'r.descripcion',
                                          'horario' => 'h.descripcion',
                                          'recorrido' => new Zend_Db_Expr("string_agg(reb.descripcion, ', ')"),
                                          'orden' => new Zend_Db_Expr("max (reb.orden) + 1")
                                          ), 
                                  $tRuta->info(Application_Model_DbTable_Ruta::SCHEMA))
                          ->join(array('reb' => $select1), 'r.id = reb.id_ruta', array())
                          ->join(array('h'=>$tHorario->info(Application_Model_DbTable_Horario::NAME)), 'r.id_horario = h.id', null, $tHorario->info(Application_Model_DbTable_Horario::SCHEMA))
                          ->where('r.status = ?', 'A')
                          ->group('r.descripcion')
                          ->group('h.descripcion')
                          ->group('reb.id_ruta')
                          ->order('r.descripcion');

        if (!empty($id_horario)){
            $select->where('h.id = ?', $id_horario);    
        }
        
        if (!empty($id_ruta)){
            $select->where('r.id = ?', $id_ruta);
            
        }
        
        if (!empty($id_municipio)){
            $select->where('reb.id_zona = ?', $id_municipio);

        }
        
        if (!empty($id_parroquia)){
            $select->where('reb.id = ?', $id_parroquia);

        }
                
//        print_r ($select->__toString());
//        exit;
        
        return $select->getAdapter()->fetchAll($select);
        

    }
    
    public static function eliminarRecorridoRuta ($recorrido){
        
        try{
            
            ///$recorrido[0]->id_recorrido;
            
            $ordenes = Application_Model_Recorrido::getOrden($recorrido[0]->id_ruta);
            
//            var_dump($recorrido);
//            exit;
            
            $actualizar=0;
            

            
            for ($i=$recorrido[0]->orden+1;$i<=count($ordenes);$i++){
                
                $indice = array_search($i, $ordenes);
                
                $contenido=($ordenes[$indice]);
                
                $orden = Application_Model_DbTable_Recorrido::findOneById($indice);

                $orden->orden= $contenido-1;

                $actualizar =$orden->save();
                
            }
            
            $eliminar= Application_Model_DbTable_Recorrido::findOneById($recorrido[0]->id_recorrido);
            
            $eliminar->delete();

            //$eliminar->save();
         
            //$this->addMessageWarning("Recorrido eliminado exitosamente.");
            
            return 1;
            
            
        }catch (Exception $ex){
            
            return $ex;
            
        }

    }
  

    
}