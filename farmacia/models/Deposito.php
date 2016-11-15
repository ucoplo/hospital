<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "deposito".
 *
 * @property string $DE_CODIGO
 * @property string $DE_DESCR
 *
 * @property ArticGral[] $articGrals
 * @property Medic[] $medics
 * @property Pedentre[] $pedentres
 * @property Topemedi[] $topemedis
 */
class Deposito extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deposito';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DE_CODIGO'], 'required'],
            [['DE_CODIGO'], 'unique'],
            [['DE_CODIGO'], 'string', 'max' => 2],
            [['DE_DESCR'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DE_CODIGO' => 'Código',
            'DE_DESCR' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticGrals()
    {
        return $this->hasMany(ArticGral::className(), ['AG_DEPOSITO' => 'DE_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedics()
    {
        return $this->hasMany(Medic::className(), ['ME_DEPOSITO' => 'DE_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedentres()
    {
        return $this->hasMany(Pedentre::className(), ['PE_DEPOSITO' => 'DE_CODIGO']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopemedis()
    {
        return $this->hasMany(Topemedi::className(), ['TM_DEPOSITO' => 'DE_CODIGO']);
    }
}
