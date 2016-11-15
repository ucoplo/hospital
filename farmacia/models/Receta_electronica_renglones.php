<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "recetas_reng".
 *
 * @property integer $RE_NRORECETA
 * @property string $RE_DEPOSITO
 * @property string $RE_CODMON
 * @property integer $RE_CANTDIA
 * @property string $RE_INDICACION
 * @property string $RE_DIAGNO
 *
 * @property ArticGral $rECODMON
 * @property Deposito $rEDEPOSITO
 * @property RecetasEnc $rENRORECETA
 */
class Receta_electronica_renglones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'recetas_reng';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RE_NRORECETA', 'RE_DEPOSITO', 'RE_CODMON'], 'required'],
            [['RE_NRORECETA', 'RE_CANTDIA'], 'integer'],
            [['RE_INDICACION'], 'string'],
            [['RE_DEPOSITO'], 'string', 'max' => 2],
            [['RE_CODMON'], 'string', 'max' => 6],
            [['RE_DIAGNO'], 'string', 'max' => 10],
            [['RE_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['RE_CODMON' => 'AG_CODIGO']],
            [['RE_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['RE_DEPOSITO' => 'DE_CODIGO']],
            [['RE_NRORECETA'], 'exist', 'skipOnError' => true, 'targetClass' => Receta_electronica::className(), 'targetAttribute' => ['RE_NRORECETA' => 'RE_NRORECETA']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RE_NRORECETA' => 'Número Receta',
            'RE_DEPOSITO' => 'Depósito',
            'RE_CODMON' => 'Medicamento',
            'RE_CANTDIA' => 'Dosis diaria',
            'RE_INDICACION' => 'Narrativa',
            'RE_DIAGNO' => 'Diagnóstico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRECODMON()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'RE_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getREDEPOSITO()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'RE_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRENRORECETA()
    {
        return $this->hasOne(Receta_electronica::className(), ['RE_NRORECETA' => 'RE_NRORECETA']);
    }
}
