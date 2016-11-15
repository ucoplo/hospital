<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\ValeEnfermeria;

/**
 * ValeEnfermeriaSearch represents the model behind the search form about `farmacia\models\ValeEnfermeria`.
 */
class ValeEnfermeriaSearch extends ValeEnfermeria
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VE_NUMVALE', 'VE_HISCLI', 'VE_IDINTERNA', 'VE_PROCESADO'], 'integer'],
            [['VE_FECHA', 'VE_HORA', 'VE_MEDICO', 'VE_SUPERV', 'VE_SERSOL', 'VE_UDSOL', 'VE_CONDPAC', 'VE_DEPOSITO'], 'safe'],
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
        $query = ValeEnfermeria::find();

        // add conditions that should always apply here
        //$query->joinWith(['interna']);

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
            'VE_NUMVALE' => $this->VE_NUMVALE,
            'VE_HISCLI' => $this->VE_HISCLI,
            'VE_FECHA' => $this->VE_FECHA,
            'VE_HORA' => $this->VE_HORA,
            'VE_IDINTERNA' => $this->VE_IDINTERNA,
            'VE_PROCESADO' => $this->VE_PROCESADO,
        ]);

        $query->andFilterWhere(['like', 'VE_MEDICO', $this->VE_MEDICO])
            ->andFilterWhere(['like', 'VE_SUPERV', $this->VE_SUPERV])
            ->andFilterWhere(['like', 'VE_SERSOL', $this->VE_SERSOL])
            ->andFilterWhere(['like', 'VE_UDSOL', $this->VE_UDSOL])
            ->andFilterWhere(['like', 'VE_CONDPAC', $this->VE_CONDPAC])
            ->andFilterWhere(['like', 'VE_DEPOSITO', $this->VE_DEPOSITO]);

        return $dataProvider;
    }

    public function vales_servicio_listado($condpac)
    {
        $query = (new \yii\db\Query())
        ->select(['VE_SERSOL', 'servicio.SE_DESCRI', 'count(vale_enf.VE_NUMVALE) as vales_pendientes',
            'vali_rem.VR_NROREM as ultimo_remito','VE_CONDPAC'])
        ->from('vale_enf')
        ->join('INNER JOIN', 'servicio', 'servicio.SE_CODIGO = vale_enf.VE_SERSOL')
        ->join('LEFT JOIN', 'vali_rem', "vali_rem.VR_SERSOL = vale_enf.VE_SERSOL and vali_rem.VR_CONDPAC = '".$condpac."'")
        ->groupBy(['VE_SERSOL', 'SE_DESCRI','VR_NROREM'])
        ->where(['VE_PROCESADO' => 0,'VE_CONDPAC'=>$condpac]);
                
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
    public function etiquetas($nrovale)
    {
        $query = (new \yii\db\Query())
        ->select(['ET_BMP'])
        ->from('vale_enf')
        ->join('INNER JOIN', 'pac_etiq', 'pac_etiq.et_hiscli = vale_enf.ve_hiscli')
        ->join('INNER JOIN', 'etiqueta', 'etiqueta.ET_COD = pac_etiq.et_etiq')
        ->where(['VE_NUMVALE' => $nrovale]);
                
        return $query->all();;
    }
}
