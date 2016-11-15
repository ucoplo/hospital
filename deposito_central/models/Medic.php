<?php

namespace deposito_central\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "medic".
 *
 * @property string $ME_CODIGO
 * @property string $ME_NOMCOM
 * @property string $ME_CODKAI
 * @property string $ME_CODRAF
 * @property string $ME_KAIBAR
 * @property string $ME_KAITRO
 * @property string $ME_CODMON
 * @property string $ME_CODLAB
 * @property string $ME_PRES
 * @property string $ME_FRACCQ
 * @property double $ME_VALVEN
 * @property string $ME_ULTCOM
 * @property string $ME_VALCOM
 * @property string $ME_ULTSAL
 * @property double $ME_STMIN
 * @property double $ME_STMAX
 * @property string $ME_RUBRO
 * @property string $ME_UNIENV
 * @property string $ME_DEPOSITO
 *
 * @property ArticGral[] $articGrals
 * @property ArticGral $monodroga
 * @property Deposito $mEDEPOSITO
 * @property Labo $mECODLAB
 */
class Medic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ME_CODIGO', 'ME_NOMCOM', 'ME_CODKAI', 'ME_CODRAF', 'ME_KAIBAR', 'ME_KAITRO', 'ME_CODMON', 'ME_CODLAB', 'ME_PRES', 'ME_FRACCQ', 'ME_VALVEN', 'ME_ULTCOM', 'ME_VALCOM', 'ME_ULTSAL', 'ME_STMIN', 'ME_STMAX', 'ME_RUBRO', 'ME_UNIENV', 'ME_DEPOSITO'], 'required'],
            [['ME_CODIGO'], 'unique'],
            [['ME_PRES'], 'string'],
            [['ME_VALVEN', 'ME_VALCOM', 'ME_STMIN', 'ME_STMAX', 'ME_UNIENV'], 'number'],
            [['ME_ULTCOM', 'ME_ULTSAL'], 'safe'],
            [['ME_CODIGO', 'ME_CODMON', 'ME_CODLAB'], 'string', 'max' => 4],
            [['ME_NOMCOM'], 'string', 'max' => 40],
            [['ME_CODKAI', 'ME_KAITRO'], 'string', 'max' => 8],
            [['ME_CODRAF'], 'string', 'max' => 9],
            [['ME_KAIBAR'], 'string', 'max' => 13],
            [['ME_FRACCQ'], 'string', 'max' => 1],
            [['ME_RUBRO', 'ME_DEPOSITO'], 'string', 'max' => 2],
            [['ME_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['ME_CODMON' => 'AG_CODIGO']],
            [['ME_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['ME_DEPOSITO' => 'DE_CODIGO']],
            [['ME_CODLAB'], 'exist', 'skipOnError' => true, 'targetClass' => Labo::className(), 'targetAttribute' => ['ME_CODLAB' => 'LA_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ME_CODIGO' => 'Código del medicamento',
            'ME_NOMCOM' => 'Nombre comercial del medicamento',
            'ME_CODKAI' => 'Código según Kairos',
            'ME_CODRAF' => 'Código según Rafam',
            'ME_KAIBAR' => 'Código de barras según Kairos',
            'ME_KAITRO' => 'Código de troquel según Kairos',
            'ME_CODMON' => 'Código de la monodroga',
            'ME_CODLAB' => 'Código del proveedor',
            'ME_PRES' => 'Texto que indica la presentación',
            'ME_FRACCQ' => 'Indica si se fracciona al enviar a Quirófano',
            'ME_VALVEN' => 'Valor de venta',
            'ME_ULTCOM' => 'Fecha de última compra',
            'ME_VALCOM' => 'Valor de la última compra',
            'ME_ULTSAL' => 'Fecha de última salida',
            'ME_STMIN' => 'Stock mínimo',
            'ME_STMAX' => 'Stock máximo',
            'ME_RUBRO' => 'Rubro de facturación',
            'ME_UNIENV' => 'Unidades por envase',
            'ME_DEPOSITO' => 'Depósito',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticGrals()
    {
        return $this->hasMany(ArticGral::className(), ['AG_CODMED' => 'ME_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonodroga()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'ME_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'ME_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLaboratorio()
    {
        return $this->hasOne(Labo::className(), ['LA_CODIGO' => 'ME_CODLAB']);
    }

    public static function getListaDepositos()
    {
        $opciones = Deposito::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

    public static function getListaMonodrogas()
    {
        $opciones = ArticGral::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'AG_CODIGO', 'AG_NOMBRE');
    }

   
}
