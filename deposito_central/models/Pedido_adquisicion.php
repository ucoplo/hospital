<?php

namespace deposito_central\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "ped_adq".
 *
 * @property string $PE_FECHA
 * @property integer $PE_NUM
 * @property string $PE_HORA
 * @property string $PE_COSTO
 * @property string $PE_REFERENCIA
 * @property string $PE_NROEXP
 * @property string $PE_FECADJ
 * @property string $PE_DEPOSITO
 * @property string $PE_ARTDES
 * @property string $PE_ARTHAS
 * @property string $PE_CLASES
 * @property string $PE_TIPO
 * @property integer $PE_EXISACT
 * @property integer $PE_PEDPEND
 * @property string $PE_PONDHIS
 * @property string $PE_PONDPUN
 * @property integer $PE_CLASABC
 * @property integer $PE_DIASABC
 * @property integer $PE_DIASPREVIS
 * @property integer $PE_DIASDEMORA
 *
 * @property OrdenesCompra[] $ordenesCompras
 * @property PeadMov[] $peadMovs
 */
class Pedido_adquisicion extends \yii\db\ActiveRecord
{
    public $renglones;
    public $clases;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ped_adq';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PE_FECHA', 'PE_HORA', 'PE_COSTO', 'PE_REFERENCIA', 'PE_DEPOSITO', 'PE_TIPO', 'PE_EXISACT', 'PE_PEDPEND', 'PE_PONDHIS', 'PE_PONDPUN', 'PE_CLASABC', 'PE_DIASABC', 'PE_DIASPREVIS', 'PE_DIASDEMORA','renglones'], 'required'],
            [['PE_FECHA', 'PE_HORA', 'PE_FECADJ','PE_CLASES','renglones'], 'safe'],
            [['PE_COSTO', 'PE_PONDHIS', 'PE_PONDPUN'], 'number'],
            [['PE_REFERENCIA'], 'string'],
            [['PE_EXISACT', 'PE_PEDPEND', 'PE_CLASABC', 'PE_DIASABC', 'PE_DIASPREVIS', 'PE_DIASDEMORA'], 'integer'],
            [['PE_NROEXP'], 'string', 'max' => 10],
            [['PE_DEPOSITO'], 'string', 'max' => 2],
            [['PE_ARTDES', 'PE_ARTHAS'], 'string', 'max' => 4],
            //[['PE_CLASES'], 'string', 'max' => 120],
            [['PE_TIPO'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PE_NUM' => 'Número',
            'PE_FECHA' => 'Fecha',
            'PE_HORA' => 'Hora',
            'PE_COSTO' => 'Costo total',
            'PE_REFERENCIA' => 'Referencia libre',
            'PE_NROEXP' => 'Número de expediente, una vez caratulado',
            'PE_FECADJ' => 'Fecha de adjudicación',
            'PE_DEPOSITO' => 'Depósito',
            'PE_ARTDES' => 'Artículo desde',
            'PE_ARTHAS' => 'Artículo hasta',
            'PE_CLASES' => 'Clases',
            'PE_TIPO' => 'Sólo Activos',
            'PE_EXISACT' => 'Considera la existencia actual',
            'PE_PEDPEND' => 'Considera las cantidades pendientes de entrega',
            'PE_PONDHIS' => 'Ponderación consumo histórico',
            'PE_PONDPUN' => 'Ponderación consumo puntual',
            'PE_CLASABC' => 'Filtrar por clase A,B ó C',
            'PE_DIASABC' => 'Cantidad de días calculó A, B o C',
            'PE_DIASPREVIS' => 'Días de previsión',
            'PE_DIASDEMORA' => 'Días que se estima que demorará el trámite',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenesCompras()
    {
        return $this->hasMany(OrdenCompra::className(), ['OC_PEDADQ' => 'PE_NUM']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenglones()
    {
        return $this->hasMany(Pedido_adquisicion_renglones::className(), ['PE_NUM' => 'PE_NUM']);
    }
     public static function getListaClases()
    {
        $opciones = Clases::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'CL_COD', 'CL_NOM');
    }
     public static function getListaDeposito()
    {
        $opciones = Deposito::find();//->asArray()->all();
        $depositos = Yii::$app->params['depositos_central'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }
}
