<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Remito_adquisicion;
use yii\helpers\ArrayHelper;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `deposito_central\models\Remito_Adquisicion`.
 */
class FiltroArticulos extends FiltroReporte
{
    public $activo;

    public function rules()
    {
        return [
            [['deposito'], 'required'],
            [['deposito','clases','articulo','activo'], 'safe'],
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
        ]);

        $query->andFilterWhere(['like', 'AG_CODIGO', $this->articulo])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        return $dataProvider;

    }

    public function getActivo_descripcion(){
        if ($this->activo=='T')
            return "Activos";
        elseif ($this->activo=='F')
            return 'Inactivos';
        else
            return "Todos";
    }
}
