<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "labo".
 *
 * @property string $LA_CODIGO
 * @property string $LA_NOMBRE
 * @property string $LA_TIPO
 *
 * @property Medic[] $medics
 */
class Labo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'labo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['LA_CODIGO', 'LA_NOMBRE'], 'required'],
            [['LA_CODIGO'], 'unique'],
            [['LA_CODIGO'], 'string', 'max' => 5],
            [['LA_NOMBRE'], 'string', 'max' => 40],
            [['LA_TIPO'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'LA_CODIGO' => 'Código',
            'LA_NOMBRE' => 'Descripción',
            'LA_TIPO' => 'Tipo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedics()
    {
        return $this->hasMany(Medic::className(), ['ME_CODLAB' => 'LA_CODIGO']);
    }

    public function tipo_descripcion()
    {
        if ($this->LA_TIPO=='i')
            return 'interno';
        elseif ($this->LA_TIPO=='e')
            return 'externo';
        else
            return 'indefinido'; 
    }
    

    
}
