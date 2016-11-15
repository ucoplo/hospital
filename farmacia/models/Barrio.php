<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "barrios".
 *
 * @property integer $BA_CODIGO
 * @property string $BA_NOMBRE
 */
class Barrio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'barrios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['BA_NOMBRE'], 'required'],
            [['BA_NOMBRE'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'BA_CODIGO' => 'Ba  Codigo',
            'BA_NOMBRE' => 'Ba  Nombre',
        ];
    }
}
