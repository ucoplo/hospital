<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "ocupacion".
 *
 * @property string $OC_COD
 * @property string $OC_DESCRI
 */
class Ocupacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ocupacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['OC_DESCRI'], 'required'],
            [['OC_COD'], 'string', 'max' => 2],
            [['OC_DESCRI'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'OC_COD' => 'Código',
            'OC_DESCRI' => 'Descripción',
        ];
    }

    public static function primaryKey()
    {
        return ['OC_COD'];
    }
}
