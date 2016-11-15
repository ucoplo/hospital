<?php

namespace deposito_central\models;

use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "devofar".
 *
 * @property integer $PR_NRODEVOL
 * @property string $PR_DEPOSITO
 * @property string $PR_CODART
 * @property string $PR_CANTID
 * @property string $PR_FECVTO
 *
 * @property ArticGral $monodroga
 * @property Deposito $dFDEPOSITO
 * @property Devoluc $dFNRODEVOL
 */
class Devolucion_salas_renglones extends \yii\db\ActiveRecord
{
    public $descripcion;
    public $codigo,$monodroga,$cantidad,$valor;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pd_reng';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PR_NRODEVOL', 'PR_DEPOSITO', 'PR_CODART', 'PR_FECVTO'], 'required'],
            [['PR_NRODEVOL'], 'integer'],
            [['PR_CANTID'], 'number'],
            [['PR_FECVTO'], 'safe'],
            [['PR_DEPOSITO'], 'string', 'max' => 2],
            [['PR_CODART'], 'string', 'max' => 4],
            [['PR_CODART'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['PR_CODART' => 'AG_CODIGO','PR_DEPOSITO' => 'AG_DEPOSITO']],
            [['PR_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['PR_DEPOSITO' => 'DE_CODIGO']],
            [['PR_NRODEVOL'], 'exist', 'skipOnError' => true, 'targetClass' => Devolucion_salas::className(), 'targetAttribute' => ['PR_NRODEVOL' => 'DE_NRODEVOL']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PR_NRODEVOL' => 'Número Devolución',
            'PR_DEPOSITO' => 'Depósito',
            'PR_CODART' => 'Cod. Medicamento',
            'PR_CANTID' => 'Cantidad devuelta',
            'PR_FECVTO' => 'Fecha vencimiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonod()
    {
        
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'PR_CODART','AG_DEPOSITO' => 'PR_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'PR_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevolucion_encabezado()
    {
        return $this->hasOne(Devolucion_salas::className(), ['DE_NRODEVOL' => 'PR_NRODEVOL']);
    }

    //Obtiene todos los renglones de una Devolucion a proveedor
    public function get_renglones($id=0)
    {
        $query = Devolucion_salas_renglones::find();

        // add conditions that should always apply here
        $query->andFilterWhere([
            'PR_NRODEVOL' => $id,
        ]);

        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
        ]);

        return $dataProvider;   
    }
}
