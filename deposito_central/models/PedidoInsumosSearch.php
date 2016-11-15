<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\PedidoInsumos;

/**
 * PedidoMedicamentosSearch represents the model behind the search form about `deposito_central\models\PedidoMedicamentos`.
 */
class PedidoInsumosSearch extends PedidoInsumos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VD_SERSOL', 'VD_FECHA', 'VD_HORA', 'VD_SUPERV', 'VD_DEPOSITO'], 'safe'],
            [['VD_NUMVALE', 'VD_PROCESADO'], 'integer'],
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
    public function search($rubro)
    {
        switch ($rubro) {
            case 'material-dxi':
                $query = PedidoInsumos::find()->andWhere(['VD_DEPOSITO' => '53']);
                break;
            case 'descartable':
                $query = PedidoInsumos::find()->andWhere(['VD_DEPOSITO' => '51']);
                break;
            case 'libreria':
                $query = PedidoInsumos::find()->andWhere(['VD_DEPOSITO' => '57']);
                break;

            default:
                # code...
                break;
        }

        $query->orderBy(['VD_FECHA' => SORT_DESC, 'VD_HORA' => SORT_DESC]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        // $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        // $query->andFilterWhere([
        //     'VD_NUMVALE' => $this->VD_NUMVALE,
        //     'VD_FECHA' => $this->VD_FECHA,
        //     'VD_HORA' => $this->VD_HORA,
        //     'VD_PROCESADO' => $this->VD_PROCESADO,
        // ]);
        //
        // $query->andFilterWhere(['like', 'VD_SERSOL', $this->VD_SERSOL])
        //     ->andFilterWhere(['like', 'VD_SUPERV', $this->VD_SUPERV])
        //     ->andFilterWhere(['like', 'VD_DEPOSITO', $this->VD_DEPOSITO]);

        return $dataProvider;
    }

    public function vales_servicio_listado()
    {
        $query = (new \yii\db\Query())
        ->select(['VD_SERSOL', 'servicio.SE_DESCRI',
            'legajos.LE_APENOM as supervisor','VD_FECHA','VD_HORA','VD_NUMVALE'])
        ->from('vale_des')
        ->join('INNER JOIN', 'servicio', 'servicio.SE_CODIGO = vale_des.VD_SERSOL')
        ->join('INNER JOIN', 'legajos', 'legajos.LE_NUMLEGA = vale_des.VD_SUPERV')
        ->where(['VD_PROCESADO' => 0])
        ->orderBy(['VD_FECHA'=>SORT_ASC,'VD_HORA'=>SORT_ASC]);  

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
