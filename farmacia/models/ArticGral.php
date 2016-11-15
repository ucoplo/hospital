<?php

namespace farmacia\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "artic_gral".
 *
 * @property string $AG_CODIGO
 * @property string $AG_NOMBRE
 * @property string $AG_CODMED
 * @property string $AG_PRES
 * @property string $AG_CODRAF
 * @property string $AG_STACT
 * @property string $AG_STACDEP
 * @property string $AG_CODCLA
 * @property string $AG_FRACCQ
 * @property string $AG_PSICOF
 * @property string $AG_PTOMIN
 * @property string $AG_FPTOMIN
 * @property string $AG_PTOPED
 * @property string $AG_FPTOPED
 * @property string $AG_PTOMAX
 * @property string $AG_FPTOMAX
 * @property string $AG_CONSDIA
 * @property string $AG_FCONSDI
 * @property string $AG_RENGLON
 * @property string $AG_PRECIO
 * @property string $AG_REDOND
 * @property string $AG_PUNTUAL
 * @property string $AG_FPUNTUAL
 * @property string $AG_REPAUT
 * @property string $AG_ULTENT
 * @property string $AG_ULTSAL
 * @property string $AG_UENTDEP
 * @property string $AG_USALDEP
 * @property string $AG_PROVINT
 * @property string $AG_ACTIVO
 * @property string $AG_VADEM
 * @property string $AG_ORIGUSUA
 * @property string $AG_FRACSAL
 * @property string $AG_DROGA
 * @property string $AG_VIA
 * @property string $AG_DOSIS
 * @property string $AG_ACCION
 * @property string $AG_VISIBLE
 * @property string $AG_DEPOSITO
 * @property string $AG_UNIENV
 * @property string $AG_PRESENV
 *
 * @property Acciont $aGACCION
 * @property Clases $clase
 * @property Deposito $deposito
 * @property Droga $aGDROGA
 * @property Medic $aGCODMED
 * @property Servicio $aGPROVINT
 * @property Vias $aGVIA
 * @property Medic[] $medics
  * @property PeenMov[] $peenMovs
 * @property RemMov[] $remMovs
 * @property RsReng[] $rsRengs
 * @property Topemedi[] $topemedis
 */
