<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "serv_soc_tip_ocupac".
 *
 * @property string $cod
 * @property string $descri
 */
class SituacionLaboral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'serv_soc_tip_ocupac';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod', 'descri'], 'required'],
            [['cod'], 'string', 'max' => 3],
            [['descri'], 'string', 'max' => 75],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod' => 'Código',
            'descri' => 'Descripción',
        ];
    }
}
