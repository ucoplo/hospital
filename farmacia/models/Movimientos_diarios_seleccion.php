<?php

namespace farmacia\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\NumberValidator;
/**
 * Este modelo es utilizado para validar el ingreso del filtro de fecha y deposito
 * de movimientos diarios a modificar.
 *
 * @property string $MD_FECHA
 * @property string $MD_DEPOSITO
 *
 * @property Deposito $mDDEPOSITO
 */
class Movimientos_diarios_seleccion extends \yii\db\ActiveRecord
{
    public $renglones;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mov_dia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {   $fecha =date('Y-m-d'); 
        return [
            [['MD_FECHA', 'MD_DEPOSITO','renglones'], 'required'],
            [['MD_FECHA', 'MD_DEPOSITO','renglones'], 'safe'],
            [['renglones'],'validateRenglonesCreate', 'on' => 'update'],
            [['renglones'],'validateRenglonesBlanqueo', 'on' => 'blanquear'],
            ['MD_FECHA','compare','compareValue'=>date('Y-m-d'),'operator'=>'<=','message'=>'Debe ser menor a Hoy'],
            [['MD_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['MD_DEPOSITO' => 'DE_CODIGO']],
            
        ];
    }
    public function validateRenglonesBlanqueo($attribute, $params)
    {
        $codigos_monodrogas = [];
        foreach($this->$attribute as $index => $renglon) {
            $codmon = [$renglon['MD_CODMON']];
            
            if (in_array($codmon, $codigos_monodrogas)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no puede repetirse');
                
            }
            else{
                $codigos_monodrogas[] = $codmon;
            }
        }
    }
    public function validateRenglonesCreate($attribute, $params)
    {
        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['MD_CANT'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'Cantidad debe ser un número');
            }

           
        }

        $codigos_monodrogas = [];
        foreach($this->$attribute as $index => $renglon) {
            $codmon_fecven = [$renglon['MD_CODMON'],$renglon['MD_FECVEN']];
            
            if (in_array($codmon_fecven, $codigos_monodrogas)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento y fecha no puede repetirse');
                
            }
            else{
                $codigos_monodrogas[] = $codmon_fecven;
            }
        }

        //verificar existencia
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['MD_CODMON'];
            $deposito = $this->MD_DEPOSITO;
            $fecven = Yii::$app->formatter->asDate($renglon['MD_FECVEN'],'php:Y-m-d');
            
            if ($this->movimiento_negativo($renglon['MD_CODMOV'])){
                $cantidad_existencia = $this->cantidad_vigente($codmon,$deposito,$fecven);

                $query = Movimientos_diarios::find();
                $query->joinWith(['codigo']);
                $query->where(['MD_FECHA' => $this->MD_FECHA, 'MD_DEPOSITO' => $this->MD_DEPOSITO, 
                                'MD_CODMON' => $codmon, 'MD_FECVEN' => $fecven, 
                                'MS_VALIDO'=> 1]);

                $renglon_antiguo =  $query->one();
                
                if (isset($renglon_antiguo)){
                    $valor = $renglon_antiguo->codigo->MS_SIGNO * $renglon_antiguo->MD_CANT;
                    $cantidad_existencia -= $valor;
                }

                if ($cantidad_existencia<$renglon['MD_CANT']) {
                    $key = $attribute . '[' . $index . '][descripcion]';
                    $this->addError($key, "Existencia insuficiente. Máximo $cantidad_existencia");
                    
                }
            }
        }
    } 
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'MD_FECHA' => 'Fecha del movimiento',
            'MD_DEPOSITO' => 'Depósito de farmacia',
        ];
    }

   /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'MD_DEPOSITO']);
    }

    public function getListaDeposito()
    {
        $opciones = Deposito::find();
        $depositos = Yii::$app->params['depositos_farmacia'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

    public function getListaMedicamentos()
    {
        $opciones = ArticGral::find();
       
        $opciones->where(['AG_DEPOSITO' => $this->MD_DEPOSITO] );

       $opciones = $opciones->asArray()->all();

       return $opciones;
        
        
    }

    public function getListaTipos()
    {
        $opciones = Movimientos_tipos::find();
       
        $opciones->where(['MS_VALIDO'=> 1] );

         
        if ($this->MD_FECHA != date('Y-m-d')){
          $opciones->andFilterWhere(['>', 'MS_SIGNO',0]);
        }

       $opciones = $opciones->asArray()->all();

       return $opciones;
        
        
    }
    private function movimiento_negativo($codigo){
        $movimiento = Movimientos_tipos::findOne(['MS_COD'=>$codigo]);

        return ($movimiento->MS_SIGNO<0);
    }

    private function cantidad_vigente($codmon,$deposito,$fecven){

       $cantidad =  Vencimientos::find()
                   ->where(['TV_CODART' => $codmon,'TV_DEPOSITO' => $deposito,'TV_FECVEN' => $fecven])
                   ->andWhere([">", 'TV_SALDO', 0])->sum('TV_SALDO');

       return $cantidad+0;
    }

}
