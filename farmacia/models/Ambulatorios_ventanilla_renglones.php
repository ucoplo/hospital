<?php

namespace farmacia\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ambu_ren".
 *
 * @property integer $AM_NUMVALE
 * @property integer $AM_NUMREN
 * @property string $AM_DEPOSITO
 * @property string $AM_CODMON
 * @property string $AM_CANTPED
 * @property string $AM_CANTENT
 * @property string $AM_FECVTO
 *
 * @property AmbuEnc $vale
 * @property ArticGral $aMCODMON
 * @property Deposito $aMDEPOSITO
 */
class Ambulatorios_ventanilla_renglones extends \yii\db\ActiveRecord
{
    public $descripcion;
    public $cant_acumulada;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ambu_ren';
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['AM_FECVTO'], // update 1 attribute 'created' OR multiple attribute ['created','updated']
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'AM_FECVTO', // update 1 attribute 'created' OR multiple attribute ['created','updated']
                ],
                'value' => function ($event) {
                    return date('Y-m-d', strtotime(str_replace("/","-",$this->AM_FECVTO)));
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
            //[['AM_NUMVALE', 'AM_NUMREN'], 'required'],
            [['AM_NUMVALE', 'AM_NUMREN'], 'integer'],
            [['AM_CANTPED', 'AM_CANTENT'], 'number'],
            [['AM_FECVTO'], 'safe'],
            [['AM_DEPOSITO'], 'string', 'max' => 2],
            [['AM_CODMON'], 'string', 'max' => 4],
            ['AM_CANTPED','compare','compareAttribute'=>'AM_CANTENT','operator'=>'>=','message'=>'Lo pedido debe ser mayor o igual a entregado'],

            //[['AM_NUMVALE'], 'exist', 'skipOnError' => true, 'targetClass' => Ambulatorios_ventanilla::className(), 'targetAttribute' => ['AM_NUMVALE' => 'AM_NUMVALE']],
            [['AM_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['AM_CODMON' => 'AG_CODIGO']],
            [['AM_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['AM_DEPOSITO' => 'DE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AM_NUMVALE' => 'Número de vale',
            'AM_NUMREN' => 'Número de renglón',
            'AM_DEPOSITO' => 'Subdeposito de farmacia',
            'AM_CODMON' => 'Código',
            'AM_CANTPED' => 'Cantidad pedida',
            'AM_CANTENT' => 'Cantidad entregada',
            'AM_FECVTO' => 'Fecha de vencimiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVale()
    {
        return $this->hasOne(Ambulatorios_ventanilla::className(), ['AM_NUMVALE' => 'AM_NUMVALE']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonodroga()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'AM_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'AM_DEPOSITO']);
    }

      //Obtiene todos los renglones de un Vale de Ventanilla
    public function get_renglones($id=0)
    {
        $query = Ambulatorios_ventanilla_renglones::find();

        // add conditions that should always apply here
        $query->andFilterWhere([
            'AM_NUMVALE' => $id,
        ]);

        $query->orderBy([
           'AM_NUMREN'=>SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
        ]);

        return $dataProvider;   
    }
}
