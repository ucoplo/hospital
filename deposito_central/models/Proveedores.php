<?php

namespace deposito_central\models;

use Yii;

/**
 * This is the model class for table "proveedores".
 *
 * @property string $PR_CODIGO
 * @property string $PR_RAZONSOC
 * @property string $PR_TITULAR
 * @property string $PR_CODRAFAM
 * @property string $PR_CUIT
 * @property string $PR_DOMIC
 * @property string $PR_TELEF
 * @property string $PR_EMAIL
 * @property string $PR_OBS
 * @property string $PR_CONTACTO
 *
 * @property DcDevoprov[] $dcDevoprovs
 * @property OrdenesCompra[] $ordenesCompras
 */
class Proveedores extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proveedores';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PR_CODIGO', 'PR_RAZONSOC', 'PR_TITULAR', 'PR_CODRAFAM', 'PR_CUIT', 'PR_DOMIC'], 'required'],
            [['PR_OBS'], 'string'],
            [['PR_CODIGO', 'PR_CODRAFAM'], 'string', 'max' => 5],
            [['PR_RAZONSOC', 'PR_TITULAR'], 'string', 'max' => 70],
            [['PR_CUIT'], 'string', 'max' => 13],
            [['PR_DOMIC'], 'string', 'max' => 40],
            [['PR_TELEF'], 'string', 'max' => 30],
            [['PR_EMAIL'], 'string', 'max' => 60],
            [['PR_CONTACTO'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PR_CODIGO' => 'Código Proveedor',
            'PR_RAZONSOC' => 'Razón Social',
            'PR_TITULAR' => 'Apellido y nombre del titular',
            'PR_CODRAFAM' => 'Código Proveedor Rafam',
            'PR_CUIT' => 'CUIT',
            'PR_DOMIC' => 'Domicilio',
            'PR_TELEF' => 'Teléfono',
            'PR_EMAIL' => 'Email',
            'PR_OBS' => 'Observaciones',
            'PR_CONTACTO' => 'Contacto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevoluciones()
    {
        return $this->hasMany(Devolucion_proveedor::className(), ['DD_PROVE' => 'PR_CODIGO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenesCompra()
    {
        return $this->hasMany(OrdenCompra::className(), ['OC_PROVEED' => 'PR_CODIGO']);
    }
}
