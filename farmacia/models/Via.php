<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "vias".
 *
 * @property string $VI_CODIGO
 * @property string $VI_DESCRI
 */
class Via extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vias';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VI_CODIGO'], 'required'],
            [['VI_CODIGO'], 'unique'],
            [['VI_CODIGO'], 'string', 'max' => 2],
            [['VI_DESCRI'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'VI_CODIGO' => 'Código',
            'VI_DESCRI' => 'Descripción',
        ];
    }
}
