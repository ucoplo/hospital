<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "interna".
 *
 * @property string $IN_ID
 * @property integer $IN_HISCLI
 * @property string $IN_FECING
 * @property string $IN_HORING
 * @property string $IN_MEDRES
 * @property string $IN_CODOS
 * @property string $IN_TITU
 * @property string $IN_COSEG
 * @property string $IN_NUMCOS
 * @property string $IN_NUMCAR
 * @property string $IN_SERING
 * @property string $IN_SERINT
 * @property string $IN_SALA
 * @property string $IN_NUMHAB
 * @property string $IN_UNDIAG
 * @property string $IN_MOTING
 * @property string $IN_DIAG1
 * @property string $IN_DIAG2
 * @property string $IN_FECEGR
 * @property string $IN_HOREGR
 * @property string $IN_TIPEGR
 * @property integer $IN_COMPLEJIDAD
 * @property string $IN_DIAGDIF
 * @property string $IN_APFA
 * @property string $IN_TELFA
 * @property string $IN_MEDALT
 * @property integer $IN_NUMCAM
 * @property string $IN_CLIQUI
 * @property string $IN_MONTO
 * @property string $IN_UDORIG
 * @property string $IN_REVIS
 * @property string $IN_AREAIN
 * @property string $IN_MOTNFC
 * @property string $IN_TDOCMA
 * @property string $IN_NDOCMA
 * @property string $IN_ASOCIAD
 * @property string $IN_OTRCIRC
 * @property string $IN_COMPRO
 * @property string $IN_FECALTA
 * @property string $IN_FOTDOCU
 * @property string $IN_FOTCARN
 * @property string $IN_FOTULRE
 * @property integer $IN_PATRES
 * @property string $IN_ANEXFAM
 * @property string $IN_ANEXMED
 * @property integer $IN_ORDENIN
 * @property string $IN_EMPEMPLE
 * @property string $IN_EMPDIR
 * @property string $IN_CUITEMP
 * @property string $IN_OBSING
 * @property string $IN_OBSEG
 * @property string $IN_NIVEL
 * @property string $IN_VENNIV
 * @property string $IN_MOTNET
 * @property string $IN_OBSNENT
 * @property string $IN_INFOSOC
 * @property string $IN_HORALTA
 *
 * @property Consmed[] $consmeds
 * @property Devoluc2[] $devoluc2s
 * @property ValeEnf[] $valeEnfs
 */
