<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "movsto".
 *
 * @property string $MS_COD
 * @property string $MS_NOM
 * @property integer $MS_SIGNO
 * @property integer $MS_VALIDO
 *
 * @property MovDia[] $movDias
 */
class Movimientos_tipos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'movsto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MS_COD', 'MS_NOM', 'MS_SIGNO', 'MS_VALIDO'], 'required'],
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
    public function getMovDias()
    {
        return $this->hasMany(MovDia::className(), ['MD_CODMOV' => 'MS_COD']);
    }

    public function obtenerSigno($codigo){
        
    }
}
