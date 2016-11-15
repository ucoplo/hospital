<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "movstosa".
 *
 * @property string $MS_COD
 * @property string $MS_NOM
 * @property integer $MS_SIGNO
 * @property integer $MS_VALIDO
 * @property integer $MS_ORDEN
 *
 * @property MovSala[] $movSalas
 */
class Tipo_movimientos_sala extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'movstosa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MS_COD', 'MS_NOM', 'MS_SIGNO', 'MS_VALIDO', 'MS_ORDEN'], 'required'],
            [['MS_SIGNO', 'MS_VALIDO', 'MS_ORDEN'], 'integer'],
            [['MS_COD'], 'string', 'max' => 1],
            [['MS_NOM'], 'string', 'max' => 35],
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
            'MS_ORDEN' => 'Ms  Orden',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovSalas()
    {
        return $this->hasMany(MovSala::className(), ['MO_TIPMOV' => 'MS_COD']);
    }
}
