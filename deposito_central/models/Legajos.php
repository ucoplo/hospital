<?php

namespace deposito_central\models;

use Yii;

/**
 * This is the model class for table "legajos".
 *
 * @property string $LE_NUMLEGA
 * @property string $LE_APENOM
 * @property string $LE_NOMAPE
 * @property string $LE_FECNAC
 * @property string $LE_SEXO
 * @property string $LE_LOCNAC
 * @property string $LE_LOCDESC
 * @property string $LE_PROVNAC
 * @property string $LE_NACION
 * @property string $LE_TIPDOC
 * @property string $LE_NUMDOC
 * @property string $LE_CUIL
 * @property string $LE_CTACRED
 * @property string $LE_TIPOCTA
 * @property string $LE_CTAPATA
 * @property string $LE_ESTUD
 * @property string $LE_PROFES
 * @property string $LE_PROFES_A
 * @property string $LE_MATRIC
 * @property string $LE_TITUL1
 * @property string $LE_MATRI1
 * @property string $LE_TITUL2
 * @property string $LE_MATRI2
 * @property string $LE_TITUL3
 * @property string $LE_MATRI3
 * @property string $LE_TITUL4
 * @property string $LE_MATRI4
 * @property string $LE_ESTCIV
 * @property string $LE_APECONY
 * @property string $LE_DIREC
 * @property string $LE_TELEF
 * @property string $LE_CELULAR
 * @property string $LE_ACTIVO
 * @property string $LE_BAJREEM
 * @property string $LE_OTROAGE
 * @property string $LE_AGEMUNI
 * @property string $password
 * @property string $salt
 * @property string $permisos
 * @property string $grupo
 * @property string $auth_key
 *
 * @property AmbuEnc[] $ambuEncs
 */
class Legajos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'legajos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['LE_NUMLEGA', 'auth_key'], 'required'],
            [['LE_FECNAC'], 'safe'],
            [['LE_ACTIVO', 'LE_BAJREEM', 'LE_OTROAGE'], 'string'],
            [['LE_NUMLEGA', 'LE_MATRIC', 'LE_MATRI1', 'LE_MATRI2', 'LE_MATRI3', 'LE_MATRI4'], 'string', 'max' => 6],
            [['LE_APENOM', 'LE_NOMAPE'], 'string', 'max' => 50],
            [['LE_SEXO', 'LE_TIPOCTA', 'LE_ESTCIV', 'LE_AGEMUNI'], 'string', 'max' => 1],
            [['LE_LOCNAC', 'LE_TIPDOC'], 'string', 'max' => 3],
            [['LE_LOCDESC', 'LE_APECONY'], 'string', 'max' => 30],
            [['LE_PROVNAC', 'LE_NACION', 'LE_ESTUD', 'LE_PROFES', 'LE_TITUL1', 'LE_TITUL2', 'LE_TITUL3', 'LE_TITUL4'], 'string', 'max' => 2],
            [['LE_NUMDOC'], 'string', 'max' => 12],
            [['LE_CUIL'], 'string', 'max' => 13],
            [['LE_CTACRED', 'LE_CTAPATA'], 'string', 'max' => 7],
            [['LE_PROFES_A'], 'string', 'max' => 4],
            [['LE_DIREC'], 'string', 'max' => 35],
            [['LE_TELEF', 'LE_CELULAR', 'grupo'], 'string', 'max' => 25],
            [['password', 'salt'], 'string', 'max' => 128],
            [['permisos'], 'string', 'max' => 8],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'LE_NUMLEGA' => 'Le  Numlega',
            'LE_APENOM' => 'Le  Apenom',
            'LE_NOMAPE' => 'Le  Nomape',
            'LE_FECNAC' => 'Le  Fecnac',
            'LE_SEXO' => 'Le  Sexo',
            'LE_LOCNAC' => 'Le  Locnac',
            'LE_LOCDESC' => 'Le  Locdesc',
            'LE_PROVNAC' => 'Le  Provnac',
            'LE_NACION' => 'Le  Nacion',
            'LE_TIPDOC' => 'Le  Tipdoc',
            'LE_NUMDOC' => 'Le  Numdoc',
            'LE_CUIL' => 'Le  Cuil',
            'LE_CTACRED' => 'Le  Ctacred',
            'LE_TIPOCTA' => 'Le  Tipocta',
            'LE_CTAPATA' => 'Le  Ctapata',
            'LE_ESTUD' => 'Le  Estud',
            'LE_PROFES' => 'Le  Profes',
            'LE_PROFES_A' => 'Le  Profes  A',
            'LE_MATRIC' => 'Le  Matric',
            'LE_TITUL1' => 'Le  Titul1',
            'LE_MATRI1' => 'Le  Matri1',
            'LE_TITUL2' => 'Le  Titul2',
            'LE_MATRI2' => 'Le  Matri2',
            'LE_TITUL3' => 'Le  Titul3',
            'LE_MATRI3' => 'Le  Matri3',
            'LE_TITUL4' => 'Le  Titul4',
            'LE_MATRI4' => 'Le  Matri4',
            'LE_ESTCIV' => 'Le  Estciv',
            'LE_APECONY' => 'Le  Apecony',
            'LE_DIREC' => 'Le  Direc',
            'LE_TELEF' => 'Le  Telef',
            'LE_CELULAR' => 'Le  Celular',
            'LE_ACTIVO' => 'Le  Activo',
            'LE_BAJREEM' => 'Le  Bajreem',
            'LE_OTROAGE' => 'Le  Otroage',
            'LE_AGEMUNI' => 'Le  Agemuni',
            'password' => 'Password',
            'salt' => 'Salt',
            'permisos' => 'Permisos',
            'grupo' => 'Grupo',
            'auth_key' => 'Auth Key',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmbuEncs()
    {
        return $this->hasMany(AmbuEnc::className(), ['AM_MEDICO' => 'LE_NUMLEGA']);
    }
}
