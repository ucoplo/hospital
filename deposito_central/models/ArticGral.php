<?php

namespace deposito_central\models;

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
 * @property AdqReng[] $adqRengs
 * @property RemitoAdq[] $aRRENUMs
 * @property Alarmas $alarmas
 * @property Deposito[] $aLDEPOSITOs
 * @property AmbuRen[] $ambuRens
 * @property Acciont $aGACCION
 * @property Clases $aGCODCLA
 * @property Deposito $aGDEPOSITO
 * @property Droga $aGDROGA
 * @property Servicio $aGPROVINT
 * @property Vias $aGVIA
 * @property DcMovDia[] $dcMovDias
 * @property DcPerdReng[] $dcPerdRengs
 * @property DcPerdidas[] $dRNROREMs
 * @property DcTabVtos[] $dcTabVtos
 * @property DevProv[] $devProvs
 * @property DevVal[] $devVals
 * @property Devofar[] $devofars
 * @property DevprovReng[] $devprovRengs
 * @property Medic[] $medics
 * @property MovDia[] $movDias
 * @property MovQuiro[] $movQuiros
 * @property MovSala[] $movSalas
 * @property PdReng[] $pdRengs
 * @property PeReng[] $peRengs
 * @property PeadMov[] $peadMovs
 * @property PeenMov[] $peenMovs
 * @property Perdfar[] $perdfars
 * @property Planfar[] $planfars
 * @property ProgMed[] $progMeds
 * @property RecetasReng[] $recetasRengs
 * @property RemMov[] $remMovs
 * @property RengOc[] $rengOcs
 * @property RsReng[] $rsRengs
 * @property TabVtos[] $tabVtos
 * @property Topeart[] $topearts
 * @property Servicio[] $tACODSERVs
 * @property Topemedi[] $topemedis
 * @property VadeRen[] $vadeRens
 * @property VaenRen[] $vaenRens
 * @property Valefar[] $valefars
 * @property VamoRen[] $vamoRens
 */
