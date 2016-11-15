<?php

namespace farmacia\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "rem_mov".
 *
 * @property integer $RM_RENUM
 * @property string $RM_DEPOSITO
 * @property integer $RM_NUMRENG
 * @property string $RM_CODMON
 * @property string $RM_PRECIO
 * @property string $RM_CANTID
 * @property string $RM_FECVTO
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
        return 'rem_mov';
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['RM_FECVTO'], // update 1 attribute 'created' OR multiple attribute ['created','updated']
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'RM_FECVTO', // update 1 attribute 'created' OR multiple attribute ['created','updated']
                ],
                'value' => function ($event) {
                    return date('Y-m-d', strtotime(str_replace("/","-",$this->RM_FECVTO)));
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
            [['RM_RENUM', 'RM_DEPOSITO', 'RM_NUMRENG', 'RM_CODMON', 'RM_PRECIO', 'RM_CANTID', 'RM_FECVTO'], 'required'],
            [['RM_RENUM', 'RM_NUMRENG'], 'integer'],
            [['RM_PRECIO', 'RM_CANTID'], 'number'],
            [['RM_FECVTO'], 'safe'],
            [['RM_DEPOSITO'], 'string', 'max' => 2],
            [['RM_CODMON'], 'string', 'max' => 4],
            [['RM_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['RM_CODMON' => 'AG_CODIGO']],
            [['RM_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['RM_DEPOSITO' => 'DE_CODIGO']],
            [['RM_RENUM'], 'exist', 'skipOnError' => true, 'targetClass' => Remito_adquisicion::className(), 'targetAttribute' => ['RM_RENUM' => 're_num']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RM_RENUM' => 'Número de remito',
            'RM_DEPOSITO' => 'Código del subdepósito de farmacia',
            'RM_NUMRENG' => 'Rm  Numreng',
            'RM_CODMON' => 'Código de monodroga',
            'RM_PRECIO' => 'Precio de la compra',
            'RM_CANTID' => 'Cantidad entregada',
            'RM_FECVTO' => 'Fecha de vencimiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonodroga()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'RM_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRMDEPOSITO()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'RM_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemito()
    {
        return $this->hasOne(Remito_adquisicion::className(), ['RE_NUM' => 'RM_RENUM']);
    }

     public static function getListaMonodrogas()
    {
        $opciones = ArticGral::find()->select(['AG_CODIGO', new \yii\db\Expression("CONCAT(`AG_CODIGO`, ' - ', `AG_NOMBRE`) as desc")]);
        //$depositos = Yii::$app->params['depositos_farmacia'];
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
            'RM_RENUM' => $id,
        ]);

        $query->orderBy([
           'RM_NUMRENG'=>SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
        ]);

        return $dataProvider;   
    }
    
}
