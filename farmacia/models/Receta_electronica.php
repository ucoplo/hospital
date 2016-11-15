<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "recetas_enc".
 *
 * @property integer $RE_NRORECETA
 * @property integer $RE_HISCLI
 * @property string $RE_FECINI
 * @property string $RE_FECFIN
 * @property string $RE_MEDICO
 * @property string $RE_NOTA
 *
 * @property Paciente $rEHISCLI
 * @property RecetasReng[] $recetasRengs
 */
class Receta_electronica extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'recetas_enc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RE_HISCLI', 'RE_FECINI', 'RE_FECFIN', 'RE_MEDICO', 'RE_NOTA'], 'required'],
            [['RE_HISCLI'], 'integer'],
            [['RE_FECINI', 'RE_FECFIN'], 'safe'],
            [['RE_NOTA'], 'string'],
            [['RE_MEDICO'], 'string', 'max' => 6],
            [['RE_HISCLI'], 'exist', 'skipOnError' => true, 'targetClass' => Paciente::className(), 'targetAttribute' => ['RE_HISCLI' => 'PA_HISCLI']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RE_NRORECETA' => 'Número',
            'RE_HISCLI' => 'Historia Clínica del Paciente',
            'RE_FECINI' => 'Fecha de inicio',
            'RE_FECFIN' => 'Fecha de fin',
            'RE_MEDICO' => 'Matrícula del Médico',
            'RE_NOTA' => 'Narrativa',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getREHISCLI()
    {
        return $this->hasOne(Paciente::className(), ['PA_HISCLI' => 'RE_HISCLI']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecetasRengs()
    {
        return $this->hasMany(RecetasReng::className(), ['RE_NRORECETA' => 'RE_NRORECETA']);
    }
}
