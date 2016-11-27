<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pedentre".
 *
 * @property integer $PE_NROPED
 * @property string $PE_FECHA
 * @property string $PE_HORA
 * @property string $PE_SERSOL
 * @property string $PE_DEPOSITO
 * @property string $PE_REFERENCIA
 * @property string $PE_CLASE
 * @property string $PE_SUPERV
 * @property string $PE_PROCESADO
 *
 * @property Deposito $pEDEPOSITO
 * @property Legajos $pESUPERV
 * @property Servicio $pESERSOL
 * @property PeenMov[] $peenMovs
 */
class Pedentre_2 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pedentre';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PE_FECHA'], 'required'],
            [['PE_FECHA', 'PE_HORA'], 'safe'],
            [['PE_REFERENCIA', 'PE_PROCESADO'], 'string'],
            [['PE_SERSOL'], 'string', 'max' => 3],
            [['PE_DEPOSITO'], 'string', 'max' => 2],
            [['PE_CLASE'], 'string', 'max' => 80],
            [['PE_SUPERV'], 'string', 'max' => 6],
            [['PE_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['PE_DEPOSITO' => 'DE_CODIGO']],
            [['PE_SUPERV'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['PE_SUPERV' => 'LE_NUMLEGA']],
            [['PE_SERSOL'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['PE_SERSOL' => 'SE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PE_NROPED' => 'Pe  Nroped',
            'PE_FECHA' => 'Pe  Fecha',
            'PE_HORA' => 'Pe  Hora',
            'PE_SERSOL' => 'Pe  Sersol',
            'PE_DEPOSITO' => 'Pe  Deposito',
            'PE_REFERENCIA' => 'Pe  Referencia',
            'PE_CLASE' => 'Pe  Clase',
            'PE_SUPERV' => 'Pe  Superv',
            'PE_PROCESADO' => 'Pe  Procesado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPEDEPOSITO()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'PE_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPESUPERV()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'PE_SUPERV']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPESERSOL()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'PE_SERSOL']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeenMovs()
    {
        return $this->hasMany(PeenMov::className(), ['PE_NROPED' => 'PE_NROPED']);
    }
}
