<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "nacional".
 *
 * @property string $NA_COD
 * @property string $NA_DETALLE
 * @property string $NA_CODOP
 * @property string $NA_MODIF
 */
class Nacionalidad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nacional';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NA_COD'], 'string', 'max' => 2],
            [['NA_DETALLE'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'NA_COD' => 'Na  Cod',
            'NA_DETALLE' => 'Na  Detalle',
        ];
    }

    public static function primaryKey()
    {
        return ['NA_COD'];
    }
}
