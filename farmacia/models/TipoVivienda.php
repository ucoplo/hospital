<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "tip_vivienda".
 *
 * @property string $TV_CODIGO
 * @property string $TV_DETALLE
 */
class TipoVivienda extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tip_vivienda';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TV_CODIGO'], 'string', 'max' => 1],
            [['TV_DETALLE'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TV_CODIGO' => 'Código',
            'TV_DETALLE' => 'Detalle',
        ];
    }

    public static function primaryKey()
    {
        return ['TV_CODIGO'];
    }
}
