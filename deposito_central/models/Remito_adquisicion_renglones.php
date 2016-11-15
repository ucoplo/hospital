<?php

namespace deposito_central\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "rem_mov".
 *
 * @property integer $AR_RENUM
 * @property string $AR_DEPOSITO
 * @property integer $AR_NROREN
 * @property string $AR_CODART
 * @property string $AR_PRECIO
 * @property string $AR_CANTID
 * @property string $AR_FECVTO
 *
 * @property ArticGral $rMCODMON
 * @property Deposito $rMDEPOSITO
 * @property FaRemit $rMRENUM
 */
class Remito_adquisicion_renglones extends \yii\db\ActiveRecord
{
    public $precio_compra;
    /**
     * @inheritdoc
     */
    public $descripcion;
    
    public static function tableName()
    {
        return 'adq_reng';
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['AR_FECVTO'], // update 1 attribute 'created' OR multiple attribute ['created','updated']
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'AR_FECVTO', // update 1 attribute 'created' OR multiple attribute ['created','updated']
                ],
                'value' => function ($event) {
                    return date('Y-m-d', strtotime(str_replace("/","-",$this->AR_FECVTO)));
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
            [['AR_RENUM', 'AR_DEPOSITO', 'AR_NROREN', 'AR_CODART', 'AR_PRECIO', 'AR_CANTID', 'AR_FECVTO'], 'required'],
            [['AR_RENUM', 'AR_NROREN'], 'integer'],
            [['AR_PRECIO', 'AR_CANTID'], 'number'],
            [['AR_FECVTO'], 'safe'],
            [['AR_DEPOSITO'], 'string', 'max' => 2],
            [['AR_CODART'], 'string', 'max' => 4],
            [['AR_CODART'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['AR_CODART' => 'AG_CODIGO']],
            [['AR_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['AR_DEPOSITO' => 'DE_CODIGO']],
            [['AR_RENUM'], 'exist', 'skipOnError' => true, 'targetClass' => Remito_adquisicion::className(), 'targetAttribute' => ['AR_RENUM' => 'RA_NUM']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AR_RENUM' => 'Número de remito',
            'AR_DEPOSITO' => 'Código del subdepósito de deposito_central',
            'AR_NROREN' => 'Número renglon',
            'AR_CODART' => 'Código de artículo',
            'AR_PRECIO' => 'Precio de la compra',
            'AR_CANTID' => 'Cantidad entregada',
            'AR_FECVTO' => 'Fecha de vencimiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonodroga()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'AR_CODART']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRMDEPOSITO()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'AR_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemito()
    {
        return $this->hasOne(Remito_adquisicion::className(), ['RA_NUM' => 'AR_RENUM']);
    }

     public static function getListaMonodrogas()
    {
        $opciones = ArticGral::find()->select(['AG_CODIGO', new \yii\db\Expression("CONCAT(`AG_CODIGO`, ' - ', `AG_NOMBRE`) as desc")]);
        //$depositos = Yii::$app->params['depositos_deposito_central'];
        //$opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        print_r($opciones);
        return ArrayHelper::map($opciones, 'AG_CODIGO', 'desc');
    }


    public function get_renglones($id=0)
    {
        $query = Remito_adquisicion_renglones::find();

        // add conditions that should always apply here
        $query->andFilterWhere([
            'AR_RENUM' => $id,
        ]);

        $query->orderBy([
           'AR_NROREN'=>SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
        ]);

        return $dataProvider;   
    }
    
}
