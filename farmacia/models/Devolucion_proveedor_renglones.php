<?php

namespace farmacia\models;

use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "dev_prov".
 *
 * @property integer $DP_NROREM
 * @property integer $DP_NUMRENG
 * @property string $DP_DEPOSITO
 * @property string $DP_CODMON
 * @property string $DP_CANTID
 * @property string $DP_FECVTO
 *
 * @property ArticGral $dPCODMON
 * @property Deposito $dPDEPOSITO
 */
class Devolucion_proveedor_renglones extends \yii\db\ActiveRecord
{
    public $descripcion;
    public $vencimientos;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dev_prov';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DP_NROREM', 'DP_NUMRENG'], 'required'],
            [['DP_NROREM', 'DP_NUMRENG'], 'integer'],
            [['DP_CANTID'], 'number'],
            [['DP_FECVTO'], 'safe'],
            [['DP_DEPOSITO'], 'string', 'max' => 2],
            [['DP_CODMON'], 'string', 'max' => 4],
            [['DP_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['DP_CODMON' => 'AG_CODIGO']],
            [['DP_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['DP_DEPOSITO' => 'DE_CODIGO']],
        ];
    }

   
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DP_NROREM' => 'Número de remito ',
            'DP_NUMRENG' => 'Número de Renglón',
            'DP_DEPOSITO' => 'Código del subdepósito de farmacia',
            'DP_CODMON' => 'Cod. Medicamento',
            'DP_CANTID' => 'Cantidad devuelta',
            'DP_FECVTO' => 'Fecha vencimiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDPCODMON()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'DP_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDPDEPOSITO()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'DP_DEPOSITO']);
    }

    //Obtiene todos los renglones de una Devolucion a proveedor
    public function get_renglones($id=0)
    {
        $query = Devolucion_proveedor_renglones::find();

        // add conditions that should always apply here
        $query->andFilterWhere([
            'DP_NROREM' => $id,
        ]);

        $query->orderBy([
           'DP_NUMRENG'=>SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
        ]);

        return $dataProvider;   
    }
}
