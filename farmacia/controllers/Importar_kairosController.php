<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Medic;
use farmacia\models\AlarmaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

/**
 * AlarmaController implements the CRUD actions for Alarma model.
 */
class Importar_kairosController extends Controller
{
      public $CodController="026";
    /**
     * @inheritdoc
     */
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::classname(),
                'only'=>["importar"],
                'rules'=>[
                    [
                        'allow'=>true,
                        'roles'=> ['@'],
                        'matchCallback' => 
                            function ($rule, $action) {
                                return Yii::$app->user->identity->habilitado($action);
                            }
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {   
        return $this->render('index', ['resultado'=>''
            
            ]);
    }

     public function actionImportar_txt()
    {   
        set_time_limit(0);
        
        //return \yii\helpers\Json::encode('tt');
             $path_kairos = Yii::$app->params['local_path']['path_txt_kairos'];
             $resultado = "Archivos txt importados desde $path_kairos :<br>";
             $connection = \Yii::$app->db;
             $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                try {
                     //BASLAB
                     if (file_exists ($path_kairos.'/BASLAB.TXT'))
                     {  
                    
                        $connection ->createCommand()->delete('kairos_baslab')->execute();

                        $sql = "LOAD DATA INFILE '". $path_kairos."/BASLAB.TXT' INTO TABLE kairos_baslab  CHARACTER SET LATIN1 (@var1) SET codigo = SUBSTR(@var1,2,5),
                                                                                                    descripcion = SUBSTR(@var1,7,15),
                                                                                                    estado = SUBSTR(@var1,22,1);";
                        $connection ->createCommand($sql)->execute();                                                                                        
                                                                                                                
                        $resultado .= "BASLAB importado<br>";
                     }
                    
                    //BASPRO
                    if (file_exists ($path_kairos.'/BASPRO.TXT'))
                    {  
                    
                        $connection ->createCommand()->delete('kairos_baspro')->execute();

                        $sql = "LOAD DATA INFILE '".$path_kairos."/BASPRO.TXT' INTO TABLE kairos_baspro  CHARACTER SET LATIN1 (@var1) SET codigo = SUBSTR(@var1,2,6),
                                                                                                    descripcion = SUBSTR(@var1,8,40),
                                                                                                    laboratorio = SUBSTR(@var1,49,5),
                                                                                                    origen = SUBSTR(@var1,54,1),
                                                                                                    psicofarmaco = SUBSTR(@var1,55,1),                                                                                      
                                                                                                    codigo_venta = SUBSTR(@var1,56,1),
                                                                                                    estupefaciente = SUBSTR(@var1,57,1),                                                                                                                                                                             
                                                                                                    estado = SUBSTR(@var1,58,1);";
                        $connection ->createCommand($sql)->execute();                                                                                        
                                                                                                                
                        $resultado .= "BASPRO importado<br>";
                    }     
                    
                     //BASATE
                    if (file_exists ($path_kairos.'/BASATE.TXT'))
                    {  
                    
                        $connection ->createCommand()->delete('kairos_basate')->execute();

                        $sql = "LOAD DATA INFILE '".$path_kairos."/BASATE.TXT' INTO TABLE kairos_basate  CHARACTER SET LATIN1 (@var1) SET codigo = SUBSTR(@var1,2,4),
                                                                                                    descripcion = SUBSTR(@var1,6,45),
                                                                                                    estado = SUBSTR(@var1,51,1);";
                        $connection ->createCommand($sql)->execute();                                                                                        
                                                                                                                
                        $resultado .= "BASATE importado<br>";
                    }     
                     //BASATP
                    if (file_exists ($path_kairos.'/BASATP.TXT'))
                    {  
                    
                        $connection ->createCommand()->delete('kairos_basatp')->execute();

                        $sql = "LOAD DATA INFILE '".$path_kairos."/BASATP.TXT' INTO TABLE kairos_basatp  CHARACTER SET LATIN1 (@var1) SET 
                                                                                                    codigo_accion = SUBSTR(@var1,2,4),
                                                                                                    codigo_producto = SUBSTR(@var1,7,6),
                                                                                                    presentacion = SUBSTR(@var1,13,6),
                                                                                                    via_administracion = SUBSTR(@var1,19,6),
                                                                                                    medio_presentacion = SUBSTR(@var1,25,6),                                                                                        
                                                                                                    importancia = SUBSTR(@var1,32,1);";
                        $connection ->createCommand($sql)->execute();                                                                                        
                                                                                                                
                        $resultado .= "BASATP importado<br>";
                    } 


                     //BASDP
                    if (file_exists ($path_kairos.'/BASDP.TXT'))
                    {  
                    
                        $connection ->createCommand()->delete('kairos_basdp')->execute();

                        $sql = "LOAD DATA INFILE '".$path_kairos."/BASDP.TXT' INTO TABLE kairos_basdp  CHARACTER SET LATIN1 (@var1) SET 
                                                                                                    codigo_droga = SUBSTR(@var1,2,4),
                                                                                                    codigo_producto = SUBSTR(@var1,7,6),
                                                                                                    presentacion = SUBSTR(@var1,13,6),
                                                                                                    via_administracion = SUBSTR(@var1,19,6),
                                                                                                    medio_presentacion = SUBSTR(@var1,25,6),                                                                                        
                                                                                                    importancia = SUBSTR(@var1,32,1);";
                        $connection ->createCommand($sql)->execute();                                                                                        
                                                                                                                
                        $resultado .= "BASDP importado<br>";
                    }

                     //BASDRO
                    if (file_exists ($path_kairos.'/BASDRO.TXT'))
                    {  
                    
                        $connection ->createCommand()->delete('kairos_basdro')->execute();

                        $sql = "LOAD DATA INFILE '".$path_kairos."/BASDRO.TXT' INTO TABLE kairos_basdro  CHARACTER SET LATIN1 (@var1) SET 
                                                                                                    codigo = SUBSTR(@var1,2,4),
                                                                                                    descripcion = SUBSTR(@var1,6,45),
                                                                                                    estado = SUBSTR(@var1,51,1);    ";
                        $connection ->createCommand($sql)->execute();                                                                                        
                                                                                                                
                        $resultado .= "BASDRO importado<br>";
                    }

                      //BASIOM
                    if (file_exists ($path_kairos.'/BASIOM.TXT'))
                    {  
                    
                        $connection ->createCommand()->delete('kairos_basiom')->execute();

                        $sql = "LOAD DATA INFILE '".$path_kairos."/BASIOM.TXT' INTO TABLE kairos_basiom  CHARACTER SET LATIN1 (@var1) SET 
                                                                                                    codigo_producto = SUBSTR(@var1,2,6),
                                                                                                    codigo_presentacion = SUBSTR(@var1,9,2),
                                                                                                    monto = SUBSTR(@var1,12,13),
                                                                                                    fecha_vigencia = SUBSTR(@var1,26,6);";
                        $connection ->createCommand($sql)->execute();                                                                                        
                                                                                                                
                        $resultado .= "BASIOM importado<br>";
                    }

                      //BASPAM
                    if (file_exists ($path_kairos.'/BASPAM.TXT'))
                    {  
                    
                        $connection ->createCommand()->delete('kairos_baspam')->execute();

                        $sql = "LOAD DATA INFILE '".$path_kairos."/BASPAM.TXT' INTO TABLE kairos_baspam  CHARACTER SET LATIN1 (@var1) SET 
                                                                                                    codigo_producto = SUBSTR(@var1,2,6),
                                                                                                    codigo_presentacion = SUBSTR(@var1,9,2),
                                                                                                    monto = SUBSTR(@var1,12,13),
                                                                                                    fecha_vigencia = SUBSTR(@var1,26,6);";
                        $connection ->createCommand($sql)->execute();                                                                                        
                                                                                                                
                        $resultado .= "BASPAM importado<br>";
                    }

                    //BASPRC
                    if (file_exists ($path_kairos.'/BASPRC.TXT'))
                    {  
                    
                        $connection ->createCommand()->delete('kairos_basprc')->execute();

                        $sql = "LOAD DATA INFILE '".$path_kairos."/BASPRC.TXT' INTO TABLE kairos_basprc  CHARACTER SET LATIN1 (@var1) SET 
                                                                                                    codigo_producto = SUBSTR(@var1,2,6),
                                                                                                    codigo_presentacion = SUBSTR(@var1,9,2),
                                                                                                    precio_publico = SUBSTR(@var1,12,13),
                                                                                                    fecha_vigencia = SUBSTR(@var1,26,6);";
                        $connection ->createCommand($sql)->execute();                                                                                        
                                                                                                                
                        $resultado .= "BASPRC importado<br>";
                    }

                    //BASPRE
                    if (file_exists ($path_kairos.'/BASPRE.TXT'))
                    {  
                    
                        $connection ->createCommand()->delete('kairos_baspre')->execute();

                        $sql = "LOAD DATA INFILE '".$path_kairos."/BASPRE.TXT' INTO TABLE kairos_baspre  CHARACTER SET LATIN1 (@var1) SET 
                                                                                                    codigo_producto = SUBSTR(@var1,2,6),
                                                                                                    codigo_presentacion = SUBSTR(@var1,9,2),
                                                                                                    descripcion = SUBSTR(@var1,11,60),
                                                                                                    iva = SUBSTR(@var1,71,1),                                                                                                                                                                                                                                                                                                                                               
                                                                                                    pami = SUBSTR(@var1,72,1),
                                                                                                    codigo_troquel = SUBSTR(@var1,74,8),
                                                                                                    ioma = SUBSTR(@var1,82,1),
                                                                                                    ioma_normatizado = SUBSTR(@var1,83,1),                                                                                                                                                                                                                                                                                                                                                              
                                                                                                    codigo_barras = SUBSTR(@var1,85,13),    
                                                                                                    estado = SUBSTR(@var1,98,1);";
                        $connection ->createCommand($sql)->execute();                                                                                        
                                                                                                                
                        $resultado .= "BASPRE importado<br>";
                    }
                    //BASTIP
                    if (file_exists ($path_kairos.'/BASTIP.TXT'))
                    {  
                    
                        $connection ->createCommand()->delete('kairos_bastip')->execute();

                        $sql = "LOAD DATA INFILE '".$path_kairos."/BASTIP.TXT' INTO TABLE kairos_bastip  CHARACTER SET LATIN1 (@var1) SET 
                                                                                                    codigo_producto = SUBSTR(@var1,2,6),
                                                                                                    codigo_presentacion = SUBSTR(@var1,9,2),
                                                                                                    especificacion = SUBSTR(@var1,11,6),
                                                                                                    via = SUBSTR(@var1,17,6),
                                                                                                    forma = SUBSTR(@var1,23,6),                                                                                     
                                                                                                    concentracion = SUBSTR(@var1,30,11),
                                                                                                    unid_concentracion = SUBSTR(@var1,41,6),
                                                                                                    comentario_concentracion = SUBSTR(@var1,47,10),
                                                                                                    cantidad_envase = SUBSTR(@var1,58,4),           
                                                                                                    cantidad_unidad = SUBSTR(@var1,63,7),
                                                                                                    unidad_cantidad = SUBSTR(@var1,70,6),                                                                                                                                                                                                                                                                                                                                                                                                                                           
                                                                                                    dosis = SUBSTR(@var1,77,3);";
                        $connection ->createCommand($sql)->execute();                                                                                        
                                                                                                                
                        $resultado .= "BASTIP importado<br>";
                    }

                    $transaction->commit();
                    return \yii\helpers\Json::encode($resultado);
                    
                 }
            catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
                return \yii\helpers\Json::encode("ERROR");
            }
           

        
    }

    public function actionActualizar_precios()
    {
                      
            
            $connection = \Yii::$app->db;
            $medicamentos = Medic::find()->all();
            $resultado = '';
            foreach ($medicamentos as $key => $medic) {
                if (isset($medic['ME_CODKAI']) && !empty($medic['ME_CODKAI'])){
                    $prod_kairos = str_pad(ltrim(substr($medic['ME_CODKAI'], 0,6),'0'), 6, " ", STR_PAD_LEFT);
                    $pres_kairos = str_pad(ltrim(substr($medic['ME_CODKAI'], -2),'0'), 2, " ", STR_PAD_LEFT);
                    
                    
                    $query2 = (new \yii\db\Query())
                        ->from(['kairos_baspre']);
                    $query2->andFilterWhere(['kairos_baspre.codigo_producto' => $prod_kairos,
                                            'kairos_baspre.codigo_presentacion' => $pres_kairos]);
                    
                    // $query2->join('INNER JOIN', 'kairos_bastip',
                    //  "kairos_bastip.codigo_producto= kairos_baspre.codigo_producto and
                    //                     kairos_bastip.codigo_presentacion= kairos_baspre.codigo_presentacion");  

                    $query2->join('INNER JOIN', 'kairos_basprc',
                     "kairos_basprc.codigo_producto=kairos_baspre.codigo_producto and
                                         kairos_basprc.codigo_presentacion = kairos_baspre.codigo_presentacion");  


                    $prod_kairos = $query2->one();

                    //Si unidades por envase es cero se setea 1
                    $unidad_envase = (!isset($medic['ME_UNIENV'])||$medic['ME_UNIENV']==0)?1:$medic['ME_UNIENV'];

                    $precio_venta = floatval(str_replace(',','.',$prod_kairos['precio_publico']))/$unidad_envase;
                    
                    $medic_update = Medic::findOne($medic['ME_CODIGO']);
                    //print_r($medic_update);
                    $medic_update->ME_VALVEN = $precio_venta;
                    
                    $medic_update->save();  

                    //No pueden estar vacios
                    //ME_CODRAF,ME_KAIBAR,ME_KAITRO,ME_PRES,ME_FRACCQ,ME_RUBRO 
                     
                     if ($medic_update->getErrors()){
                         $resultado = $medic_update->getErrors();
                     }
                    
                }
            }

            return \yii\helpers\Json::encode($resultado);
            
            
    }

   

   
}
