<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "movst_qui".
 *
 * @property string $ MS_COD
 * @property string $ MS_NOM
 * @property integer $ MS_SIGNO
 * @property integer $ MS_VALIDO
 *
 * @property MovQuiro[] $movQuiros
 */
class Tipo_movimientos_quirofano extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'movst_qui';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MS_COD'], 'required'],
            [['MS_SIGNO', 'MS_VALIDO'], 'integer'],
            [['MS_COD'], 'string', 'max' => 1],
            [['MS_NOM'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'MS_COD' => 'Ms  Cod',
            'MS_NOM' => 'Ms  Nom',
            'MS_SIGNO' => 'Ms  Signo',
            'MS_VALIDO' => 'Ms  Valido',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovQuiros()
    {
        return $this->hasMany(MovQuiro::className(), ['MO_TIPMOV' => 'MS_COD']);
    }
}
