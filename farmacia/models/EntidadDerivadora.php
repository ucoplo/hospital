<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "enti_der".
 *
 * @property string $ED_COD
 * @property string $ED_DETALLE
 *
 * @property AmbuEnc[] $ambuEncs
 */
class EntidadDerivadora extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'enti_der';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ED_COD', 'ED_DETALLE'], 'required'],
            [['ED_COD'], 'string', 'max' => 3],
            [['ED_DETALLE'], 'string', 'max' => 35],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ED_COD' => 'Ed  Cod',
            'ED_DETALLE' => 'Ed  Detalle',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmbuEncs()
    {
        return $this->hasMany(AmbuEnc::className(), ['AM_ENTIDER' => 'ED_COD']);
    }
}
