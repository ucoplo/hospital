<?php

namespace deposito_central\models;

use Yii;

/**
 * This is the model class for table "rs_encab".
 *
 * @property string $RS_CODEP
 * @property integer $RS_NROREM
 * @property string $RS_FECHA
 * @property string $RS_HORA
 * @property string $RS_CODOPE
 * @property integer $RS_NUMPED
 * @property string $RS_SERSOL
 * @property string $RS_IMPORT
 *
 * @property Deposito $rSCODEP
 * @property Servicio $rSSERSOL
 * @property RsReng[] $rsRengs
 */
class Remito_deposito extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rs_encab';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RS_FECHA', 'RS_HORA'], 'safe'],
            [['RS_NUMPED'], 'integer'],
            [['RS_IMPORT'], 'string'],
            [['RS_CODEP'], 'string', 'max' => 2],
            [['RS_CODOPE'], 'string', 'max' => 6],
            [['RS_SERSOL'], 'string', 'max' => 3],
            [['RS_CODEP'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['RS_CODEP' => 'DE_CODIGO']],
            [['RS_SERSOL'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['RS_SERSOL' => 'SE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RS_CODEP' => 'Código del depósito',
            'RS_NROREM' => 'Número Remito',
            'RS_FECHA' => 'Fecha',
            'RS_HORA' => 'Hora',
            'RS_CODOPE' => 'Personal Depósito Central',
            'RS_NUMPED' => 'Número Pedido',
            'RS_SERSOL' => 'Servicio Solicitante',
            'RS_IMPORT' => 'Indica si está importado o no',
        ];
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
    public function getRSSERSOL()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'RS_SERSOL']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRsRengs()
    {
        return $this->hasMany(Remito_deposito_renglones::className(), ['RS_NROREM' => 'RS_NROREM']);
    }
}
