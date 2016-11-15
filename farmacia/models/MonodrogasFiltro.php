<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Remito_adquisicion;
use yii\helpers\ArrayHelper;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `farmacia\models\Remito_Adquisicion`.
 */
class MonodrogasFiltro extends ReporteFiltro
{
    public $medicamento;
    public $activo,$vademecum;

    public function rules()
    {
        return [
            [['deposito'], 'required'],
            [['deposito','clases','monodroga','activo','vademecum'], 'safe'],
        ];
    }

    
    public function buscar($params)
    {
        $query = ArticGral::find();

        $query->joinWith(['clase']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['clase'] = [
            'asc' => ['clases.CL_NOM' => SORT_ASC],
            'desc' => ['clases.CL_NOM' => SORT_DESC],
        ];

        $this->load($params);

        $query->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
            'AG_ACTIVO' => $this->activo, //Articulo Activo
            'AG_VADEM' => $this->vademecum,  //Que este en Vademecum
        ]);

        $query->andFilterWhere(['like', 'AG_CODIGO', $this->monodroga])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        return $dataProvider;

    }

   
}
