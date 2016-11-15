<?php

namespace deposito_central\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "topeart".
 *
 * @property string $TA_CODSERV
 * @property string $TA_DEPOSITO
 * @property string $TA_CODART
 * @property string $TA_CANTID
 *
 * @property ArticGral $tACODART
 * @property Servicio $tACODSERV
 */
class Techo_articulo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'topeart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TA_CODSERV', 'TA_DEPOSITO', 'TA_CODART'], 'required'],
            [['TA_CANTID'], 'number'],
            [['TA_CODSERV'], 'string', 'max' => 3],
            [['TA_DEPOSITO'], 'string', 'max' => 2],
            [['TA_CODART'], 'string', 'max' => 4],
            [['TA_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 
                    'targetAttribute' => ['TA_CODART' => 'AG_CODIGO', 'TA_DEPOSITO' => 'AG_DEPOSITO']
                    ,'message'=>'No existe el artículo en el Depósito seleccionado'],
            [['TA_CODSERV'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['TA_CODSERV' => 'SE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TA_CODSERV' => 'Servicio',
            'TA_DEPOSITO' => 'Depósito',
            'TA_CODART' => 'Artículo',
            'TA_CANTID' => 'Cantidad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticulo()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'TA_CODART', 'AG_DEPOSITO' => 'TA_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicio()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'TA_CODSERV']);
    }

     public static function getListaServicio()
    {
        $opciones = Servicio::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'SE_CODIGO', 'SE_DESCRI');
    }

    public static function getListaDeposito()
    {
        $opciones = Deposito::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

     public static function getListaMonodroga()
    {
        $opciones = ArticGral::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'AG_CODIGO', 'AG_NOMBRE');
    }
}