class Interna extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'interna';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IN_HISCLI', 'IN_FECING', 'IN_HORING'], 'required'],
            [['IN_HISCLI', 'IN_COMPLEJIDAD', 'IN_NUMCAM', 'IN_PATRES', 'IN_ORDENIN'], 'integer'],
            [['IN_FECING', 'IN_HORING', 'IN_FECEGR', 'IN_HOREGR', 'IN_FECALTA', 'IN_VENNIV', 'IN_HORALTA'], 'safe'],
            [['IN_REVIS', 'IN_MOTNFC', 'IN_FOTDOCU', 'IN_FOTCARN', 'IN_FOTULRE', 'IN_OBSING', 'IN_OBSEG', 'IN_OBSNENT', 'IN_INFOSOC'], 'string'],
            [['IN_MEDRES', 'IN_MEDALT'], 'string', 'max' => 6],
            [['IN_CODOS', 'IN_COSEG'], 'string', 'max' => 4],
            [['IN_TITU', 'IN_CLIQUI', 'IN_ASOCIAD', 'IN_ANEXFAM', 'IN_ANEXMED', 'IN_NIVEL', 'IN_MOTNET'], 'string', 'max' => 1],
            [['IN_NUMCOS', 'IN_NUMCAR'], 'string', 'max' => 15],
            [['IN_SERING', 'IN_SERINT', 'IN_NUMHAB', 'IN_UNDIAG', 'IN_UDORIG', 'IN_AREAIN', 'IN_TDOCMA'], 'string', 'max' => 3],
            [['IN_SALA', 'IN_TIPEGR'], 'string', 'max' => 2],
            [['IN_MOTING', 'IN_DIAG1', 'IN_DIAG2', 'IN_MONTO', 'IN_COMPRO'], 'string', 'max' => 10],
            [['IN_DIAGDIF', 'IN_OTRCIRC'], 'string', 'max' => 55],
            [['IN_APFA', 'IN_EMPDIR'], 'string', 'max' => 35],
            [['IN_TELFA'], 'string', 'max' => 14],
            [['IN_NDOCMA'], 'string', 'max' => 12],
            [['IN_EMPEMPLE'], 'string', 'max' => 40],
            [['IN_CUITEMP'], 'string', 'max' => 13],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IN_ID' => 'Id de la tabla',
            'IN_HISCLI' => 'Historia Clinica del Paciente',
            'IN_FECING' => 'Fecha de Ingreso',
            'IN_HORING' => 'Hora de Ingreso',
            'IN_MEDRES' => 'Medico responsable de la internacion',
            'IN_CODOS' => 'Codigo de la obra social, tabla obrasoci',
            'IN_TITU' => 'Indica si es titular o no',
            'IN_COSEG' => 'Codigo del Coseguro',
            'IN_NUMCOS' => 'Numero de afiliado al coseguro',
            'IN_NUMCAR' => 'Numero de carnet',
            'IN_SERING' => 'Servicio de ingreso, tabla Servicio',
            'IN_SERINT' => 'Servicio de internacion, tabla Servicio',
            'IN_SALA' => 'Numero de la sala actual',
            'IN_NUMHAB' => 'Numero de habitacion actual',
            'IN_UNDIAG' => 'Unidad de diagnostico actual',
            'IN_MOTING' => 'Motivo de ingreso',
            'IN_DIAG1' => 'Diagnostico de egreso, tabla Egresos',
            'IN_DIAG2' => 'Diagnostico de egreso, tabla Egresos',
            'IN_FECEGR' => 'Fecha de egreso',
            'IN_HOREGR' => 'Hora de egreso',
            'IN_TIPEGR' => 'Condicion de egreso',
            'IN_COMPLEJIDAD' => 'Complejidad del paciente, tabla Tipo_hab',
            'IN_DIAGDIF' => 'Diagnostico diferenciales, separado por barras /',
            'IN_APFA' => 'Apellido familiar',
            'IN_TELFA' => 'Telefono familiar',
            'IN_MEDALT' => 'Medico responsable del alta',
            'IN_NUMCAM' => 'Numero de cama, puede saberse la complejidad de la misma',
            'IN_CLIQUI' => 'Indica si es Internacion Clinica o Quirurgica',
            'IN_MONTO' => 'Monto facturado en toda la internacion',
            'IN_UDORIG' => 'Unidad de Diag Origen, tabla ',
            'IN_REVIS' => 'Revision si son decima o novena',
            'IN_AREAIN' => 'Es el sector de ingreso',
            'IN_MOTNFC' => 'Motivo por el cual no se facturo',
            'IN_TDOCMA' => 'Tipo de documento de la madre',
            'IN_NDOCMA' => 'Nro documento de la madre',
            'IN_ASOCIAD' => 'Asociado tabla Asociado',
            'IN_OTRCIRC' => 'Otra circunst de internacion prolongada',
            'IN_COMPRO' => 'Como se produjo, tabla Diagno',
            'IN_FECALTA' => 'Fecha de alta medica',
            'IN_FOTDOCU' => 'Indica si entrego fotoc del dni o no corresponde',
            'IN_FOTCARN' => 'Indica si entrego fotoc del carnet o no corresponde',
            'IN_FOTULRE' => 'Indica si entrego fotoc del recibo de sueldo o no corresponde',
            'IN_PATRES' => 'In  Patres',
            'IN_ANEXFAM' => 'Indica si el anexo esta firmado por el familiar',
            'IN_ANEXMED' => 'Indica si el anexo esta firmado por el medico',
            'IN_ORDENIN' => 'Indica si trajo la orden de internacion',
            'IN_EMPEMPLE' => 'Nombre de la empresa empleadora',
            'IN_EMPDIR' => 'Domicilio de la empresa empleadora',
            'IN_CUITEMP' => 'CUIT de la empresa empleadora',
            'IN_OBSING' => 'In  Obsing',
            'IN_OBSEG' => 'In  Obseg',
            'IN_NIVEL' => 'In  Nivel',
            'IN_VENNIV' => 'In  Venniv',
            'IN_MOTNET' => 'In  Motnet',
            'IN_OBSNENT' => 'In  Obsnent',
            'IN_INFOSOC' => 'In  Infosoc',
            'IN_HORALTA' => 'In  Horalta',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConsumosPaciente()
    {
        return $this->hasMany(Consumo_medicamentos_pacientes::className(), ['CM_IDINTERNA' => 'IN_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevoluciones_Paciente()
    {
        return $this->hasMany(Devolucion_salas_paciente::className(), ['DE_IDINTERNA' => 'IN_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVales_Paciente()
    {
        return $this->hasMany(ValeEnfermeria::className(), ['VE_IDINTERNA' => 'IN_ID']);
    }

    public function getValefars()
    {
        return $this
           ->hasMany(Consumo_medicamentos_pacientes_renglones::className(), ['VA_NROVALE' => 'CM_NROVAL'])
           ->via('consumosPaciente');
    }

    public function getMonodrogas()
    {
        return $this
            ->hasOne(ArticGral::className(), ['AG_CODIGO' => 'VA_CODMON'])
            ->via('valefars');
    }

    

}
