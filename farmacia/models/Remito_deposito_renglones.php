<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "rs_reng".
 *
 * @property string $RS_CODEP
 * @property integer $RS_NROREM
 * @property integer $RS_NUMRENG
 * @property string $RS_CODMON
 * @property string $RS_CANTID
 * @property string $RS_FECVTO
 * @property string $RS_VALULTCOMP
 *
 * @property ArticGral $rSCODMON
 * @property Deposito $rSCODEP
 * @property RsEncab $rSNROREM
 */
class Remito_deposito_renglones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rs_reng';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RS_NROREM', 'RS_NUMRENG'], 'required'],
            [['RS_NROREM', 'RS_NUMRENG'], 'integer'],
            [['RS_CANTID', 'RS_VALULTCOMP'], 'number'],
            [['RS_FECVTO'], 'safe'],
            [['RS_CODEP'], 'string', 'max' => 2],
            [['RS_CODMON'], 'string', 'max' => 4],
            [['RS_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['RS_CODMON' => 'AG_CODIGO']],
            [['RS_CODEP'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['RS_CODEP' => 'DE_CODIGO']],
            [['RS_NROREM'], 'exist', 'skipOnError' => true, 'targetClass' => Remito_deposito::className(), 'targetAttribute' => ['RS_NROREM' => 'RS_NROREM']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RS_CODEP' => 'Rs  Codep',
            'RS_NROREM' => 'Rs  Nrorem',
            'RS_NUMRENG' => 'Rs  Numreng',
            'RS_CODMON' => 'Rs  Codmon',
            'RS_CANTID' => 'Rs  Cantid',
            'RS_FECVTO' => 'Rs  Fecvto',
            'RS_VALULTCOMP' => 'Rs  Valultcomp',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRSCODMON()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'RS_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRSCODEP()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'RS_CODEP']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRSNROREM()
    {
        return $this->hasOne(Remito_deposito::className(), ['RS_NROREM' => 'RS_NROREM']);
    }
}
