<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Productos_kairos;
use yii\data\ArrayDataProvider ;

/**
 * Productos_kairosSearch represents the model behind the search form about `farmacia\models\Productos_kairos`.
 */
class Productos_kairosSearch extends Productos_kairos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codigo', 'descripcion', 'laboratorio', 'origen', 'psicofarmaco', 'codigo_venta', 'estupefaciente', 'estado'], 'safe'],
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
        $query = Productos_kairos::find();

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
        $query->andFilterWhere(['like', 'codigo', $this->codigo])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'laboratorio', $this->laboratorio])
            ->andFilterWhere(['like', 'origen', $this->origen])
            ->andFilterWhere(['like', 'psicofarmaco', $this->psicofarmaco])
            ->andFilterWhere(['like', 'codigo_venta', $this->codigo_venta])
            ->andFilterWhere(['like', 'estupefaciente', $this->estupefaciente])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }

        //Obtiene todas las presentaciones de un producto
    public function get_presentaciones($id=0)
    {
        $sql_prodfarm = "select CONCAT(LPAD(TRIM(kairos_baspre.codigo_producto),6,'0'),
                                LPAD(TRIM(kairos_baspre.codigo_presentacion),2,'0')) as codigo,
                            kairos_baspre.codigo_troquel as codigo_troquel,
                            CONCAT(kairos_baspro.descripcion,' ',kairos_baspre.descripcion) as descripcion,
                            kairos_bastip.especificacion as caracteristicas,
                            kairos_bastip.via as via,
                            kairos_bastip.forma as presentacion,
                            kairos_bastip.concentracion as dosis,
                            kairos_bastip.unid_concentracion as medida_dosis,
                            kairos_bastip.comentario_concentracion as observaciones,
                            kairos_bastip.cantidad_envase as unidades_envase,
                            kairos_bastip.cantidad_unidad as contenido,
                            kairos_bastip.unidad_cantidad as medida_contenido,
                            kairos_bastip.dosis as cantidad_dosis,
                            kairos_baspre.descripcion as desc_presentacion, 
                            kairos_baslab.descripcion as laboratorio,
                            kairos_basprc.precio_publico,
                            CASE WHEN (kairos_bastip.cantidad_envase=0) THEN  kairos_basprc.precio_publico
                                                                                    ELSE kairos_basprc.precio_publico/kairos_bastip.cantidad_envase END
                                                                                    as precio_publico_unitario,
                           kairos_basprc.fecha_vigencia,
                           drogas.descripcion as monodroga,
                            kairos_baspre.codigo_barras,
                            kairos_basiom.monto as importe_ioma,
                            kairos_basiom.fecha_vigencia as fecha_vigencia_ioma,
                            kairos_baspam.monto as importe_pami,
                            kairos_baspam.fecha_vigencia as fecha_vigencia_pami,
                            CASE WHEN (kairos_baspro.estado='A') THEN 'S' ELSE 'N' END as activo                                                        
                        from kairos_baspro
                        inner join kairos_baspre on kairos_baspre.codigo_producto=kairos_baspro.codigo 
                        left join kairos_baslab on kairos_baslab.codigo=kairos_baspro.laboratorio 
                        left join kairos_basprc on kairos_basprc.codigo_producto=kairos_baspre.codigo_producto and
                                                             kairos_basprc.codigo_presentacion = kairos_baspre.codigo_presentacion
                        left join kairos_basiom on kairos_basiom.codigo_producto=kairos_baspre.codigo_producto and
                                                             kairos_basiom.codigo_presentacion = kairos_baspre.codigo_presentacion
                        left join kairos_baspam on kairos_baspam.codigo_producto=kairos_baspre.codigo_producto and
                                                             kairos_baspam.codigo_presentacion = kairos_baspre.codigo_presentacion          
                        left join ( SELECT kairos_basdp.codigo_producto, GROUP_CONCAT(TRIM(kairos_basdro.descripcion) SEPARATOR '+') as descripcion FROM kairos_basdp 
                                        inner join kairos_basdro on kairos_basdro.codigo=codigo_droga
                                        where kairos_basdp.codigo_producto = $id
                                        GROUP BY codigo_producto
                                        
                                        ) as drogas on drogas.codigo_producto = kairos_baspro.codigo            

                        left join kairos_bastip on kairos_bastip.codigo_producto= kairos_baspre.codigo_producto and
                                                            kairos_bastip.codigo_presentacion= kairos_baspre.codigo_presentacion

                            
                        where kairos_baspro.codigo = $id";
        $connection = \Yii::$app->db;
        $comando = $connection->createCommand($sql_prodfarm);
        
        
        $query = $comando->queryAll();

        // $query = (new \yii\db\Query())
        //   //->select(["sum(cantidad) as total_consumo","codmon",'AG_PRECIO','AG_NOMBRE','unidiag',"se_descri",'CONCAT(unidiag,codmon) as `codigo`'])
        //   ->from(['kairos_baspre']);

        // $query->andFilterWhere([
        //     'kairos_baspre.codigo_producto' => $id,
        // ]);

        // $query->join('INNER JOIN', 'kairos_basprc',
        //          "kairos_basprc.codigo_producto = kairos_baspre.codigo_producto AND
        //          kairos_basprc.codigo_presentacion = kairos_baspre.codigo_presentacion");  

        // // $query->orderBy([
        // //    'VA_NUMRENG'=>SORT_ASC]);
        $dataProvider= new ArrayDataProvider([
                                'allModels' => $query,]);

        // $dataProvider = new ActiveDataProvider([
        //     'query' => $query,
        //     'sort' =>false,
        // ]);

        return $dataProvider;   
    }
}
