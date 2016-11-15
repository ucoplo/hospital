<?php

namespace farmacia\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "pedentre".
 *
 * @property integer $PE_NROPED
 * @property string $PE_FECHA
 * @property string $PE_HORA
 * @property string $PE_SERSOL
 * @property string $PE_DEPOSITO
 * @property string $PE_REFERENCIA
 * @property string $PE_CLASE
 * @property string $PE_SUPERV
 * @property string $PE_PROCESADO
 *
 * @property Deposito $pEDEPOSITO
 * @property PeenMov[] $peenMovs
 */
class Pedentre extends \yii\db\ActiveRecord
{   
    public $dias_reponer; 
    public $incluye_demanda_insatisfecha;
    public $pedido_renglones;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pedentre';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           [['PE_NROPED','PE_FECHA','PE_DEPOSITO','PE_FECHA','PE_CLASE','dias_reponer','incluye_demanda_insatisfecha','PE_REFERENCIA','pedido_renglones'], 'required'],
            [['PE_FECHA'], 'safe'],
            [['PE_REFERENCIA', 'PE_PROCESADO'], 'string'],
            [['PE_HORA'], 'string', 'max' => 8],
            [['PE_SERSOL'], 'string', 'max' => 3],
            [['PE_DEPOSITO'], 'string', 'max' => 2],
            [['PE_SUPERV'], 'string', 'max' => 6],
            [['dias_reponer'], 'number'],
            [['PE_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['PE_DEPOSITO' => 'DE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PE_NROPED' => 'Número de pedido',
            'PE_FECHA' => 'Fecha del pedido',
            'PE_HORA' => 'Hora del pedido',
            'PE_SERSOL' => 'Servicio solicitante',
            'PE_DEPOSITO' => 'Depósito',
            'PE_REFERENCIA' => 'Referencia',
            'PE_CLASE' => 'Clases',
            'PE_SUPERV' => 'Personal de Enfermería',
            'PE_PROCESADO' => 'Indica si fue procesado o no',
            'dias_reponer' => 'Días a reponer',
            'pedido_renglones' => 'Renglones',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPEDEPOSITO()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'PE_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeenMovs()
    {
        return $this->hasMany(PeenMov::className(), ['PE_NROPED' => 'PE_NROPED']);
    }

    public static function getListaClases()
    {
        $opciones = Clases::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'CL_COD', 'CL_NOM');
    }

    public static function getListaDeposito()
    {
        $opciones = Deposito::find();//->asArray()->all();
        $depositos = Yii::$app->params['depositos_farmacia'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }
}
