<?php

namespace farmacia\models;

use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "devofar".
 *
 * @property integer $DF_NRODEVOL
 * @property string $DF_DEPOSITO
 * @property string $DF_CODMON
 * @property string $DF_CANTID
 * @property string $DF_FECVTO
 *
 * @property ArticGral $monodroga
 * @property Deposito $dFDEPOSITO
 * @property Devoluc $dFNRODEVOL
 */
class Devolucion_salas_granel_renglones extends \yii\db\ActiveRecord
{
    public $descripcion;
    public $codigo,$monodroga,$cantidad,$valor;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'devofar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DF_NRODEVOL', 'DF_DEPOSITO', 'DF_CODMON', 'DF_FECVTO'], 'required'],
            [['DF_NRODEVOL'], 'integer'],
            [['DF_CANTID'], 'number'],
            [['DF_FECVTO'], 'safe'],
            [['DF_DEPOSITO'], 'string', 'max' => 2],
            [['DF_CODMON'], 'string', 'max' => 4],
            [['DF_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['DF_CODMON' => 'AG_CODIGO']],
            [['DF_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['DF_DEPOSITO' => 'DE_CODIGO']],
            [['DF_NRODEVOL'], 'exist', 'skipOnError' => true, 'targetClass' => Devolucion_salas_granel::className(), 'targetAttribute' => ['DF_NRODEVOL' => 'DE_NRODEVOL']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DF_NRODEVOL' => 'Número Devolución',
            'DF_DEPOSITO' => 'Depósito',
            'DF_CODMON' => 'Cod. Medicamento',
            'DF_CANTID' => 'Cantidad devuelta',
            'DF_FECVTO' => 'Fecha vencimiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonod()
    {
        
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'DF_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'DF_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevolucion_encabezado()
    {
        return $this->hasOne(Devolucion_salas_granel::className(), ['DE_NRODEVOL' => 'DF_NRODEVOL']);
    }

    //Obtiene todos los renglones de una Devolucion a proveedor
    public function get_renglones($id=0)
    {
        $query = Devolucion_salas_granel_renglones::find();

        // add conditions that should always apply here
        $query->andFilterWhere([
            'DF_NRODEVOL' => $id,
        ]);

        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
        ]);

        return $dataProvider;   
    }
}
