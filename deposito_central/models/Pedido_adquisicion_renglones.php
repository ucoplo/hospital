<?php

namespace deposito_central\models;

use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "pead_mov".
 *
 * @property integer $PE_NUM
 * @property integer $PE_NRORENG
 * @property string $PE_CODART
 * @property string $PE_DEPOSITO
 * @property string $PE_CLASE
 * @property integer $PE_CANT
 * @property string $PE_PRECIO
 * @property integer $PE_REDONDEO
 * @property integer $ PE_CANTPED
 * @property integer $ PE_SUGERIDO
 * @property integer $ PE_EXISTENCIA
 * @property integer $ PE_PENDIENTE
 * @property string $ PE_CONSUMO
 *
 * @property ArticGral $pECODART
 * @property Clases $pECLASE
 * @property Deposito $pEDEPOSITO
 * @property PedAdq $pENUM
 */
class Pedido_adquisicion_renglones extends \yii\db\ActiveRecord
{
    public $descripcion,$clase,$cantidad_sugerida,$cons_puntual,$cons_historico,
            $existencia,$pendiente_entrega,$cantidad_pack,$precio;
       
    public $BUSCAR_ARTICULO = ''; // es el nombre del material.
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pead_mov';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PE_NUM', 'PE_NRORENG'], 'required'],
            [['PE_NUM', 'PE_NRORENG', 'PE_CANT', 'PE_REDONDEO', 'PE_CANTPED', 'PE_SUGERIDO', 'PE_EXISTENCIA', 'PE_PENDIENTE'], 'integer'],
            [['PE_PRECIO', 'PE_CONSUMO'], 'number'],
            [['PE_CODART'], 'string', 'max' => 4],
            [['PE_DEPOSITO', 'PE_CLASE'], 'string', 'max' => 2],
            [['PE_CODART', 'PE_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['PE_CODART' => 'AG_CODIGO', 'PE_DEPOSITO' => 'AG_DEPOSITO']],
            [['PE_CLASE'], 'exist', 'skipOnError' => true, 'targetClass' => Clases::className(), 'targetAttribute' => ['PE_CLASE' => 'CL_COD']],
            [['PE_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['PE_DEPOSITO' => 'DE_CODIGO']],
            [['PE_NUM'], 'exist', 'skipOnError' => true, 'targetClass' => Pedido_adquisicion::className(), 'targetAttribute' => ['PE_NUM' => 'PE_NUM']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PE_NUM' => 'Número del pedido',
            'PE_NRORENG' => 'Número de renglón',
            'PE_CODART' => 'Código del artículo ',
            'PE_DEPOSITO' => 'Código del depósito',
            'PE_CLASE' => 'Clase del artículo',
            'PE_CANT' => 'Cantidad pendiente de entrega',
            'PE_PRECIO' => 'Precio unitario',
            'PE_REDONDEO' => 'Redondeo',
            'PE_CANTPED' => 'Cantidad pedida definitiva',
            'PE_SUGERIDO' => 'Cantidad sugerida',
            'PE_EXISTENCIA' => 'Existencia al momento de generar el pedido',
            'PE_PENDIENTE' => 'Cantidad pendiente al momento de generar el pedido',
            'PE_CONSUMO' => 'Consumo promedio que se utilizo al generar el pedido',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticulo()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'PE_CODART', 'AG_DEPOSITO' => 'PE_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPECLASE()
    {
        return $this->hasOne(Clases::className(), ['CL_COD' => 'PE_CLASE']);
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
    public function getPENUM()
    {
        return $this->hasOne(Pedido_adquisicion::className(), ['PE_NUM' => 'PE_NUM']);
    }

    public function get_renglones($id=0)
    {
        $query = Pedido_adquisicion_renglones::find();

        // add conditions that should always apply here
        $query->andFilterWhere([
            'PE_NUM' => $id,
        ]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
        ]);

        return $dataProvider;   
    }
}
