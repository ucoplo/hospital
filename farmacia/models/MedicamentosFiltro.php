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
class MedicamentosFiltro extends ReporteFiltro
{
    public $medicamento;

    public function rules()
    {
        return [
            [['deposito'], 'required'],
            [['deposito','clases','monodroga','medicamento'], 'safe'],
        ];
    }

    
    public function buscar($params)
    {
        $query = Medic::find();

      $query->joinWith(['monodroga']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->orderBy(['ME_CODMON'=>SORT_ASC]);

        $this->load($params);

         $query->andFilterWhere([
            'ME_DEPOSITO' => $this->deposito,
            'AG_ACTIVO' => 'T', //Articulo Activo
            'AG_VADEM' => 'S', //Que este en Vademecum
        ]);

        $query->andFilterWhere(['in', 'AG_CODCLA', $this->clases]);

        return $dataProvider;
    }

    public static function getListaMedicamentos()
    {
        $opciones = Medic::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'ME_CODIGO', 'ME_NOMCOM');
    }
}
