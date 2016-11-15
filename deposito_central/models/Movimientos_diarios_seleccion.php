<?php

namespace deposito_central\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\NumberValidator;
/**
 * Este modelo es utilizado para validar el ingreso del filtro de fecha y deposito
 * de movimientos diarios a modificar.
 *
 * @property string $DM_FECHA
 * @property string $DM_DEPOSITO
 *
 * @property Deposito $mDDEPOSITO
 */
class Movimientos_diarios_seleccion extends \yii\db\ActiveRecord
{
    public $renglones,$deposito_descripcion;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dc_mov_dia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {   $fecha =date('Y-m-d'); 
        return [
            [['DM_FECHA', 'DM_DEPOSITO','renglones'], 'required'],
            [['DM_FECHA', 'DM_DEPOSITO','renglones'], 'safe'],
            [['renglones'],'validateRenglonesCreate', 'on' => 'update'],
            [['renglones'],'validateRenglonesBlanqueo', 'on' => 'blanquear'],
            ['DM_FECHA','compare','compareValue'=>date('Y-m-d'),'operator'=>'<=','message'=>'Debe ser menor a Hoy'],
            [['DM_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['DM_DEPOSITO' => 'DE_CODIGO']],
            
        ];
    }
    public function validateRenglonesBlanqueo($attribute, $params)
    {
        $codigos_articulos = [];
        foreach($this->$attribute as $index => $renglon) {
            $codart = [$renglon['DM_CODART']];
            
            if (in_array($codart, $codigos_articulos)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no puede repetirse');
                
            }
            else{
                $codigos_articulos[] = $codart;
            }
        }
    }
    public function validateRenglonesCreate($attribute, $params)
    {
        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['DM_CANT'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'Cantidad debe ser un número');
            }

           
        }

        $codigos_articulos = [];
        foreach($this->$attribute as $index => $renglon) {
            $codart_fecven = [$renglon['DM_CODART'],$renglon['DM_FECVTO']];
            
            if (in_array($codart_fecven, $codigos_articulos)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento y fecha no puede repetirse');
                
            }
            else{
                $codigos_articulos[] = $codart_fecven;
            }
        }

        //verificar existencia
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['DM_CODART'];
            $deposito = $this->DM_DEPOSITO;
            $fecven = Yii::$app->formatter->asDate($renglon['DM_FECVTO'],'php:Y-m-d');
            
            if ($this->movimiento_negativo($renglon['DM_CODMOV'])){
                $cantidad_existencia = $this->cantidad_vigente($codart,$deposito,$fecven);

                $query = Movimientos_diarios::find();
                $query->joinWith(['codigo']);
                $query->where(['DM_FECHA' => $this->DM_FECHA, 'DM_DEPOSITO' => $this->DM_DEPOSITO, 
                                'DM_CODART' => $codart, 'DM_FECVTO' => $fecven, 
                                'DM_VALIDO'=> 1]);

                $renglon_antiguo =  $query->one();
                
                if (isset($renglon_antiguo)){
                    $valor = $renglon_antiguo->codigo->DM_SIGNO * $renglon_antiguo->DM_CANT;
                    $cantidad_existencia -= $valor;
                }

                if ($cantidad_existencia<$renglon['DM_CANT']) {
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
            'DM_FECHA' => 'Fecha',
            'DM_DEPOSITO' => 'Depósito',
            'deposito_descripcion' => 'Depósito',
        ];
    }

   /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'DM_DEPOSITO']);
    }

    public function getListaDeposito()
    {
        $opciones = Deposito::find();
        $depositos = Yii::$app->params['depositos_central'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

    public function getListaMedicamentos()
    {
        $opciones = ArticGral::find();
       
        $opciones->where(['AG_DEPOSITO' => $this->DM_DEPOSITO] );

       $opciones = $opciones->asArray()->all();

       return $opciones;
        
        
    }

    public function getListaTipos()
    {
        $opciones = Movimientos_tipos::find();
       
        $opciones->where(['DM_VALIDO'=> 1] );

         
        if ($this->DM_FECHA != date('Y-m-d')){
          $opciones->andFilterWhere(['>', 'DM_SIGNO',0]);
        }

       $opciones = $opciones->asArray()->all();

       return $opciones;
        
        
    }
    private function movimiento_negativo($codigo){
        $movimiento = Movimientos_tipos::findOne(['DM_COD'=>$codigo]);

        return ($movimiento->DM_SIGNO<0);
    }

    private function cantidad_vigente($codart,$deposito,$fecven){

       $cantidad =  Vencimientos::find()
                   ->where(['DT_CODART' => $codart,'DT_DEPOSITO' => $deposito,'DT_FECVEN' => $fecven])
                   ->andWhere([">", 'DT_SALDO', 0])->sum('DT_SALDO');

       return $cantidad+0;
    }

}