class ArticGral extends \yii\db\ActiveRecord
{
    public $clasifica,$dias_salida;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'artic_gral';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AG_CODIGO'], 'unique'],
            [['AG_CODIGO', 'AG_NOMBRE', 'AG_PRES', 'AG_STACT', 'AG_STACDEP', 'AG_CODCLA', 'AG_FRACCQ', 'AG_PSICOF', 'AG_PTOMIN', 'AG_FPTOMIN', 'AG_PTOPED', 'AG_FPTOPED', 'AG_PTOMAX', 'AG_FPTOMAX', 'AG_CONSDIA', 'AG_FCONSDI', 'AG_RENGLON', 'AG_PRECIO', 'AG_REDOND', 'AG_PUNTUAL', 'AG_FPUNTUAL', 'AG_REPAUT', 'AG_ULTENT', 'AG_ULTSAL', 'AG_UENTDEP', 'AG_USALDEP', 'AG_PROVINT', 'AG_ACTIVO', 'AG_VADEM', 'AG_ORIGUSUA', 'AG_FRACSAL', 'AG_DROGA', 'AG_VIA', 'AG_DOSIS', 'AG_ACCION', 'AG_VISIBLE', 'AG_DEPOSITO'], 'required'],
            [['AG_PRES', 'AG_RENGLON', 'AG_REPAUT', 'AG_ACTIVO', 'AG_VISIBLE'], 'string'],
            [['AG_STACT', 'AG_STACDEP', 'AG_PTOMIN', 'AG_FPTOMIN', 'AG_PTOPED', 'AG_FPTOPED', 'AG_PTOMAX', 'AG_FPTOMAX', 'AG_CONSDIA', 'AG_FCONSDI', 'AG_PRECIO', 'AG_REDOND', 'AG_PUNTUAL', 'AG_FPUNTUAL', 'AG_DOSIS', 'AG_UNIENV'], 'number'],
            [['AG_ULTENT', 'AG_ULTSAL', 'AG_UENTDEP', 'AG_USALDEP'], 'safe'],
            [['AG_CODIGO', 'AG_CODMED', 'AG_DROGA'], 'string', 'max' => 4],
            [['AG_NOMBRE'], 'string', 'max' => 40],
            [['AG_CODRAF'], 'string', 'max' => 16],
            [['AG_CODCLA', 'AG_VIA', 'AG_DEPOSITO'], 'string', 'max' => 2],
            [['AG_FRACCQ', 'AG_PSICOF', 'AG_VADEM', 'AG_FRACSAL'], 'string', 'max' => 1],
            [['AG_PROVINT', 'AG_ACCION'], 'string', 'max' => 3],
            [['AG_ORIGUSUA'], 'string', 'max' => 6],
            [['AG_PRESENV'], 'string', 'max' => 50],
            [['AG_ACCION'], 'exist', 'skipOnError' => true, 'targetClass' => Accionterapeutica::className(), 'targetAttribute' => ['AG_ACCION' => 'AC_COD']],
            [['AG_CODCLA'], 'exist', 'skipOnError' => true, 'targetClass' => Clases::className(), 'targetAttribute' => ['AG_CODCLA' => 'CL_COD']],
            [['AG_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['AG_DEPOSITO' => 'DE_CODIGO']],
            [['AG_DROGA'], 'exist', 'skipOnError' => true, 'targetClass' => Droga::className(), 'targetAttribute' => ['AG_DROGA' => 'DR_CODIGO']],
            [['AG_CODMED'], 'exist', 'skipOnError' => true, 'targetClass' => Medic::className(), 'targetAttribute' => ['AG_CODMED' => 'ME_CODIGO']],
            [['AG_PROVINT'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['AG_PROVINT' => 'SE_CODIGO']],
            [['AG_VIA'], 'exist', 'skipOnError' => true, 'targetClass' => Via::className(), 'targetAttribute' => ['AG_VIA' => 'vi_codigo']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AG_CODIGO' => 'Código',
            'AG_NOMBRE' => 'Nombre',
            'AG_CODMED' => 'Medicamento',
            'AG_PRES' => 'Presentación',
            'AG_CODRAF' => 'Código de Rafam ',
            'AG_STACT' => 'Stock Farmacia',
            'AG_STACDEP' => 'Stock Depósito',
            'AG_CODCLA' => 'Clase',
            'AG_FRACCQ' => 'Fraccionado',
            'AG_PSICOF' => 'Psicofármaco',
            'AG_PTOMIN' => 'Stock Mínimo Depósito',
            'AG_FPTOMIN' => 'Stock Mínimo Farmacia',
            'AG_PTOPED' => 'Stock Medio Depósito',
            'AG_FPTOPED' => 'Stock Medio Farmacia',
            'AG_PTOMAX' => 'Stock Máximo Depósito',
            'AG_FPTOMAX' => 'Stock Máximo Farmacia',
            'AG_CONSDIA' => 'Consumo Promedio Depósito',
            'AG_FCONSDI' => 'Consumo Promedio Farmacia',
            'AG_RENGLON' => 'Solicitud Compras',
            'AG_PRECIO' => 'Precio última compra',
            'AG_REDOND' => 'Cantidad Mínima a pedir',
            'AG_PUNTUAL' => 'Consumo Medio Depósito',
            'AG_FPUNTUAL' => 'Consumo Medio Farmacia',
            'AG_REPAUT' => 'Reposición Automática',
            'AG_ULTENT' => 'Última entrada',
            'AG_ULTSAL' => 'Última Salida',
            'AG_UENTDEP' => 'Última entrada Depósito',
            'AG_USALDEP' => 'Última salida Depósito',
            'AG_PROVINT' => 'Proveedor Interno',
            'AG_ACTIVO' => 'Activo',
            'AG_VADEM' => 'Vademecum',
            'AG_ORIGUSUA' => 'Usuario',
            'AG_FRACSAL' => 'Fracciona en Sala',
            'AG_DROGA' => 'Droga',
            'AG_VIA' => 'Vía de acceso',
            'AG_DOSIS' => 'Dosis',
            'AG_ACCION' => 'Acción terapéutica',
            'AG_VISIBLE' => 'Visible desde Descartes',
            'AG_DEPOSITO' => 'Depósito',
            'AG_UNIENV' => 'Unidades por envase',
            'AG_PRESENV' => 'Presentación del envase',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAGACCION()
    {
        return $this->hasOne(Accionterapeutica::className(), ['AC_COD' => 'AG_ACCION']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase()
    {
        return $this->hasOne(Clases::className(), ['CL_COD' => 'AG_CODCLA']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAGDEPOSITO()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'AG_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAGDROGA()
    {
        return $this->hasOne(Droga::className(), ['DR_CODIGO' => 'AG_DROGA']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAGCODMED()
    {
        return $this->hasOne(Medic::className(), ['ME_CODIGO' => 'AG_CODMED']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAGPROVINT()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'AG_PROVINT']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAGVIA()
    {
        return $this->hasOne(Via::className(), ['vi_codigo' => 'AG_VIA']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedics()
    {
        return $this->hasMany(Medic::className(), ['ME_CODMON' => 'AG_CODIGO']);
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeenMovs()
    {
        return $this->hasMany(PeenMov::className(), ['PE_CODMON' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemMovs()
    {
        return $this->hasMany(RemMov::className(), ['RM_CODMON' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRsRengs()
    {
        return $this->hasMany(RsReng::className(), ['RS_CODMON' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopemedis()
    {
        return $this->hasMany(Topemedi::className(), ['TM_CODMON' => 'AG_CODIGO']);
    }
    
    public static function getListaClases()
    {
        $opciones = Clases::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'CL_COD', 'CL_NOM');
    }

     public static function getListaDrogas()
    {
        $opciones = Droga::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'DR_CODIGO', 'DR_DESCRI');
    }

     public static function getListaVias()
    {
        $opciones = Via::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'VI_CODIGO', 'VI_DESCRI');
    }

    public static function getListaDepositos()
    {
        $opciones = Deposito::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

    public static function getListaAccionTerapeutica()
    {
        $opciones = Accionterapeutica::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'AC_COD', 'AC_DESCRI');
    }

    public static function getListaServicios()
    {
        $opciones = Servicio::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'SE_CODIGO', 'SE_DESCRI');
    }

    public static function getListaMedicamentos()
    {
        $opciones = Medic::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'ME_CODIGO', 'ME_NOMCOM');
    }

    public function boolean_descripcion($valor)
    {

          if ($valor=='T' || $valor=='S')
                return 'Si';
            elseif ($valor=='F' || $valor=='N')
                return 'No';
            else
                return 'Indefinido';
    }

    public function descripcion_medic()
    {
        if (isset($this->aGCODMED))
             return $this->aGCODMED->ME_NOMCOM;
        else   
            return '';

    }
}
