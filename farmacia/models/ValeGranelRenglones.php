<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "vamo_ren".
 *
 * @property integer $VM_NUMVALE
 * @property integer $VM_NUMRENG
 * @property string $VM_CODMON
 * @property string $VM_DEPOSITO
 * @property string $VM_CANTID
 *
 * @property ArticGral $monodroga
 * @property Deposito $deposito
 * @property ValeMon $vale
 */
class ValeGranelRenglones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vamo_ren';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VM_NUMVALE', 'VM_NUMRENG', 'VM_CODMON', 'VM_DEPOSITO', 'VM_CANTID'], 'required'],
            [['VM_NUMVALE', 'VM_NUMRENG'], 'integer'],
            [['VM_CANTID'], 'number'],
            [['VM_CODMON'], 'string', 'max' => 4],
            [['VM_DEPOSITO'], 'string', 'max' => 2],
            [['VM_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['VM_CODMON' => 'AG_CODIGO']],
            [['VM_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['VM_DEPOSITO' => 'DE_CODIGO']],
            [['VM_NUMVALE'], 'exist', 'skipOnError' => true, 'targetClass' => ValeGranel::className(), 'targetAttribute' => ['VM_NUMVALE' => 'VM_NUMVALE']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'VM_NUMVALE' => 'Número de vale',
            'VM_NUMRENG' => 'Número de renglón',
            'VM_CODMON' => 'Código monodroga',
            'VM_DEPOSITO' => 'Depósito',
            'VM_CANTID' => 'Cantidad pedida',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonodroga()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'VM_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'VM_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVale()
    {
        return $this->hasOne(ValeGranel::className(), ['VM_NUMVALE' => 'VM_NUMVALE']);
    }
}
