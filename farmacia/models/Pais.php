<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "paises".
 *
 * @property string $PA_COD
 * @property string $PA_DETALLE
 */
class Pais extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paises';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PA_COD'], 'string', 'max' => 3],
            [['PA_DETALLE'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PA_COD' => 'Código',
            'PA_DETALLE' => 'Detalle',
        ];
    }

    public static function primaryKey()
    {
        return ['PA_COD'];
    }
}
