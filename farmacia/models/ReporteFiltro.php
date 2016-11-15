<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Movimientos_diarios;
use yii\helpers\ArrayHelper;

/**
 * Movimientos_diariosSearch represents the model behind the search form about `farmacia\models\Movimientos_diarios`.
 */
class ReporteFiltro extends \yii\db\ActiveRecord
{
    public $periodo_inicio;
    public $periodo_fin;
    public $deposito;
    public $clases;
    public $monodroga;

    public static function tableName()
    {
        return 'mov_dia';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
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

    public static function getListaMonodrogas()
    {
        $opciones = ArticGral::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'AG_CODIGO', 'AG_NOMBRE');
    }

    public static function getListaClases()
    {
        $opciones = Clases::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'CL_COD', 'CL_NOM');
    }

    public function getListaDepositos()
    {
        $opciones = Deposito::find();
        $depositos = Yii::$app->params['depositos_farmacia'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

    public static function getListaServicios()
    {
        $opciones = Servicio::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'SE_CODIGO', 'SE_DESCRI');
    }

    
}
