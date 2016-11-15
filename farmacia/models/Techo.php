<?php

namespace farmacia\models;

use Yii;
use farmacia\models\Servicio;
use farmacia\models\Deposito;
use farmacia\models\ArticGral;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "topemedi".
 *
 * @property integer $id_techo
 * @property string $TM_CODSERV
 * @property string $TM_DEPOSITO
 * @property string $TM_CODMON
 * @property string $TM_CANTID
 *
 * @property Deposito $tMDEPOSITO
 * @property Monod $tMCODMON
 * @property Servicio $tMCODSERV
 */
class Techo extends \yii\db\ActiveRecord
{
    public $AG_CODIGO,$AG_NOMBRE;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'topemedi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TM_CODSERV'], 'required'],
            [['TM_DEPOSITO'], 'required'],
            [['TM_CODSERV', 'TM_DEPOSITO','TM_CODMON'], 'unique', 'targetAttribute' => ['TM_CODSERV', 'TM_DEPOSITO','TM_CODMON']],
            [['TM_CODMON'], 'required'],
            [['TM_CANTID'], 'number'],
            [['TM_CODSERV'], 'string', 'max' => 3],
            [['TM_DEPOSITO'], 'string', 'max' => 2],
            [['TM_CODMON'], 'string', 'max' => 4],
            [['TM_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['TM_DEPOSITO' => 'DE_CODIGO']],
            [['TM_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['TM_CODMON' => 'AG_CODIGO']],
            [['TM_CODSERV'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['TM_CODSERV' => 'SE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_techo' => 'Id Techo',
            'TM_CODSERV' => 'Servicio',
            'TM_DEPOSITO' => 'Depósito',
            'TM_CODMON' => 'Monodroga',
            'TM_CANTID' => 'Cantidad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'TM_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonodroga()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'TM_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicio()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'TM_CODSERV']);
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
