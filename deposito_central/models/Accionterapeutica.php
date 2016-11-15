<?php

namespace deposito_central\models;

use Yii;

/**
 * This is the model class for table "acciont".
 *
 * @property string $AC_COD
 * @property string $AC_DESCRI
 */
class Accionterapeutica extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acciont';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AC_COD'], 'unique'],
            [['AC_COD', 'AC_DESCRI'], 'required'],
            [['AC_COD'], 'string', 'max' => 3],
            [['AC_DESCRI'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AC_COD' => 'Código',
            'AC_DESCRI' => 'Descripción',
        ];
    }
}
