<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "obrasoci".
 *
 * @property string $OB_COD
 * @property string $OB_NOM
 * @property string $OB_NOMCOMP
 * @property string $OB_SINON
 * @property string $OB_DIRECC
 * @property string $OB_CUIT
 * @property string $OB_DGI
 * @property string $OB_SUBCOD
 * @property string $OB_COSEG
 * @property string $OB_ENTE
 * @property string $OB_CONVEN
 * @property string $OB_CJTONOM
 * @property string $OB_RUBNFG
 * @property string $OB_RUBNFH
 * @property string $OB_CTACTE
 * @property integer $OB_DIAPRES
 * @property integer $OB_FECPRES
 * @property double $OB_PORGAS
 * @property double $OB_PORHON
 * @property double $OB_USGOPER
 * @property double $OB_USOTGAS
 * @property double $OB_USGRADI
 * @property double $OB_USGCLIN
 * @property double $OB_USPENSI
 * @property double $OB_USGALEN
 * @property double $OB_UGASBIO
 * @property string $OB_REQUISI
 * @property string $OB_FACTDIR
 * @property string $OB_RUBHONO
 * @property string $OB_FECHA
 * @property double $OB_USGALQU
 * @property double $OB_USGAPA
 * @property string $OB_ARTSEG
 * @property string $OB_CAPITA
 * @property string $OB_INSTSMU
 * @property string $OB_INSTCEX
 * @property string $OB_INSTDXI
 * @property string $OB_INSTLAB
 * @property string $OB_INSTINT
 * @property string $OB_ACTIVA
 * @property string $OB_TEL
 */
class ObraSocial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'obrasoci';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['OB_DIAPRES', 'OB_FECPRES'], 'integer'],
            [['OB_PORGAS', 'OB_PORHON', 'OB_USGOPER', 'OB_USOTGAS', 'OB_USGRADI', 'OB_USGCLIN', 'OB_USPENSI', 'OB_USGALEN', 'OB_UGASBIO', 'OB_USGALQU', 'OB_USGAPA'], 'number'],
            [['OB_FECHA'], 'safe'],
            [['OB_ARTSEG', 'OB_CAPITA', 'OB_INSTSMU', 'OB_INSTCEX', 'OB_INSTDXI', 'OB_INSTLAB', 'OB_INSTINT', 'OB_ACTIVA'], 'string'],
            [['OB_COD'], 'string', 'max' => 4],
            [['OB_NOM', 'OB_RUBHONO'], 'string', 'max' => 50],
            [['OB_NOMCOMP'], 'string', 'max' => 100],
            [['OB_SINON'], 'string', 'max' => 30],
            [['OB_DIRECC', 'OB_CJTONOM', 'OB_RUBNFG', 'OB_RUBNFH'], 'string', 'max' => 40],
            [['OB_CUIT'], 'string', 'max' => 15],
            [['OB_DGI', 'OB_ENTE'], 'string', 'max' => 3],
            [['OB_SUBCOD', 'OB_CTACTE'], 'string', 'max' => 6],
            [['OB_COSEG', 'OB_CONVEN', 'OB_FACTDIR'], 'string', 'max' => 1],
            [['OB_REQUISI'], 'string', 'max' => 2],
            [['OB_TEL'], 'string', 'max' => 70],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'OB_COD' => 'Código',
            'OB_NOM' => 'Nombre',
            'OB_NOMCOMP' => 'Nombre Completo',
            'OB_SINON' => 'Sinónimo',
            'OB_DIRECC' => 'Dirección',
            'OB_CUIT' => 'CUIT',
            'OB_DGI' => 'AFIP',
            'OB_SUBCOD' => 'Sub Código',
            'OB_COSEG' => 'Ob  Coseg',
            'OB_ENTE' => 'Ob  Ente',
            'OB_CONVEN' => 'Ob  Conven',
            'OB_CJTONOM' => 'Ob  Cjtonom',
            'OB_RUBNFG' => 'Ob  Rubnfg',
            'OB_RUBNFH' => 'Ob  Rubnfh',
            'OB_CTACTE' => 'Ob  Ctacte',
            'OB_DIAPRES' => 'Ob  Diapres',
            'OB_FECPRES' => 'Ob  Fecpres',
            'OB_PORGAS' => 'Ob  Porgas',
            'OB_PORHON' => 'Ob  Porhon',
            'OB_USGOPER' => 'Ob  Usgoper',
            'OB_USOTGAS' => 'Ob  Usotgas',
            'OB_USGRADI' => 'Ob  Usgradi',
            'OB_USGCLIN' => 'Ob  Usgclin',
            'OB_USPENSI' => 'Ob  Uspensi',
            'OB_USGALEN' => 'Ob  Usgalen',
            'OB_UGASBIO' => 'Ob  Ugasbio',
            'OB_REQUISI' => 'Ob  Requisi',
            'OB_FACTDIR' => 'Ob  Factdir',
            'OB_RUBHONO' => 'Ob  Rubhono',
            'OB_FECHA' => 'Ob  Fecha',
            'OB_USGALQU' => 'Ob  Usgalqu',
            'OB_USGAPA' => 'Ob  Usgapa',
            'OB_ARTSEG' => 'Ob  Artseg',
            'OB_CAPITA' => 'Ob  Capita',
            'OB_INSTSMU' => 'Instructivo deGuardia',
            'OB_INSTCEX' => 'Instructivo de Cosnultorio Externo',
            'OB_INSTDXI' => 'Instructivo de Diag. por Imágenes',
            'OB_INSTLAB' => 'Instructivo de Laboratorio',
            'OB_INSTINT' => 'Instructivo de Internación',
            'OB_ACTIVA' => '¿Activa?',
            'OB_TEL' => 'Teléfono',
        ];
    }
}
