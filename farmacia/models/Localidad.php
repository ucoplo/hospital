<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "locali".
 *
 * @property string $LO_COD
 * @property string $LO_DETALLE
 */
class Localidad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'locali';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['LO_COD'], 'string', 'max' => 3],
            [['LO_DETALLE'], 'string', 'max' => 35],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'LO_COD' => 'Código',
            'LO_DETALLE' => 'Detalle',
        ];
    }
}
