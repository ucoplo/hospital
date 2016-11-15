<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\ValeGranel;

/**
 * ValeGranelSearch represents the model behind the search form about `farmacia\models\ValeGranel`.
 */
class ValeGranelSearch extends ValeGranel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VM_SERSOL', 'VM_FECHA', 'VM_HORA', 'VM_SUPERV', 'VM_DEPOSITO'], 'safe'],
            [['VM_NUMVALE', 'VM_PROCESADO'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ValeGranel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'VM_NUMVALE' => $this->VM_NUMVALE,
            'VM_FECHA' => $this->VM_FECHA,
            'VM_HORA' => $this->VM_HORA,
            'VM_PROCESADO' => $this->VM_PROCESADO,
        ]);

        $query->andFilterWhere(['like', 'VM_SERSOL', $this->VM_SERSOL])
            ->andFilterWhere(['like', 'VM_SUPERV', $this->VM_SUPERV])
            ->andFilterWhere(['like', 'VM_DEPOSITO', $this->VM_DEPOSITO]);

        return $dataProvider;
    }

    public function vales_servicio_listado()
    {
        $query = (new \yii\db\Query())
        ->select(['VM_SERSOL', 'servicio.SE_DESCRI',
            'legajos.LE_APENOM as supervisor','VM_FECHA','VM_HORA','VM_NUMVALE'])
        ->from('vale_mon')
        ->join('INNER JOIN', 'servicio', 'servicio.SE_CODIGO = vale_mon.VM_SERSOL')
        ->join('INNER JOIN', 'legajos', 'legajos.LE_NUMLEGA = vale_mon.VM_SUPERV')
        ->where(['VM_PROCESADO' => 0])
        ->orderBy(['VM_FECHA'=>SORT_ASC,'VM_HORA'=>SORT_ASC]);  

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
