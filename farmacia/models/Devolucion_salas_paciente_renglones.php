<?php

namespace farmacia\models;

use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "dev_val".
 *
 * @property integer $DV_NRODEVOL
 * @property integer $DV_HISCLI
 * @property string $DV_DEPOSITO
 * @property string $DV_CODMON
 * @property string $DV_CANTID
 * @property string $DV_FECVTO
 * @property integer $DV_NUMRENG
 *
 * @property ArticGral $dVCODMON
 * @property Deposito $dVDEPOSITO
 * @property Devoluc2 $dVNRODEVOL
 */
class Devolucion_salas_paciente_renglones extends \yii\db\ActiveRecord
{
    public $descripcion;
     public $codigo,$monodroga,$cantidad,$valor;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dev_val';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DV_NRODEVOL', 'DV_HISCLI', 'DV_NUMRENG'], 'required'],
            [['DV_NRODEVOL', 'DV_HISCLI', 'DV_NUMRENG'], 'integer'],
            [['DV_CANTID'], 'number'],
            [['DV_FECVTO'], 'safe'],
            [['DV_DEPOSITO'], 'string', 'max' => 2],
            [['DV_CODMON'], 'string', 'max' => 4],
            [['DV_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['DV_CODMON' => 'AG_CODIGO']],
            [['DV_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['DV_DEPOSITO' => 'DE_CODIGO']],
            [['DV_NRODEVOL'], 'exist', 'skipOnError' => true, 'targetClass' => Devolucion_salas_paciente::className(), 'targetAttribute' => ['DV_NRODEVOL' => 'DE_NRODEVOL']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DV_NRODEVOL' => 'Número Devolución',
            'DV_HISCLI' => 'Historia Clínica',
            'DV_DEPOSITO' => 'Subdepósito de farmacia',
            'DV_CODMON' => 'Código del medicamento devuelto',
            'DV_CANTID' => 'Cantidad devuelta',
            'DV_FECVTO' => 'Fecha de vencimiento del medicamento',
            'DV_NUMRENG' => 'Número de renglón del vale de devolucion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonod()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'DV_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDVDEPOSITO()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'DV_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevolucion_encabezado()
    {
        return $this->hasOne(Devolucion_salas_paciente::className(), ['DE_NRODEVOL' => 'DV_NRODEVOL']);
    }

    public function getPaciente()
    {
        return $this->hasOne(Paciente::className(), ['PA_HISCLI' => 'DE_HISCLI'])
                     ->via('devolucion_encabezado');
    }

     //Obtiene todos los renglones de una Devolucion a proveedor
    public function get_renglones($id=0)
    {
        $query = Devolucion_salas_paciente_renglones::find();

        // add conditions that should always apply here
        $query->andFilterWhere([
            'DV_NRODEVOL' => $id,
        ]);

        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
        ]);

        return $dataProvider;   
    }
}
