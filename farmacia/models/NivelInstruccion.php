<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "nivinst".
 *
 * @property integer $NI_CODIGO
 * @property string $NI_DETALLE
 */
class NivelInstruccion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nivinst';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NI_DETALLE'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'NI_CODIGO' => 'Ni  Codigo',
            'NI_DETALLE' => 'Ni  Detalle',
        ];
    }

    public static function primaryKey()
    {
        return ['NI_CODIGO'];
    }
}
