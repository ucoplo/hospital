<?php

namespace farmacia\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "kairos_baspro".
 *
 * @property string $codigo
 * @property string $descripcion
 * @property string $laboratorio
 * @property string $origen
 * @property string $psicofarmaco
 * @property string $codigo_venta
 * @property string $estupefaciente
 * @property string $estado
 */
class Productos_kairos extends \yii\db\ActiveRecord
{
    public $presentaciones;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kairos_baspro';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codigo'], 'string', 'max' => 6],
            [['descripcion'], 'string', 'max' => 40],
            [['laboratorio'], 'string', 'max' => 5],
            [['origen', 'psicofarmaco', 'codigo_venta', 'estupefaciente', 'estado'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'codigo' => 'Codigo',
            'descripcion' => 'Descripcion',
            'laboratorio' => 'Laboratorio',
            'origen' => 'Origen',
            'psicofarmaco' => 'Psicofarmaco',
            'codigo_venta' => 'Codigo Venta',
            'estupefaciente' => 'Estupefaciente',
            'estado' => 'Estado',
        ];
    }

    public function getLaboratorio_descripcion()
    {   
        $connection = \Yii::$app->db;
        $model = $connection->createCommand("SELECT * FROM kairos_baslab where codigo=$this->laboratorio");

        $labo = $model->queryOne();

        return $labo['descripcion'];
    }

}
