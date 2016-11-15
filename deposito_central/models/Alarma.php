<?php

namespace deposito_central\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "alarmas".
 *
 * @property string $AL_CODMON
 * @property string $AL_DEPOSITO
 * @property integer $AL_MIN
 * @property integer $AL_MAX
 *
 * @property ArticGral $aLCODMON
 * @property Deposito $aLDEPOSITO
 */
class Alarma extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alarmas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AL_CODMON', 'AL_DEPOSITO'], 'required'],
            [['AL_DEPOSITO'], 'unique', 'targetAttribute' => ['AL_CODMON', 'AL_DEPOSITO'],'message'=>'Esta combinación Depósito-Monodroga ya ha sido ingresada'],
            [['AL_MIN', 'AL_MAX'], 'integer'],
            [['AL_CODMON'], 'string', 'max' => 4],
            [['AL_DEPOSITO'], 'string', 'max' => 2],
            [['AL_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => '\deposito_central\models\ArticGral', 
                    'targetAttribute' => ['AL_CODMON' => 'AG_CODIGO', 'AL_DEPOSITO' => 'AG_DEPOSITO']
                    ,'message'=>'No existe la Monodroga en el Dépósito seleccionado'],
            [['AL_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['AL_DEPOSITO' => 'DE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AL_CODMON' => 'Código Monodroga',
            'AL_DEPOSITO' => 'Depósito',
            'AL_MIN' => 'Punto mínimo de consumo normal semanal ',
            'AL_MAX' => 'Consumo máximo normal semanal',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonodroga()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'AL_CODMON', 'AG_DEPOSITO' => 'AL_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'AL_DEPOSITO']);
    }
    public function getListaDepositos()
    {
        $opciones = Deposito::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

    public function getListaMonodrogas()
    {
        $opciones = ArticGral::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'AG_CODIGO', 'AG_NOMBRE');
    }
}
