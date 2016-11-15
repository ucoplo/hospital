<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "partido".
 *
 * @property string $PT_COD
 * @property string $PT_DETALLE
 */
class Partido extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partido';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PT_COD'], 'string', 'max' => 3],
            [['PT_DETALLE'], 'string', 'max' => 35],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PT_COD' => 'Código',
            'PT_DETALLE' => 'Nombre',
        ];
    }

    public static function primaryKey()
    {
        return ['PT_COD'];
    }
}
