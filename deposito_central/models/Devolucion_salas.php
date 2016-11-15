<?php

namespace deposito_central\models;

use Yii;
use yii\validators\NumberValidator;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "devoluc".
 *
 * @property integer $DE_NRODEVOL
 * @property string $DE_FECHA
 * @property string $DE_HORA
 * @property string $DE_SERSOL
 * @property string $DE_CODOPE
 * @property string $DE_ENFERM
 * @property integer $DE_SOBRAN
 * @property integer $DE_NUMREMOR
 * @property string $DE_DEPOSITO
 *
 * @property Devofar[] $devofars
 * @property Consme3 $dENUMREMOR
 * @property Deposito $deposito
 * @property Legajos $operador
 * @property Legajos $enfermero
 * @property Servicio $dESERSOL
 */
class Devolucion_salas extends \yii\db\ActiveRecord
{
    public $renglones;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'plan_dev';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            [['DE_NRODEVOL', 'DE_SOBRAN', 'DE_NUMREMOR'], 'integer'],
            [['DE_FECHA', 'DE_HORA'], 'safe'],
            [['DE_SERSOL','DE_DEPOSITO','renglones'], 'required'],
            [['DE_SERSOL'], 'string', 'max' => 3],
            [['DE_CODOPE', 'DE_ENFERM'], 'string', 'max' => 6],
            [['DE_DEPOSITO'], 'string', 'max' => 2],
            [['renglones'],'validateRenglonesCreate', 'on' => 'create'],
            [['renglones'],'validateRenglonesCreateSobrante', 'on' => 'create_sobrante'],
            [['DE_NUMREMOR'], 'exist', 'skipOnError' => true, 'targetClass' => Planilla_entrega::className(), 'targetAttribute' => ['DE_NUMREMOR' => 'PE_NROREM']],
            [['DE_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['DE_DEPOSITO' => 'DE_CODIGO']],
            [['DE_CODOPE'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['DE_CODOPE' => 'LE_NUMLEGA']],
            [['DE_ENFERM'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['DE_ENFERM' => 'LE_NUMLEGA']],
            [['DE_SERSOL'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['DE_SERSOL' => 'SE_CODIGO']],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create_sobrante'] = ['DE_SERSOL','DE_DEPOSITO','DE_CODOPE','DE_FECHA','DE_HORA','renglones'];//Scenario Values Only Accepted
        return $scenarios;
    }

    public function validateRenglonesCreate($attribute, $params)
    {
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['PR_CODART'];
            $deposito = $this->DE_DEPOSITO;
            if (!$this->existe_articulo($codart, $deposito)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no existe en el depósito seleccionado');
            }
        }

        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['PR_CANTID'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'Cantidad debe ser un número');
            }

           
        }

        $codigos_monodrogas = [];
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['PR_CODART'];
            if (in_array($codart, $codigos_monodrogas)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no puede repetirse');
            }
            else{
                $codigos_monodrogas[] = $codart;
            }

        }

        //verificar existencia
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['PR_CODART'];
            $deposito = $this->DE_DEPOSITO;
            $fecven = date('Y-m-d', strtotime(str_replace("/","-",$renglon['PR_FECVTO'])));
            $cantidad_entregada = $this->cantidad_entregada($codart,$deposito,$fecven);

            if ($cantidad_entregada<$renglon['PR_CANTID']) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, "Máximo a Devolver s/Remito. $cantidad_entregada");
                
            }
        }
    }

     public function validateRenglonesCreateSobrante($attribute, $params)
    {   
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['PR_CODART'];
            $deposito = $this->DE_DEPOSITO;
            if (!$this->existe_articulo($codart, $deposito)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no existe en el depósito seleccionado');
            }
        }

        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['PR_CANTID'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'Cantidad debe ser un número');
            }
        }

        $codigos_monodrogas = [];
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['PR_CODART'];
            if (in_array($codart, $codigos_monodrogas)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no puede repetirse');
            }
            else{
                $codigos_monodrogas[] = $codart;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DE_NRODEVOL' => 'Número Devolución',
            'DE_FECHA' => 'Fecha',
            'DE_HORA' => 'Hora',
            'DE_SERSOL' => 'Servicio solicitante',
            'DE_CODOPE' => 'Personal de Farmacia',
            'DE_ENFERM' => 'Personal de Enfermería',
            'DE_SOBRAN' => 'Indica si fue sobrante de Sala',
            'DE_NUMREMOR' => 'Número del remito original',
            'DE_DEPOSITO' => 'Depósito',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenglones()
    {
        return $this->hasMany(Devolucion_salas_renglones::className(), ['PR_NRODEVOL' => 'DE_NRODEVOL']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemito_original()
    {
        return $this->hasOne(Planilla_entrega::className(), ['PE_NROREM' => 'DE_NUMREMOR']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'DE_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperador()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'DE_CODOPE']);
    }

    
    public function getEnfermero()
    {
        if ($this->DE_ENFERM)
            return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'DE_ENFERM']);
        else
            return '';
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicio()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'DE_SERSOL']);
    }

    public function getArticulosRemito()
    {
       $articulos = ArticGral::find();
       $articulos->join('INNER JOIN', 'pe_reng','pe_reng.PR_CODART=artic_gral.AG_CODIGO AND pe_reng.PR_NROREM ='.$this->DE_NUMREMOR);     

       return $articulos->all();
    }

    public function cantidad_entregada($codart,$deposito,$fecven){

        $query = Planilla_entrega_renglones::find()->where(['PR_CODART' => $codart,
                                                             'PR_DEPOSITO' => $deposito,
                                                             'PR_FECVTO' => $fecven,
                                                             'PR_NROREM' => $this->DE_NUMREMOR]);
        $renglon_entrega = $query->one(); 
        
        $query = Devolucion_salas::find();
        $query->joinWith(['renglones']);
        $query->where(['DE_NUMREMOR'=>$this->DE_NUMREMOR,'PR_CODART' => $codart,'PR_DEPOSITO' => $deposito,'PR_FECVTO' => $fecven]);

        

        $cantidad_devuelta = $query->sum('PR_CANTID');

        return $renglon_entrega->PR_CANTID-$cantidad_devuelta;
       
       

    }

    public static function getListaDeposito()
    {
        $opciones = Deposito::find();//->asArray()->all();
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

    private function existe_articulo($codart,$deposito){

        if (($model = ArticGral::findOne(['AG_CODIGO' => $codart, 'AG_DEPOSITO' => $deposito])) !== null) {
            return true;
        } else {
            return false;
        }
    }
}
