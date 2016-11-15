<?php

namespace farmacia\models;

use Yii;

use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "droga".
 *
 * @property string $DR_CODIGO
 * @property string $DR_DESCRI
 * @property string $DR_CLASE
 *
 * @property Clases $dRCLASE
 */
class Droga extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'droga';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DR_CODIGO'], 'required'],
            [['DR_CODIGO'], 'unique'],
            [['DR_DESCRI'], 'string'],
            [['DR_CODIGO'], 'string', 'max' => 4],
            [['DR_CLASE'], 'string', 'max' => 2],
            [['DR_CLASE'], 'exist', 'skipOnError' => true, 'targetClass' => Clases::className(), 'targetAttribute' => ['DR_CLASE' => 'CL_COD']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DR_CODIGO' => 'Código',
            'DR_DESCRI' => 'Descripción',
            'DR_CLASE' => 'Clase',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase()
    {
        return $this->hasOne(Clases::className(), ['CL_COD' => 'DR_CLASE']);
    }

    public static function getListaClases()
    {
        $opciones = Clases::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'CL_COD', 'CL_NOM');
    }
}
