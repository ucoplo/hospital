<?php

namespace deposito_central\models;

use Yii;


define('TRABAJA', 'T');
/**
 * This is the model class for table "paciente".
 *
 */
class Paciente extends \yii\db\ActiveRecord
{
    public $edad;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paciente';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PA_TIPDOC', 'PA_NUMDOC', 'PA_APENOM', 'PA_FECNAC', 'PA_SEXO', 'PA_NACION', 'PA_CODPRO', 'PA_CODPAR', 'PA_CODLOC', 'PA_DIREC', 'PA_CODCALL', 'PA_NROCALL', 'PA_BARRIO', 'PA_TIPOVIV', 'PA_TELEF', 'PA_APEMA', 'PA_TELFA', 'PA_NOMBRE', 'PA_APELLIDO'], 'required'],
            [['PA_VENNIV'], 'safe'],
            [['PA_FECNAC'], 'validarFechaNacimiento'],
            [['PA_OBSERV', 'PA_FALLEC', 'PA_REGISTRADO'], 'string'],
            [['PA_CODPAR', 'PA_TIPDOC', 'PA_CODLOC', 'PA_ENTDE', 'PA_LOCNAC', 'PA_USANIT', 'PA_CODPAIS', 'PA_USANITSU', 'PA_ORIGEN', 'PA_PARTIDONAC'], 'string', 'max' => 3],
            [['PA_NOMBRE', 'PA_APELLIDO', 'PA_APEMA', 'PA_APEFA', 'PA_NOMELEG'], 'string', 'max' => 35],
            [['PA_APENOM'], 'string', 'max' => 100],
            [['PA_EMPDIR'], 'string', 'max' => 50],
            [['PA_NROCALL', 'PA_MEDDER'], 'string', 'max' => 6],
            [['PA_NUMDOC'], 'string', 'max' => 12],
            [['PA_NACION', 'PA_CODPRO', 'PA_NIVINST', 'PA_PROVNAC'], 'string', 'max' => 2],
            [['PA_SEXO', 'PA_TIPOVIV', 'PA_NIVEL'], 'string', 'max' => 1],
            [['PA_CODCALL', 'PA_PISO'], 'string', 'max' => 5],
            [['PA_BARRIO'], 'integer'],
            [['PA_CUERPO'], 'string', 'max' => 20],
            [['PA_DPTO'], 'string', 'max' => 10],
            [['PA_TELEF', 'PA_CUITEMP'], 'string', 'max' => 13],
            [['PA_CODOS', 'PA_ART'], 'string', 'max' => 4],
            [['PA_NROAFI'], 'string', 'max' => 15],
            [['PA_ADEU'], 'string', 'max' => 7],
            [['PA_UBIC'], 'string', 'max' => 8],
            [['PA_APEMEDD'], 'string', 'max' => 30],
            [['PA_OCUPAC'], 'string', 'max' => 50],
            [['PA_TELFA'], 'string', 'max' => 14],
            [['PA_EMPEMPL'], 'string', 'max' => 40],
            [['PA_EMAIL'], 'string', 'max' => 75],
            [['PA_DIREC'], 'string', 'max' => 100], 

            [['PA_NROAFI', 'PA_ASOCIAD'], 'required', 
                'when' => function($model) {return $model->PA_CODOS != '';},
                'whenClient' => "function (attribute, value) {return $('#paciente-pa_codos').val() != '';}"
                ], // Obligatorio si seleccionó Obra Social

            [['PA_EMPEMPL', 'PA_EMPDIR', 'PA_CUITEMP', 'PA_ART'], 'required', 
                'when' => function($model) {return $model->PA_SITLABO == TRABAJA;},
                'whenClient' => "function (attribute, value) {return $('#paciente-pa_sitlabo').val() == '" . TRABAJA . "';}"
                ], // Obligatorio si trabaja en relación de dependencia
        ];
    }

    public function validarFechaNacimiento($attribute, $params) {
        $date = new \DateTime();
        $hoy = $date->format('Y-m-d');
        if ($this->$attribute > $hoy) {
            $this->addError($attribute, 'La fecha de nacimiento no puede ser posterior a hoy');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PA_CODPAR' => 'Partido',
            'PA_APENOM' => 'Apellido y Nombres',
            'PA_NOMBRE' => 'Nombres',
            'PA_APELLIDO' => 'Apellidos',
            'PA_HISCLI' => 'Historia Clínica',
            'PA_TIPDOC' => 'Tipo de Doc.',
            'PA_NUMDOC' => 'Nro de Doc.',
            'PA_FECNAC' => 'Fecha de nacimiento',
            'PA_NACION' => 'Nacionalidad',
            'PA_SEXO' => 'Sexo',
            'PA_DIREC' => 'Dirección',
            'PA_CODCALL' => 'Calle',
            'PA_NROCALL' => 'Nro.',
            'PA_BARRIO' => 'Barrio',
            'PA_CUERPO' => 'Cuerpo',
            'PA_PISO' => 'Piso',
            'PA_TIPOVIV' => 'Tipo de Vivienda',
            'PA_DPTO' => 'Dpto',
            'PA_CODLOC' => 'Localidad',
            'PA_CODPRO' => 'Provincia',
            'PA_TELEF' => 'Teléfono',
            'PA_OBSERV' => 'Comentarios',
            'PA_NIVEL' => 'Nivel S-E',
            'PA_VENNIV' => 'Vto. Categ.',
            'PA_CODOS' => 'Obra Social',
            'PA_NROAFI' => 'Nro Afiliado',
            'PA_ADEU' => 'Adeu',
            'PA_ENTDE' => 'Entde',
            'PA_LOCNAC' => 'Ciudad de nacimiento',
            'PA_PARTIDONAC' => 'Partido de nacimiento',
            'PA_PROVNAC' => 'Provincia de nacimiento',
            'PA_APEMA' => 'Apellido y nombres de la madre',
            'PA_UBIC' => 'Ubicación',
            'PA_USANIT' => 'Usanit',
            'PA_MEDDER' => 'Medder',
            'PA_APEMEDD' => 'Apemedd',
            'PA_CODPAIS' => 'País',
            'PA_ASOCIAD' => 'Asociado',
            'PA_NIVINST' => 'Nivel de instrucción',
            'PA_SITLABO' => 'Situación Laboral',
            'PA_OCUPAC' => 'Ocupación',
            'PA_APEFA' => 'Apellido y nombres del familiar',
            'PA_TELFA' => 'Nº de Teléfono del familiar',
            'PA_FALLEC' => 'Fallec',
            'PA_NOMELEG' => 'Nombre elegido',
            'PA_EMPEMPL' => 'Nombre Empresa',
            'PA_EMPDIR' => 'Domicilio Empresa',
            'PA_CUITEMP' => 'CUIT Empresa',
            'PA_EMAIL' => 'E-mail',
            'PA_REGISTRADO' => 'Registrado',
            'PA_USANITSU' => 'Usanitsu',
            'PA_ART' => 'ART',
            'PA_ORIGEN' => 'Creada desde',
            'nacionalidadDescripcion' => 'Nacionalidad',
            'paisDescripcion' => 'País',
            'provinciaDescripcion' => 'Provincia',
            'partidoDescripcion' => 'Partido',
            'localidadDescripcion' => 'Localidad',
            'obraSocialDescripcion' => 'Obra Social',
            'localidadNacimientoDescripcion' => 'Ciudad de nacimiento',
            'partidoNacimientoDescripcion' => 'Partido de nacimiento',
            'provinciaNacimientoDescripcion' => 'Provincia de nacimiento',
            'calleDescripcion' => 'Calle',
            'barrioDescripcion' => 'Barrio',
            'tipoViviendaDescripcion' => 'Tipo de Vivienda',
            'nivelInstruccionDescripcion' => 'Nivel de instrucción',
            'situacionLaboralDescripcion' => 'Situación Laboral',
            'ocupacionDescripcion' => 'Ocupación',
            'artDescripcion' => 'ART',
            'tipoDocumentoDescripcion' => 'Tipo de Doc.'
        ];
    }

    public static function primaryKey()
    {
        return ['PA_HISCLI'];
    }

    public function getTipoDocumento()
    {
        return $this->hasOne(TipoDocumento::className(), ['TI_COD' => 'PA_TIPDOC']); 
    }

    public function getNacionalidad()
    {
        return $this->hasOne(Nacionalidad::className(), ['NA_COD' => 'PA_NACION']); 
    }

    public function getPais()
    {
        return $this->hasOne(Pais::className(), ['PA_COD' => 'PA_CODPAIS']); 
    }

    public function getProvincia()
    {
        return $this->hasOne(Provincia::className(), ['PR_COD' => 'PA_CODPRO']); 
    }

    public function getPartido()
    {
        return $this->hasOne(Partido::className(), ['PT_COD' => 'PA_CODPAR']); 
    }

    public function getLocalidad()
    {
        return $this->hasOne(Localidad::className(), ['LO_COD' => 'PA_CODLOC']);
    }

    public function getCalle()
    {
        return $this->hasOne(Calle::className(), ['CA_CODIGO' => 'PA_CODCALL']);
    }

    public function getBarrio()
    {
        return $this->hasOne(Barrio::className(), ['BA_CODIGO' => 'PA_BARRIO']);
    }

    public function getTipoVivienda()
    {
        return $this->hasOne(TipoVivienda::className(), ['TV_CODIGO' => 'PA_TIPOVIV']);
    }

    public function getObraSocial()
    {
        return $this->hasOne(ObraSocial::className(), ['OB_COD' => 'PA_CODOS']);
    }

    public function getLocalidadNacimiento()
    {
        return $this->hasOne(Localidad::className(), ['LO_COD' => 'PA_LOCNAC']); 
    }

    public function getPartidoNacimiento()
    {
        return $this->hasOne(Partido::className(), ['PT_COD' => 'PA_PARTIDONAC']); 
    }

    public function getProvinciaNacimiento()
    {
        return $this->hasOne(Provincia::className(), ['PR_COD' => 'PA_PROVNAC']); 
    }

    public function getNivelInstruccion()
    {
        return $this->hasOne(NivelInstruccion::className(), ['NI_CODIGO' => 'PA_NIVINST']);
    }

    public function getSituacionLaboral()
    {
        return $this->hasOne(SituacionLaboral::className(), ['cod' => 'PA_SITLABO']);
    }

    public function getOcupacion()
    {
        return $this->hasOne(Ocupacion::className(), ['OC_COD' => 'PA_OCUPAC']);
    }

    public function getArt()
    {
        return $this->hasOne(ObraSocial::className(), ['OB_COD' => 'PA_ART']);
    }

    public function getEtiquetasPoblacionales()
    {
        return $this->hasMany(EtiquetaPoblacionalPaciente::className(), ['et_hiscli' => 'PA_HISCLI']);
    }

    public function getConsultasSmu()
    {
        return $this->hasMany(ColaSmu::className(), ['CO_HISCLI' => 'PA_HISCLI']);
    }

    /*======== Métodos para obtener la Descripción de los objetos de la relación ========*/

    public function getTipoDocumentoDescripcion()
    {
        if ($this->tipoDocumento)
            return '[' . $this->tipoDocumento->TI_COD . '] ' . $this->tipoDocumento->TI_NOM;
        else
            return '';
    }

    public function getNacionalidadDescripcion()
    {
        if ($this->nacionalidad)
            return '[' . $this->nacionalidad->NA_COD . '] ' . $this->nacionalidad->NA_DETALLE;
        else
            return '';
    }

    public function getPaisDescripcion()
    {
        if ($this->pais)
            return '[' . $this->pais->PA_COD . '] ' . $this->pais->PA_DETALLE;
        else
            return '';
    }

    public function getProvinciaDescripcion()
    {
        if ($this->provincia)
            return '[' . $this->provincia->PR_COD . '] ' . $this->provincia->PR_DETALLE;
        else
            return '';
    }

    public function getPartidoDescripcion()
    {
        if ($this->partido)
            return '[' . $this->partido->PT_COD . '] ' . $this->partido->PT_DETALLE;
        else
            return '';
    }

    public function getLocalidadDescripcion()
    {
        if ($this->localidad)
            return '[' . $this->localidad->LO_COD . '] ' . $this->localidad->LO_DETALLE;
        else
            return '';
    }

    public function getCalleDescripcion()
    {
        if ($this->calle)
            return '[' . $this->calle->CA_CODIGO . '] ' . $this->calle->CA_NOM;
        else
            return '';
    }

    public function getBarrioDescripcion()
    {
        if ($this->barrio)
            return '[' . $this->barrio->BA_CODIGO . '] ' . $this->barrio->BA_NOMBRE;
        else
            return '';
    }

    public function getTipoViviendaDescripcion()
    {
        if ($this->tipoVivienda)
            return '[' . $this->tipoVivienda->TV_CODIGO . '] ' . $this->tipoVivienda->TV_DETALLE;
        else
            return '';
    }

    public function getObraSocialDescripcion()
    {
        if ($this->obraSocial)
            return '[' . $this->obraSocial->OB_SINON . ' (' . $this->obraSocial->OB_NOM . ')';
        else
            return '';
    }

    public function getLocalidadNacimientoDescripcion()
    {
        if ($this->localidadNacimiento)
            return '[' . $this->localidadNacimiento->LO_COD . '] ' . $this->localidadNacimiento->LO_DETALLE;
        else
            return '';
    }

    public function getProvinciaNacimientoDescripcion()
    {
        if ($this->provinciaNacimiento)
            return '[' . $this->provincia->PR_COD . '] ' . $this->provincia->PR_DETALLE;
        else
            return '';
    }

    public function getPartidoNacimientoDescripcion()
    {
        if ($this->partidoNacimiento)
            return '[' . $this->partido->PT_COD . '] ' . $this->partido->PT_DETALLE;
        else
            return '';
    }

    public function getNivelInstruccionDescripcion()
    {
        if ($this->nivelInstruccion)
            return '[' . $this->nivelInstruccion->NI_CODIGO . '] ' . $this->nivelInstruccion->NI_DETALLE;
        else
            return ''; 
    }

    public function getSituacionLaboralDescripcion()
    {
        if ($this->situacionLaboral)
            return '[' . $this->situacionLaboral->cod . '] ' . $this->situacionLaboral->descri;
        else
            return '';
    }

    public function getOcupacionDescripcion()
    {
        if ($this->ocupacion)
            return '[' . $this->ocupacion->OC_COD . '] ' . $this->ocupacion->OC_DESCRI;
        else
            return '';
    }

    public function getArtDescripcion()
    {
        if ($this->art)
            return '[' . $this->art->OB_COD . '] ' . $this->art->OB_NOM;
        else
            return '';
    }

    public function getObraSocialMensaje()
    {
        if ($this->obraSocial)
            return $this->obraSocial->OB_INSTSMU;
        else
            return '';
    }

     public function getVencimientoNivel()
    {
        $fecha=$this->PA_VENNIV; 

        if (isset($fecha) && !empty($fecha) && $fecha!='0000-00-00'){

            
            return Yii::$app->formatter->asDate($fecha,'php:d-m-Y');
            
        }
        else{
               return ''; 
        }
    } 
    
      public function getSexo()
    {
        if ($this->PA_SEXO='M'){
            return "Masculino";
        }else{
            return "Femenino";
        }
    }
}
