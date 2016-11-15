<?php

namespace deposito_central\models;

use Yii;

/**
 * This is the model class for table "servicio".
 *
 * @property string $SE_CODIGO
 * @property string $SE_DESCRI
 * @property string $SE_TPOSER
 * @property string $SE_CCOSTO
 * @property string $SE_SALA
 * @property string $SE_AREA
 * @property string $SE_INFO
 *
 * @property ArticGral[] $articGrals
 * @property Topemedi[] $topemedis
 */
class Servicio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'servicio';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SE_CODIGO'], 'unique'],
            [['SE_CODIGO', 'SE_TPOSER', 'SE_INFO'], 'required'],
            [['SE_TPOSER', 'SE_INFO'], 'string'],
            [['SE_CODIGO', 'SE_CCOSTO'], 'string', 'max' => 3],
            [['SE_DESCRI'], 'string', 'max' => 30],
            [['SE_SALA'], 'string', 'max' => 2],
            [['SE_AREA'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SE_CODIGO' => 'Código',
            'SE_DESCRI' => 'Descripción',
            'SE_TPOSER' => 'Tipo',
            'SE_CCOSTO' => 'Centro de costo',
            'SE_SALA' => 'Número de sala',
            'SE_AREA' => 'Nombre del área asistencial',
            'SE_INFO' => 'Genera informe',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticGrals()
    {
        return $this->hasMany(ArticGral::className(), ['AG_PROVINT' => 'SE_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopemedis()
    {
        return $this->hasMany(Topemedi::className(), ['TM_CODSERV' => 'SE_CODIGO']);
    }

    public function tipo_descripcion()
    {   //Serv. Intermedio, Unid de Diagnóstico, Serv Internación, Serv. Administrativo, Serv. Externo

        switch ($this->SE_TPOSER) {
            case 'S':
                return "Serv. Intermedio";
                break;
            case 'U':
                return "Unid de Diagnóstico";
                break;
            case 'I':
                return "Serv Internación";
                break;
            case 'A':
                return "Serv. Administrativo";
                break;
            case 'E':
                return "Serv. Externo";
                break;
        }
      
    }

    public function lista_tipos()
    {

        return [ 'S' => 'Serv. Intermedio', 'U' => 'Unid de Diagnóstico', 'I' => 'Serv Internación', 'A' => 'Serv. Administrativo', 'E' => 'Serv. Externo', ];
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

   
}
