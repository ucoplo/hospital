<?php

namespace farmacia\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "mov_dia".
 *
 * @property string $MD_FECHA
 * @property string $MD_CODMOV
 * @property string $MD_CANT
 * @property string $MD_FECVEN
 * @property string $MD_CODMON
 * @property string $MD_DEPOSITO
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
        return 'mov_dia';
    }

     public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['MD_FECVEN'], // update 1 attribute 'created' OR multiple attribute ['created','updated']
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'MD_FECVEN', // update 1 attribute 'created' OR multiple attribute ['created','updated']
                ],
                'value' => function ($event) {
                    return date('Y-m-d', strtotime(str_replace("/","-",$this->MD_FECVEN)));
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
            [['MD_FECHA', 'MD_CODMOV', 'MD_FECVEN', 'MD_CODMON', 'MD_DEPOSITO'], 'required'],
            [['MD_FECHA', 'MD_FECVEN'], 'safe'],
            [['MD_CANT'], 'number'],
            [['MD_CODMOV'], 'string', 'max' => 1],
            [['MD_CODMON'], 'string', 'max' => 4],
            [['MD_DEPOSITO'], 'string', 'max' => 2],
            [['MD_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['MD_CODMON' => 'AG_CODIGO']],
            [['MD_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['MD_DEPOSITO' => 'DE_CODIGO']],
            [['MD_CODMOV'], 'exist', 'skipOnError' => true, 'targetClass' => Movimientos_tipos::className(), 'targetAttribute' => ['MD_CODMOV' => 'MS_COD']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'MD_FECHA' => 'Fecha del movimiento',
            'MD_CODMOV' => 'Tipo de movimiento',
            'MD_CANT' => 'Cantidad',
            'MD_FECVEN' => 'Fecha de vencimiento',
            'MD_CODMON' => 'Monodroga',
            'MD_DEPOSITO' => 'Depósito',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonodroga()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'MD_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'MD_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigo()
    {
        return $this->hasOne(Movimientos_tipos::className(), ['MS_COD' => 'MD_CODMOV']);
    }

    public function getListaDeposito()
    {
        $opciones = Deposito::find();
        $depositos = Yii::$app->params['depositos_farmacia'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }
}
