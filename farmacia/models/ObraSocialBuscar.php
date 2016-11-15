<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\ObraSocial;

/**
 * ObraSocialBuscar represents the model behind the search form about `smu\models\ObraSocial`.
 */
class ObraSocialBuscar extends ObraSocial
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['OB_COD', 'OB_NOM', 'OB_NOMCOMP', 'OB_SINON', 'OB_DIRECC', 'OB_CUIT', 'OB_DGI', 'OB_SUBCOD', 'OB_COSEG', 'OB_ENTE', 'OB_CONVEN', 'OB_CJTONOM', 'OB_RUBNFG', 'OB_RUBNFH', 'OB_CTACTE', 'OB_REQUISI', 'OB_FACTDIR', 'OB_RUBHONO', 'OB_FECHA', 'OB_ARTSEG', 'OB_CAPITA', 'OB_INSTSMU', 'OB_INSTCEX', 'OB_INSTDXI', 'OB_INSTLAB', 'OB_INSTINT', 'OB_ACTIVA', 'OB_TEL'], 'safe'],
            [['OB_DIAPRES', 'OB_FECPRES'], 'integer'],
            [['OB_PORGAS', 'OB_PORHON', 'OB_USGOPER', 'OB_USOTGAS', 'OB_USGRADI', 'OB_USGCLIN', 'OB_USPENSI', 'OB_USGALEN', 'OB_UGASBIO', 'OB_USGALQU', 'OB_USGAPA'], 'number'],
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
        $query = ObraSocial::find();

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
            'OB_DIAPRES' => $this->OB_DIAPRES,
            'OB_FECPRES' => $this->OB_FECPRES,
            'OB_PORGAS' => $this->OB_PORGAS,
            'OB_PORHON' => $this->OB_PORHON,
            'OB_USGOPER' => $this->OB_USGOPER,
            'OB_USOTGAS' => $this->OB_USOTGAS,
            'OB_USGRADI' => $this->OB_USGRADI,
            'OB_USGCLIN' => $this->OB_USGCLIN,
            'OB_USPENSI' => $this->OB_USPENSI,
            'OB_USGALEN' => $this->OB_USGALEN,
            'OB_UGASBIO' => $this->OB_UGASBIO,
            'OB_FECHA' => $this->OB_FECHA,
            'OB_USGALQU' => $this->OB_USGALQU,
            'OB_USGAPA' => $this->OB_USGAPA,
        ]);

        $query->andFilterWhere(['like', 'OB_COD', $this->OB_COD])
            ->andFilterWhere(['like', 'OB_NOM', $this->OB_NOM])
            ->andFilterWhere(['like', 'OB_NOMCOMP', $this->OB_NOMCOMP])
            ->andFilterWhere(['like', 'OB_SINON', $this->OB_SINON])
            ->andFilterWhere(['like', 'OB_DIRECC', $this->OB_DIRECC])
            ->andFilterWhere(['like', 'OB_CUIT', $this->OB_CUIT])
            ->andFilterWhere(['like', 'OB_DGI', $this->OB_DGI])
            ->andFilterWhere(['like', 'OB_SUBCOD', $this->OB_SUBCOD])
            ->andFilterWhere(['like', 'OB_COSEG', $this->OB_COSEG])
            ->andFilterWhere(['like', 'OB_ENTE', $this->OB_ENTE])
            ->andFilterWhere(['like', 'OB_CONVEN', $this->OB_CONVEN])
            ->andFilterWhere(['like', 'OB_CJTONOM', $this->OB_CJTONOM])
            ->andFilterWhere(['like', 'OB_RUBNFG', $this->OB_RUBNFG])
            ->andFilterWhere(['like', 'OB_RUBNFH', $this->OB_RUBNFH])
            ->andFilterWhere(['like', 'OB_CTACTE', $this->OB_CTACTE])
            ->andFilterWhere(['like', 'OB_REQUISI', $this->OB_REQUISI])
            ->andFilterWhere(['like', 'OB_FACTDIR', $this->OB_FACTDIR])
            ->andFilterWhere(['like', 'OB_RUBHONO', $this->OB_RUBHONO])
            ->andFilterWhere(['like', 'OB_ARTSEG', $this->OB_ARTSEG])
            ->andFilterWhere(['like', 'OB_CAPITA', $this->OB_CAPITA])
            ->andFilterWhere(['like', 'OB_INSTSMU', $this->OB_INSTSMU])
            ->andFilterWhere(['like', 'OB_INSTCEX', $this->OB_INSTCEX])
            ->andFilterWhere(['like', 'OB_INSTDXI', $this->OB_INSTDXI])
            ->andFilterWhere(['like', 'OB_INSTLAB', $this->OB_INSTLAB])
            ->andFilterWhere(['like', 'OB_INSTINT', $this->OB_INSTINT])
            ->andFilterWhere(['like', 'OB_ACTIVA', $this->OB_ACTIVA])
            ->andFilterWhere(['like', 'OB_TEL', $this->OB_TEL]);

        return $dataProvider;
    }
}
