<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Movimientos_diarios;
use yii\helpers\ArrayHelper;

/**
 * Movimientos_diariosSearch represents the model behind the search form about `deposito_central\models\Movimientos_diarios`.
 */
class FiltroReporte extends \yii\db\ActiveRecord
{
    public $fecha_inicio;
    public $fecha_fin;
    public $deposito;
    public $clases;
    public $articulo;

    public static function tableName()
    {
        return 'dc_mov_dia';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
        ];
    }

    public function attributeLabels()
    {
        return [
            'deposito' => 'Depósito',
            'clases' => 'Clases',
            'articulo' => 'Artículo',
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

    public static function getListaArticulos()
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
        $depositos = Yii::$app->params['depositos_central'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

    public static function getListaServicios()
    {
        $opciones = Servicio::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'SE_CODIGO', 'SE_DESCRI');
    }

     public function getDeposito_descripcion(){
        return Deposito::findOne($this->deposito)->DE_DESCR;
    }

    public function getArticulo_descripcion(){
        if ($articulo=ArticGral::findOne(['AG_CODIGO'=>$this->articulo,'AG_DEPOSITO'=>$this->deposito]))
            return "[$this->articulo]-".$articulo->AG_NOMBRE;
        else
            return '';
    }

    public function getClases_descripcion(){
        $lista_clases = "";
        if (isset($this->clases) && !empty($this->clases)){ 
      
            

            foreach ($this->clases as $key => $value) {
               $lista_clases .= Clases::findOne($value)->CL_NOM.', ';
            }
        }
        return $lista_clases;
           
    }

}
