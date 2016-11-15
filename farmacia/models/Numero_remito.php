<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "vali_rem".
 *
 * @property integer $VR_NROREM
 * @property string $VR_SERSOL
 * @property string $VR_CONDPAC
 * @property string $VR_FECDES
 * @property string $VR_FECHAS
 * @property string $VR_HORDES
 * @property string $VR_HORHAS
 *
 * @property Servicio $vRSERSOL
 */

class Numero_remito extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vali_rem';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VR_NROREM', 'VR_SERSOL','VR_FECDES', 'VR_FECHAS', 'VR_HORDES', 'VR_HORHAS'], 'required'],
            [['VR_NROREM'], 'integer'],
            [['VR_CONDPAC'], 'string'],
            [['VR_FECDES', 'VR_FECHAS', 'VR_HORDES', 'VR_HORHAS'], 'safe'],
            ['VR_FECDES','compare', 'compareAttribute'=>'VR_FECHAS', 'operator'=>'<=','message'=>'Debe ser anterior a Hasta'],
            ['VR_FECHAS','compare', 'compareAttribute'=>'VR_FECDES', 'operator'=>'>=','message'=>'Debe ser posterior a Desde'],
            ['VR_HORDES','compare', 'compareAttribute'=>'VR_HORHAS', 'operator'=>'<','message'=>'Debe ser anterior a Hasta','when' => function ($model) {
                return ($model->VR_FECDES==$model->VR_FECHAS);
            }, 'whenClient' => "function(attribute, value) {
                return ($('#numero_remito-vr_fecdes-disp').val()==$('#numero_remito-vr_fechas-disp').val());
            }"],
            ['VR_HORHAS','compare', 'compareAttribute'=>'VR_HORDES', 'operator'=>'>','message'=>'Debe ser posterior a Desde','when' => function ($model) {
                return ($model->VR_FECDES==$model->VR_FECHAS);
            }, 'whenClient' => "function(attribute, value) {
                return ($('#numero_remito-vr_fecdes-disp').val()==$('#numero_remito-vr_fechas-disp').val());
            }"],
            [['VR_SERSOL'], 'string', 'max' => 3],
            [['VR_SERSOL'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['VR_SERSOL' => 'SE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'VR_NROREM' => 'Número Remito',
            'VR_SERSOL' => 'Servicio Solicitante',
            'VR_CONDPAC' => 'Ambulatorio o Internado',
            'VR_FECDES' => 'Fecha Desde',
            'VR_FECHAS' => 'Fecha Hasta',
            'VR_HORDES' => 'Hora Desde',
            'VR_HORHAS' => 'Hora Hasta',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicio()
    {
        if (isset($this->VR_SERSOL))
            return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'VR_SERSOL']);
        else
            return '';
    }

    public function getProcesado()
    {
        if (isset($this->VR_NROREM) && !empty($this->VR_NROREM)){ 
            $searchModel = new Consumo_medicamentos_pacientesSearch();
            $vales_farmacia = Consumo_medicamentos_pacientes::find()->where(['CM_PROCESADO' => 1,'CM_NROREM'=>$this->VR_NROREM, 'CM_CONDPAC' => $this->VR_CONDPAC])->all();
            return (!empty($vales_farmacia));
        }
        else
            return false;
    }
}
