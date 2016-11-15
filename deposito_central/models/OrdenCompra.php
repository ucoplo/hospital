<?php

namespace deposito_central\models;

use Yii;

/**
 * This is the model class for table "ordenes_compra".
 *
 * @property string $OC_NRO
 * @property string $OC_PROVEED
 * @property string $OC_FECHA
 * @property integer $OC_FINALIZADA
 * @property integer $OC_PEDADQ
 *
 * @property PedAdq $oCPEDADQ
 * @property Proveedores $oCPROVEED
 * @property RemitoAdq[] $remitoAdqs
 * @property RengOc[] $rengOcs
 */
class OrdenCompra extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ordenes_compra';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['OC_NRO'], 'required'],
            [['OC_FECHA'], 'safe'],
            [['OC_FINALIZADA', 'OC_PEDADQ'], 'integer'],
            [['OC_NRO'], 'string', 'max' => 10],
            [['OC_PROVEED'], 'string', 'max' => 5],
            [['OC_PEDADQ'], 'exist', 'skipOnError' => true, 'targetClass' => Pedido_adquisicion::className(), 'targetAttribute' => ['OC_PEDADQ' => 'PE_NUM']],
            [['OC_PROVEED'], 'exist', 'skipOnError' => true, 'targetClass' => Proveedores::className(), 'targetAttribute' => ['OC_PROVEED' => 'PR_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'OC_NRO' => 'Número',
            'OC_PROVEED' => 'Proveedor',
            'OC_FECHA' => 'Fecha',
            'OC_FINALIZADA' => 'Indica si fue entregada totalmente o no',
            'OC_PEDADQ' => 'Número Pedido',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedido()
    {
        return $this->hasOne(Pedido_adquisicion::className(), ['PE_NUM' => 'OC_PEDADQ']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProveedor()
    {
        return $this->hasOne(Proveedores::className(), ['PR_CODIGO' => 'OC_PROVEED']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemitos()
    {
        return $this->hasMany(RemitoAdq::className(), ['RA_OCNRO' => 'OC_NRO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenglones()
    {
        return $this->hasMany(OrdenCompra_renglones::className(), ['EN_NROOC' => 'OC_NRO']);
    }

    public function getNumero()
    {
        return substr($this->OC_NRO, -6);
    }

    public function getEjercicio()
    {
        return substr($this->OC_NRO, 0,4);
    }

    public function chequear_finalizada(){

        $query =  OrdenCompra_renglones::find()
                   ->where(['EN_NROOC' => $this->OC_NRO]);

        $query->join('INNER JOIN', 'remito_adq',
                 "remito_adq.RA_OCNRO = EN_NROOC");

        $query->join('INNER JOIN', 'adq_reng',
                 "adq_reng.AR_RENUM = RA_NUM AND 
                 adq_reng.AR_CODART = EN_CODART AND 
                 adq_reng.AR_DEPOSITO = EN_DEPOSITO"); 

        $query->groupBy(['EN_CODART','EN_DEPOSITO','EN_CANT']);

        $query->select(['EN_CANT-SUM(AR_CANTID) as pendiente','EN_CODART','EN_DEPOSITO',
                        ]);

        $query->having('(EN_CANT-SUM(AR_CANTID))>0');

        
        $pendientes = $query->asArray()->all();
        
        if (count($pendientes)==0){
            $this->OC_FINALIZADA = 1;
        }
    }
}