class ArticGral extends \yii\db\ActiveRecord
{
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
            [['AG_VIA'], 'exist', 'skipOnError' => true, 'targetClass' => Via::className(), 'targetAttribute' => ['AG_VIA' => 'VI_CODIGO']],
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
    public function getAdqRengs()
    {
        return $this->hasMany(AdqReng::className(), ['AR_CODART' => 'AG_CODIGO', 'AR_DEPOSITO' => 'AG_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getARRENUMs()
    {
        return $this->hasMany(RemitoAdq::className(), ['RA_NUM' => 'AR_RENUM'])->viaTable('adq_reng', ['AR_CODART' => 'AG_CODIGO', 'AR_DEPOSITO' => 'AG_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlarmas()
    {
        return $this->hasOne(Alarmas::className(), ['AL_CODMON' => 'AG_CODIGO', 'AL_DEPOSITO' => 'AG_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getALDEPOSITOs()
    {
        return $this->hasMany(Deposito::className(), ['DE_CODIGO' => 'AL_DEPOSITO'])->viaTable('alarmas', ['AL_CODMON' => 'AG_CODIGO', 'AL_DEPOSITO' => 'AG_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmbuRens()
    {
        return $this->hasMany(AmbuRen::className(), ['AM_CODMON' => 'AG_CODIGO']);
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
    public function getAGPROVINT()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'AG_PROVINT']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAGVIA()
    {
        return $this->hasOne(Via::className(), ['VI_CODIGO' => 'AG_VIA']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcMovDias()
    {
        return $this->hasMany(DcMovDia::className(), ['DM_CODART' => 'AG_CODIGO', 'DM_DEPOSITO' => 'AG_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcPerdRengs()
    {
        return $this->hasMany(DcPerdReng::className(), ['DR_CODART' => 'AG_CODIGO', 'DR_DEPOSITO' => 'AG_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDRNROREMs()
    {
        return $this->hasMany(DcPerdidas::className(), ['DP_NROREM' => 'DR_NROREM'])->viaTable('dc_perd_reng', ['DR_CODART' => 'AG_CODIGO', 'DR_DEPOSITO' => 'AG_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcTabVtos()
    {
        return $this->hasMany(DcTabVtos::className(), ['DT_CODART' => 'AG_CODIGO', 'DT_DEPOSITO' => 'AG_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevProvs()
    {
        return $this->hasMany(DevProv::className(), ['DP_CODMON' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevVals()
    {
        return $this->hasMany(DevVal::className(), ['DV_CODMON' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevofars()
    {
        return $this->hasMany(Devofar::className(), ['DF_CODMON' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevprovRengs()
    {
        return $this->hasMany(DevprovReng::className(), ['DP_CODART' => 'AG_CODIGO', 'DP_DEPOSITO' => 'AG_DEPOSITO']);
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
    public function getMovDias()
    {
        return $this->hasMany(MovDia::className(), ['MD_CODMON' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovQuiros()
    {
        return $this->hasMany(MovQuiro::className(), ['MO_CODART' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovSalas()
    {
        return $this->hasMany(MovSala::className(), ['MO_CODMON' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenglones_devolucion_salas()
    {
        return $this->hasMany(PdReng::className(), ['PR_CODART' => 'AG_CODIGO', 'PR_DEPOSITO' => 'AG_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenglones_planilla()
    {
        return $this->hasMany(Planilla_entrega_renglones::className(), ['PR_CODART' => 'AG_CODIGO', 'PR_DEPOSITO' => 'AG_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenglones_pedidos()
    {
        return $this->hasMany(Pedido_adquisicion_renglones::className(), ['PE_CODART' => 'AG_CODIGO', 'PE_DEPOSITO' => 'AG_DEPOSITO']);
    }

    public function getPlanilla_entrega()
    {
        return $this->hasOne(Planilla_entrega::className(), ['PE_NROREM' => 'PR_NROREM'])
                        ->via('renglones_planilla');
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
    public function getPerdfars()
    {
        return $this->hasMany(Perdfar::className(), ['PF_CODMON' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanfars()
    {
        return $this->hasMany(Planfar::className(), ['PF_CODMON' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgMeds()
    {
        return $this->hasMany(ProgMed::className(), ['PM_CODMON' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecetasRengs()
    {
        return $this->hasMany(RecetasReng::className(), ['RE_CODMON' => 'AG_CODIGO']);
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
    public function getRengOcs()
    {
        return $this->hasMany(RengOc::className(), ['EN_CODART' => 'AG_CODIGO', 'EN_DEPOSITO' => 'AG_DEPOSITO']);
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
    public function getTabVtos()
    {
        return $this->hasMany(TabVtos::className(), ['TV_CODART' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopearts()
    {
        return $this->hasMany(Topeart::className(), ['TA_CODART' => 'AG_CODIGO', 'TA_DEPOSITO' => 'AG_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTACODSERVs()
    {
        return $this->hasMany(Servicio::className(), ['SE_CODIGO' => 'TA_CODSERV'])->viaTable('topeart', ['TA_CODART' => 'AG_CODIGO', 'TA_DEPOSITO' => 'AG_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopemedis()
    {
        return $this->hasMany(Topemedi::className(), ['TM_CODMON' => 'AG_CODIGO', 'TM_DEPOSITO' => 'AG_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVadeRens()
    {
        return $this->hasMany(VadeRen::className(), ['VD_CODMON' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVaenRens()
    {
        return $this->hasMany(VaenRen::className(), ['VE_CODMON' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValefars()
    {
        return $this->hasMany(Valefar::className(), ['VA_CODMON' => 'AG_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVamoRens()
    {
        return $this->hasMany(VamoRen::className(), ['VM_CODMON' => 'AG_CODIGO', 'VM_DEPOSITO' => 'AG_DEPOSITO']);
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

    public function pendiente_entrega()
    {
       $query = ArticGral::find();
       $query->joinWith(['renglones_pedidos']);
       $query->andFilterWhere([
            'AG_CODIGO' => $this->AG_CODIGO,
            'AG_DEPOSITO' => $this->AG_DEPOSITO,
        ]);

        $query->andFilterWhere(['>', 'PE_CANT', 0]);

        $pendiente = $query->sum('PE_CANT');
        $pendiente = (isset($pendiente)) ? $pendiente : 0 ;
        return $pendiente;
    }

    public function consumo_promedio_diario_historico(){
        
        $fecha_inicio = date('Y-m-d', strtotime('-1 year'));
        $fecha_fin = date('Y-m-d');

        $query = ArticGral::find();
        $query->joinWith(['planilla_entrega']);

        $query->andFilterWhere([
            'AG_CODIGO' => $this->AG_CODIGO,
            'AG_DEPOSITO' => $this->AG_DEPOSITO,
        ]);

        $query->andFilterWhere(['>=', 'PE_FECHA', $fecha_inicio]);
        $query->andFilterWhere(['<=', 'PE_FECHA', $fecha_fin]);

        $consumo_historico = $query->sum('PR_CANTID');

        return  $consumo_historico/365;
    }

    public function consumo_promedio_diario_puntual($dias){
        $fecha_inicio = date('Y-m-d', strtotime("-$dias day"));
        $fecha_fin = date('Y-m-d');

        $query = ArticGral::find();
        $query->joinWith(['planilla_entrega']);

        $query->andFilterWhere([
            'AG_CODIGO' => $this->AG_CODIGO,
            'AG_DEPOSITO' => $this->AG_DEPOSITO,
        ]);

        $query->andFilterWhere(['>=', 'PE_FECHA', $fecha_inicio]);
        $query->andFilterWhere(['<=', 'PE_FECHA', $fecha_fin]);

        $consumo_puntual = $query->sum('PR_CANTID');

        return  $consumo_puntual/$dias;
    }
}
