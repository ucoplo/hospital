<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\ArticGral;

/**
 * ArticGralSearch represents the model behind the search form about `deposito_central\models\ArticGral`.
 */
class ArticGralSearch extends ArticGral
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AG_CODIGO', 'AG_NOMBRE', 'AG_CODMED', 'AG_PRES', 'AG_CODCLA', 'AG_FRACCQ', 'AG_PSICOF', 'AG_RENGLON', 'AG_REPAUT', 'AG_ULTENT', 'AG_ULTSAL', 'AG_UENTDEP', 'AG_USALDEP', 'AG_PROVINT', 'AG_ACTIVO', 'AG_VADEM', 'AG_ORIGUSUA', 'AG_FRACSAL', 'AG_DROGA', 'AG_VIA', 'AG_ACCION', 'AG_VISIBLE', 'AG_DEPOSITO'], 'safe'],
            [['AG_STACT', 'AG_STACDEP', 'AG_PTOMIN', 'AG_FPTOMIN', 'AG_PTOPED', 'AG_FPTOPED', 'AG_PTOMAX', 'AG_FPTOMAX', 'AG_CONSDIA', 'AG_FCONSDI', 'AG_PRECIO', 'AG_REDOND', 'AG_PUNTUAL', 'AG_FPUNTUAL', 'AG_DOSIS'], 'number'],
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
        $query = ArticGral::find();

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
            'AG_STACT' => $this->AG_STACT,
            'AG_STACDEP' => $this->AG_STACDEP,
            'AG_PTOMIN' => $this->AG_PTOMIN,
            'AG_FPTOMIN' => $this->AG_FPTOMIN,
            'AG_PTOPED' => $this->AG_PTOPED,
            'AG_FPTOPED' => $this->AG_FPTOPED,
            'AG_PTOMAX' => $this->AG_PTOMAX,
            'AG_FPTOMAX' => $this->AG_FPTOMAX,
            'AG_CONSDIA' => $this->AG_CONSDIA,
            'AG_FCONSDI' => $this->AG_FCONSDI,
            'AG_PRECIO' => $this->AG_PRECIO,
            'AG_REDOND' => $this->AG_REDOND,
            'AG_PUNTUAL' => $this->AG_PUNTUAL,
            'AG_FPUNTUAL' => $this->AG_FPUNTUAL,
            'AG_ULTENT' => $this->AG_ULTENT,
            'AG_ULTSAL' => $this->AG_ULTSAL,
            'AG_UENTDEP' => $this->AG_UENTDEP,
            'AG_USALDEP' => $this->AG_USALDEP,
            'AG_DOSIS' => $this->AG_DOSIS,
        ]);

        $query->andFilterWhere(['like', 'AG_CODIGO', $this->AG_CODIGO])
            ->andFilterWhere(['like', 'AG_NOMBRE', $this->AG_NOMBRE])
            ->andFilterWhere(['like', 'AG_CODMED', $this->AG_CODMED])
            ->andFilterWhere(['like', 'AG_PRES', $this->AG_PRES])
            ->andFilterWhere(['like', 'AG_CODCLA', $this->AG_CODCLA])
            ->andFilterWhere(['like', 'AG_FRACCQ', $this->AG_FRACCQ])
            ->andFilterWhere(['like', 'AG_PSICOF', $this->AG_PSICOF])
            ->andFilterWhere(['like', 'AG_RENGLON', $this->AG_RENGLON])
            ->andFilterWhere(['like', 'AG_REPAUT', $this->AG_REPAUT])
            ->andFilterWhere(['like', 'AG_PROVINT', $this->AG_PROVINT])
            ->andFilterWhere(['like', 'AG_ACTIVO', $this->AG_ACTIVO])
            ->andFilterWhere(['like', 'AG_VADEM', $this->AG_VADEM])
            ->andFilterWhere(['like', 'AG_ORIGUSUA', $this->AG_ORIGUSUA])
            ->andFilterWhere(['like', 'AG_FRACSAL', $this->AG_FRACSAL])
            ->andFilterWhere(['like', 'AG_DROGA', $this->AG_DROGA])
            ->andFilterWhere(['like', 'AG_VIA', $this->AG_VIA])
            ->andFilterWhere(['like', 'AG_ACCION', $this->AG_ACCION])
            ->andFilterWhere(['like', 'AG_VISIBLE', $this->AG_VISIBLE])
            ->andFilterWhere(['like', 'AG_DEPOSITO', $this->AG_DEPOSITO]);

        return $dataProvider;
    }
}
