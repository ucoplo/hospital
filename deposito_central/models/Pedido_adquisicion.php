<?php

namespace deposito_central\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "ped_adq".
 *
 * @property string $PE_FECHA
 * @property integer $PE_NUM
 * @property string $PE_HORA
 * @property string $PE_COSTO
 * @property string $PE_REFERENCIA
 * @property string $PE_NROEXP
 * @property string $PE_FECADJ
 * @property string $PE_DEPOSITO
 * @property string $PE_ARTDES
 * @property string $PE_ARTHAS
 * @property string $PE_CLASES
 * @property integer $PE_ACTIVOS
 * @property integer $PE_INACTIVOS
 * @property integer $PE_EXISACT
 * @property integer $PE_PEDPEND
 * @property string $PE_PONDHIS
 * @property string $PE_PONDPUN
 * @property string $PE_CLASABC
 * @property integer $PE_DIASABC
 * @property integer $PE_DIASPREVIS
 * @property integer $PE_DIASDEMORA
 *
 * @property OrdenesCompra[] $ordenesCompras
 * @property PeadMov[] $peadMovs
 * @property ArticGral $pEARTDES
 * @property ArticGral $pEARTHAS
 * @property Deposito $pEDEPOSITO
 */
class Pedido_adquisicion extends \yii\db\ActiveRecord
{
    public $renglones;
    public $CLASE_A,$CLASE_B,$CLASE_C;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ped_adq';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PE_FECHA', 'PE_HORA', 'PE_REFERENCIA', 'PE_DEPOSITO', 'PE_EXISACT', 'PE_PEDPEND', 'PE_PONDHIS', 'PE_PONDPUN', 'PE_DIASPREVIS', 'PE_DIASDEMORA','renglones'], 'required'],
            [['PE_FECHA', 'PE_HORA', 'PE_FECADJ','PE_CLASES','renglones','CLASE_A','CLASE_B','CLASE_C','PE_CLASABC'], 'safe'],
            [['PE_COSTO', 'PE_PONDHIS', 'PE_PONDPUN'], 'number'],
            [['PE_REFERENCIA'], 'string'],
            [['PE_ACTIVOS', 'PE_INACTIVOS','PE_EXISACT', 'PE_PEDPEND', 'PE_DIASABC', 'PE_DIASPREVIS', 'PE_DIASDEMORA'], 'integer'],
            [['PE_NROEXP'], 'string', 'max' => 10],
            [['PE_DEPOSITO'], 'string', 'max' => 2],
            [['PE_ARTDES', 'PE_ARTHAS'], 'string', 'max' => 4],
            //[['PE_CLASES'], 'string', 'max' => 120],
            [['renglones'],'validateCantidad'],
            [['PE_DEPOSITO'], 'exist', 'skipOnError' => false, 'targetClass' => Deposito::className(), 'targetAttribute' => ['PE_DEPOSITO' => 'DE_CODIGO']],
            //[['PE_ARTDES'], 'exist', 'skipOnError' => false, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['PE_ARTDES' => 'AG_CODIGO', 'PE_DEPOSITO' => 'AG_DEPOSITO']],
            //[['PE_ARTHAS'], 'exist', 'skipOnError' => false, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['PE_ARTHAS' => 'AG_CODIGO', 'PE_DEPOSITO' => 'AG_DEPOSITO']],
        ];
    }

     public function validateCantidad($attribute, $params)
    {
        foreach($this->$attribute as $index => $renglon) {
            $cantidad = $renglon['PE_CANTPED'];
            
            if ($cantidad<=0) {
                $key = $attribute . '[' . $index . '][PE_CANTPED]';
                $this->addError($key, 'Mayor a 0.');
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PE_NUM' => 'Número',
            'PE_FECHA' => 'Fecha',
            'PE_HORA' => 'Hora',
            'PE_COSTO' => 'Costo total',
            'PE_REFERENCIA' => 'Referencia libre',
            'PE_NROEXP' => 'Número de expediente, una vez caratulado',
            'PE_FECADJ' => 'Fecha de adjudicación',
            'PE_DEPOSITO' => 'Depósito',
            'PE_ARTDES' => 'Artículo desde',
            'PE_ARTHAS' => 'Artículo hasta',
            'PE_CLASES' => 'Clases',
            'PE_ACTIVOS' => 'Activos',
            'PE_INACTIVOS' => 'Inactivos',
            'PE_EXISACT' => 'Contempla existencia actual',
            'PE_PEDPEND' => 'Contempla pedidos pendientes',
            'PE_PONDHIS' => 'Ponderación consumo histórico %',
            'PE_PONDPUN' => 'Ponderación consumo puntual %',
            'PE_CLASABC' => 'Filtrar por clase A,B ó C',
            'PE_DIASABC' => 'Cantidad de días calculó A, B o C',
            'PE_DIASPREVIS' => 'Días de previsión',
            'PE_DIASDEMORA' => 'Días de demorará del trámite',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenesCompras()
    {
        return $this->hasMany(OrdenCompra::className(), ['OC_PEDADQ' => 'PE_NUM']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenglones()
    {
        return $this->hasMany(Pedido_adquisicion_renglones::className(), ['PE_NUM' => 'PE_NUM']);
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticulo_desde()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'PE_ARTDES', 'AG_DEPOSITO' => 'PE_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticulo_hasta()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'PE_ARTHAS', 'AG_DEPOSITO' => 'PE_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'PE_DEPOSITO']);
    }

     public static function getListaClases()
    {
        $opciones = Clases::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'CL_COD', 'CL_NOM');
    }
     public static function getListaDeposito()
    {
        $opciones = Deposito::find();//->asArray()->all();
        $depositos = Yii::$app->params['depositos_central'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

     public function abc()
    {
        $query = Movimientos_diarios::find();

        $query->joinWith(['articulo']);
        $query->joinWith(['codigo']);
                
        $dias = $this->PE_DIASABC;
        $fecha_inicio = date('Y-m-d', strtotime("-$this->PE_DIASABC day"));

        $query->andFilterWhere(['>=', 'DM_FECHA', $fecha_inicio]);

        $fecha_fin = date('Y-m-d');
        $query->andFilterWhere(['<=', 'DM_FECHA', $fecha_fin]);
        
     
        // // grid filtering conditions
         $query->andFilterWhere(['DM_VALIDO'=>0]);

        $query->andFilterWhere(['like', 'DM_DEPOSITO', $this->PE_DEPOSITO])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->PE_CLASES])
            ->andFilterWhere(['IN', 'DM_COD', ['D','V']]); //D= DEVOLUCION DE SERVICIO V=ENTREGADO
        
        
        $query->groupBy(['DM_CODART']);

        $query->select(['SUM(DM_CANT*DM_SIGNO*-1) as consumo',
                        'SUM(DM_CANT*DM_SIGNO*-1*AG_PRECIO) as consumo_valor',
                        '`dc_mov_dia`.*']);

        $query->orderBy(['consumo_valor'=>SORT_DESC]);    
       
        return $query->all();

    }
}
