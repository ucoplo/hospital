<?php

namespace farmacia\models;

use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "valefar".
 *
 * @property integer $VA_NROVALE
 * @property string $VA_NUMRENG
 * @property string $VA_DEPOSITO
 * @property string $VA_CODMON
 * @property string $VA_CANTID
 * @property string $VA_FECVTO
 *
 * @property ArticGral $vACODMON
 * @property Consmed $vANROVALE
 * @property Deposito $vADEPOSITO
 */
class Consumo_medicamentos_pacientes_renglones extends \yii\db\ActiveRecord
{
    public $descripcion;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'valefar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VA_NROVALE', 'VA_NUMRENG'], 'required'],
            [['VA_NROVALE', 'VA_NUMRENG'], 'integer'],
            [['VA_CANTID'], 'number'],
            [['VA_FECVTO'], 'safe'],
            [['VA_CODMON'], 'string', 'max' => 4],
            [['VA_DEPOSITO'], 'string', 'max' => 2],
            [['VA_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['VA_CODMON' => 'AG_CODIGO']],
            [['VA_NROVALE'], 'exist', 'skipOnError' => true, 'targetClass' => Consumo_medicamentos_pacientes::className(), 'targetAttribute' => ['VA_NROVALE' => 'CM_NROVAL']],
            [['VA_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['VA_DEPOSITO' => 'DE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'VA_NROVALE' => 'Número Vale Farmacia',
            'VA_NUMRENG' => 'Renglon',
            'VA_DEPOSITO' => 'Depósito',
            'VA_CODMON' => 'Medicamento',
            'VA_CANTID' => 'Cantidad',
            'VA_FECVTO' => 'Fecha Vencimiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonodroga()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'VA_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVale()
    {
        return $this->hasOne(Consumo_medicamentos_pacientes::className(), ['CM_NROVAL' => 'VA_NROVALE']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'VA_DEPOSITO']);
    }

       //Obtiene todos los renglones de un Vale de Farmacia
    public function get_renglones($id=0)
    {
        $query = Consumo_medicamentos_pacientes_renglones::find();

        // add conditions that should always apply here
        $query->andFilterWhere([
            'VA_NROVALE' => $id,
        ]);

        $query->orderBy([
           'VA_NUMRENG'=>SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
        ]);

        return $dataProvider;   
    }

    
}
