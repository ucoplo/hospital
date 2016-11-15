<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "mov_quiro".
 *
 * @property string $MO_IDFOJA
 * @property string $MO_FECHA
 * @property string $MO_HORA
 * @property string $MO_DEPOSITO
 * @property string $MO_CODART
 * @property string $MO_SECTOR
 * @property string $MO_CANTIDA
 * @property string $MO_DESCART
 * @property string $MO_TIPMOV
 *
 * @property ArticGral $mOCODART
 * @property MovstQui $mOTIPMOV
 * @property Servicio $mOSECTOR
 */
class Movimientos_quirofano extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mov_quiro';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MO_IDFOJA'], 'integer'],
            [['MO_FECHA', 'MO_HORA', 'MO_DEPOSITO', 'MO_CODART', 'MO_SECTOR', 'MO_TIPMOV'], 'required'],
            [['MO_FECHA', 'MO_HORA'], 'safe'],
            [['MO_CANTIDA', 'MO_DESCART'], 'number'],
            [['MO_DEPOSITO'], 'string', 'max' => 2],
            [['MO_CODART'], 'string', 'max' => 4],
            [['MO_SECTOR'], 'string', 'max' => 3],
            [['MO_TIPMOV'], 'string', 'max' => 1],
            [['MO_CODART'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['MO_CODART' => 'AG_CODIGO']],
            [['MO_TIPMOV'], 'exist', 'skipOnError' => true, 'targetClass' => Tipo_movimientos_quirofano::className(), 'targetAttribute' => ['MO_TIPMOV' => 'MS_COD']],
            [['MO_SECTOR'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['MO_SECTOR' => 'SE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'MO_IDFOJA' => 'Foja',
            'MO_FECHA' => 'Fecha',
            'MO_HORA' => 'Hora',
            'MO_DEPOSITO' => 'Depósito',
            'MO_CODART' => 'Artículo',
            'MO_SECTOR' => 'Servicio ',
            'MO_CANTIDA' => 'Cantidad solicitada',
            'MO_DESCART' => 'Cantidad descartada',
            'MO_TIPMOV' => 'Tipo Movimiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMOCODART()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'MO_CODART']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMOTIPMOV()
    {
        return $this->hasOne(Tipo_movimientos_quirofano::className(), ['MS_COD' => 'MO_TIPMOV']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMOSECTOR()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'MO_SECTOR']);
    }
}
