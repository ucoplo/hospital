<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "provin".
 *
 * @property string $PR_COD
 * @property string $PR_DETALLE
 * @property string $PR_CODART
 * @property string $PR_MODIF
 * @property string $PR_CODOP
 */
class Provincia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'provin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PR_COD'], 'string', 'max' => 2],
            [['PR_DETALLE'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PR_COD' => 'Código',
            'PR_DETALLE' => 'Nombre',
        ];
    }

    public static function primaryKey()
    {
        return ['PR_COD'];
    }
}
