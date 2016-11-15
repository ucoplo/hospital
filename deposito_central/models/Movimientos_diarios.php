<?php

namespace deposito_central\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "mov_dia".
 *
 * @property string $DM_FECHA
 * @property string $DM_CODMOV
 * @property string $DM_CANT
 * @property string $DM_FECVTO
 * @property string $DM_CODART
 * @property string $DM_DEPOSITO
 *
 * @property ArticGral $mDCODMON
 * @property Deposito $mDDEPOSITO
 * @property Movsto $mDCODMOV
 */
class Movimientos_diarios extends \yii\db\ActiveRecord
{
    public $descripcion;
    public $concepto,$entrada,$salida,$existencia;
     public $consumo,$consumo_valor,$porc_abc,$clasifica_abc;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dc_mov_dia';
    }

     public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['DM_FECVTO'], // update 1 attribute 'created' OR multiple attribute ['created','updated']
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'DM_FECVTO', // update 1 attribute 'created' OR multiple attribute ['created','updated']
                ],
                'value' => function ($event) {
                    return date('Y-m-d', strtotime(str_replace("/","-",$this->DM_FECVTO)));
                },
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DM_FECHA', 'DM_CODMOV', 'DM_FECVTO', 'DM_CODART', 'DM_DEPOSITO'], 'required'],
            [['DM_FECHA', 'DM_FECVTO'], 'safe'],
            [['DM_CANT'], 'number'],
            [['DM_CODMOV'], 'string', 'max' => 1],
            [['DM_CODART'], 'string', 'max' => 4],
            [['DM_DEPOSITO'], 'string', 'max' => 2],
            [['DM_CODART'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['DM_CODART' => 'AG_CODIGO','DM_DEPOSITO' => 'AG_DEPOSITO']],
            [['DM_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['DM_DEPOSITO' => 'DE_CODIGO']],
            [['DM_CODMOV'], 'exist', 'skipOnError' => true, 'targetClass' => Movimientos_tipos::className(), 'targetAttribute' => ['DM_CODMOV' => 'DM_COD']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DM_FECHA' => 'Fecha del movimiento',
            'DM_CODMOV' => 'Tipo de Movimiento Diario',
            'DM_CANT' => 'Cantidad',
            'DM_FECVTO' => 'Fecha de vencimiento',
            'DM_CODART' => 'Artículo',
            'DM_DEPOSITO' => 'Depósito',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticulo()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'DM_CODART','AG_DEPOSITO' => 'DM_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'DM_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigo()
    {
        return $this->hasOne(Movimientos_tipos::className(), ['DM_COD' => 'DM_CODMOV']);
    }

    public function getListaDeposito()
    {
        $opciones = Deposito::find();
        $depositos = Yii::$app->params['depositos_central'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

     public function ListaTipos($habilitado)
    {
        $opciones = Movimientos_tipos::find();
       
        $opciones->where(['DM_VALIDO'=> 1] );

         if ($habilitado) {
             $opciones->andFilterWhere(['>', 'DM_SIGNO',0]);
         }
        // if ($this->DM_FECHA != date('Y-m-d')){
        //   $opciones->andFilterWhere(['>', 'DM_SIGNO',0]);
        // }

       $opciones = $opciones->asArray()->all();

       return $opciones;
        
        
    }
}
