<?php

namespace deposito_central\models;

use Yii;

/**
 * This is the model class for table "clases".
 *
 * @property string $CL_COD
 * @property string $CL_NOM
 *
 * @property ArticGral[] $articGrals
 * @property PedentreClases[] $pedentreClases
 * @property Pedentre[] $pENROPEDs
 * @property Droga[] $drogas
 */
class Clases extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clases';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CL_COD', 'CL_NOM'], 'required'],
            [['CL_COD'], 'unique'],
            [['CL_COD'], 'string', 'max' => 2],
            [['CL_NOM'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CL_COD' => 'Código',
            'CL_NOM' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticGrals()
    {
        return $this->hasMany(ArticGral::className(), ['AG_CODCLA' => 'CL_COD']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDrogas()
    {
        return $this->hasMany(Droga::className(), ['DR_CLASE' => 'CL_COD']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedentreClases()
    {
        return $this->hasMany(PedentreClases::className(), ['PE_CLASE' => 'CL_COD']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPENROPEDs()
    {
        return $this->hasMany(Pedentre::className(), ['PE_NROPED' => 'PE_NROPED'])->viaTable('pedentre_clases', ['PE_CLASE' => 'CL_COD']);
    }
}

