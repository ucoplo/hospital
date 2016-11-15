<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "mot_perd".
 *
 * @property string $MP_COD
 * @property string $MP_NOM
 */
class Motivo_perdida extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mot_perd';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MP_COD', 'MP_NOM'], 'required'],
            [['MP_COD'], 'unique'],
            [['MP_COD'], 'string', 'max' => 4],
            [['MP_NOM'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'MP_COD' => 'Código',
            'MP_NOM' => 'Descripción',
        ];
    }
}
