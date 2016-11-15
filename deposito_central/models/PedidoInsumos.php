<?php

namespace deposito_central\models;

use Yii;
use yii\validators\NumberValidator;

/**
 * This is the model class for table "vale_des".
 *
 * @property string $VD_SERSOL
 * @property integer $VD_NUMVALE
 * @property string $VD_FECHA
 * @property string $VD_HORA
 * @property string $VD_SUPERV
 * @property string $VD_DEPOSITO
 * @property integer $VD_PROCESADO
 *
 * @property Deposito $deposito
 * @property Legajos $vMSUPERV
 * @property Servicio $vMSERSOL
 * @property PedidoInsumosRenglones[] $renglonesPedido
 */
class PedidoInsumos extends \yii\db\ActiveRecord
{
    //public $renglones;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vale_des';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VD_FECHA', 'VD_HORA', 'renglones'], 'safe'],
            [['VD_PROCESADO'], 'integer'],
            [['VD_SERSOL'], 'string', 'max' => 3],
            [['VD_SUPERV'], 'string', 'max' => 6],
            [['VD_DEPOSITO'], 'string', 'max' => 2],
            [['renglones'],'validarRenglonesPedido'],
            [['VD_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['VD_DEPOSITO' => 'DE_CODIGO']],
            [['VD_SUPERV'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['VD_SUPERV' => 'LE_NUMLEGA']],
            [['VD_SERSOL'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['VD_SERSOL' => 'SE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'VD_SERSOL' => 'Servicio',
            'VD_NUMVALE' => 'Número',
            'VD_FECHA' => 'Fecha',
            'VD_HORA' => 'Hora',
            'VD_SUPERV' => 'Supervisor',
            'VD_DEPOSITO' => 'Depósito',
            'VD_PROCESADO' => 'Procesado',
        ];
    }


    public function validarRenglonesPedido($attribute, $params) {
        foreach($this->$attribute as $index => $renglon) {
            $articulo=ArticGral::find()
                ->andWhere(['AG_CODIGO' => $renglon["VD_CODMON"]])
                ->andWhere(['AG_DEPOSITO' => $renglon["VD_DEPOSITO"]])
                ->one();

            if(NULL!=$articulo) {
                $numberValidator = new NumberValidator([
                    'min' => 1,
                    'max' => max([0,$articulo->AG_STACT])
                ]);

                $error = null;
                $numberValidator->validate($renglon['VD_CANTID'], $error);

            }

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][VD_CANTID]';
                $this->addError($key, $error);
            }
        }
    }
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'VD_DEPOSITO']);
    }
    public function getRenglones()
    {
        return $this->hasMany(PedidoInsumosRenglones::className(), ['VD_NUMVALE' => 'VD_NUMVALE']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanillas_entrega()
    {
        return $this->hasMany(Planilla_entrega::className(), ['PE_NUMVALE' => 'VD_NUMVALE']);
    }


    public function getServicio()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'VD_SERSOL']);
    }
    public function getSupervisor()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'VD_SUPERV']);
    }
}
