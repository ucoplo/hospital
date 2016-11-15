<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "programa".
 *
 * @property string $PR_CODIGO
 * @property string $PR_NOMBRE
 *
 * @property AmbuEnc[] $ambuEncs
 * @property ProgMed[] $progMeds
 */
class Programa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'programa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PR_CODIGO'], 'required'],
            [['PR_CODIGO'], 'string', 'max' => 2],
            [['PR_NOMBRE'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PR_CODIGO' => 'Código',
            'PR_NOMBRE' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmbuEncs()
    {
        return $this->hasMany(AmbuEnc::className(), ['AM_PROG' => 'PR_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgMeds()
    {
        return $this->hasMany(ProgMed::className(), ['PM_CODPROG' => 'PR_CODIGO']);
    }
}
