<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Ambulatorios_ventanilla;

/**
 * Ambulatorios_ventanillaSearch represents the model behind the search form about `farmacia\models\Ambulatorios_ventanilla`.
 */
class Ambulatorios_ventanillaSearch extends Ambulatorios_ventanilla
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AM_HISCLI', 'AM_NUMVALE'], 'integer'],
            [['AM_FECHA', 'AM_HORA', 'AM_PROG', 'AM_ENTIDER', 'AM_MEDICO', 'AM_DEPOSITO', 'AM_FARMACEUTICO'], 'safe'],
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
        $query = Ambulatorios_ventanilla::find();

        $query->orderBy(['AM_FECHA'=>SORT_DESC,'AM_NUMVALE'=>SORT_DESC]);
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
            'AM_HISCLI' => $this->AM_HISCLI,
            'AM_NUMVALE' => $this->AM_NUMVALE,
            'AM_FECHA' => $this->AM_FECHA,
            'AM_HORA' => $this->AM_HORA,
        ]);

        $query->andFilterWhere(['like', 'AM_PROG', $this->AM_PROG])
            ->andFilterWhere(['like', 'AM_ENTIDER', $this->AM_ENTIDER])
            ->andFilterWhere(['like', 'AM_MEDICO', $this->AM_MEDICO])
            ->andFilterWhere(['like', 'AM_DEPOSITO', $this->AM_DEPOSITO])
            ->andFilterWhere(['like', 'AM_FARMACEUTICO', $this->AM_FARMACEUTICO]);

        return $dataProvider;
    }
}
