<?php

namespace deposito_central\models;

use Yii;

/**
 * This is the model class for table "movsto".
 *
 * @property string $DM_COD
 * @property string $DM_NOM
 * @property integer $DM_SIGNO
 * @property integer $DM_VALIDO
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
        return 'dc_movsto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DM_COD', 'DM_NOM', 'DM_SIGNO', 'DM_VALIDO'], 'required'],
            [['DM_SIGNO', 'DM_VALIDO'], 'integer'],
            [['DM_COD'], 'string', 'max' => 1],
            [['DM_NOM'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DM_COD' => 'Ms  Cod',
            'DM_NOM' => 'Ms  Nom',
            'DM_SIGNO' => 'Ms  Signo',
            'DM_VALIDO' => 'Ms  Valido',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovDias()
    {
        return $this->hasMany(Movimientos_diarios::className(), ['DM_CODMOV' => 'DM_COD']);
    }

    public function obtenerSigno($codigo){
        
    }
}
