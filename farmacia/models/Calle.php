<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "calles".
 *
 * @property string $CA_CODIGO
 * @property string $CA_NOM
 * @property string $CA_CALLE
 * @property string $CA_NACE
 * @property string $CA_CORRE
 * @property string $CA_DEN_ANT
 * @property string $CA_COORDEN
 * @property string $CA_OBSERV
 */
class Calle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CA_CODIGO'], 'string', 'max' => 5],
            [['CA_NOM', 'CA_CALLE'], 'string', 'max' => 40],
            [['CA_NACE'], 'string', 'max' => 48],
            [['CA_CORRE'], 'string', 'max' => 73],
            [['CA_DEN_ANT'], 'string', 'max' => 33],
            [['CA_COORDEN'], 'string', 'max' => 10],
            [['CA_OBSERV'], 'string', 'max' => 57],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CA_CODIGO' => 'Código',
            'CA_NOM' => 'Nombre',
            'CA_CALLE' => 'Calle',
            'CA_NACE' => 'Nace en',
            'CA_CORRE' => 'Corre entre',
            'CA_DEN_ANT' => 'Denominación Anterior',
            'CA_COORDEN' => 'Coordenadas',
            'CA_OBSERV' => 'Observaciones',
        ];
    }

    public static function primaryKey()
    {
        return ['CA_CODIGO'];
    }
}
