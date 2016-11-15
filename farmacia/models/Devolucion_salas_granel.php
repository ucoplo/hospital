<?php

namespace farmacia\models;

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
class Devolucion_salas_granel extends \yii\db\ActiveRecord
{
    public $renglones;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'devoluc';
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
            [['DE_NUMREMOR'], 'exist', 'skipOnError' => true, 'targetClass' => Consumo_medicamentos_granel::className(), 'targetAttribute' => ['DE_NUMREMOR' => 'CM_NROREM']],
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
            $codmon = $renglon['DF_CODMON'];
            $deposito = $this->DE_DEPOSITO;
            if (!$this->existe_articulo($codmon, $deposito)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no existe en el depósito seleccionado');
            }
        }

        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['DF_CANTID'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'Cantidad debe ser un número');
            }

           
        }

        $codigos_monodrogas = [];
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['DF_CODMON'];
            if (in_array($codmon, $codigos_monodrogas)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no puede repetirse');
            }
            else{
                $codigos_monodrogas[] = $codmon;
            }

        }

        //verificar existencia
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['DF_CODMON'];
            $deposito = $this->DE_DEPOSITO;
            $fecven = date('Y-m-d', strtotime(str_replace("/","-",$renglon['DF_FECVTO'])));
            $cantidad_entregada = $this->cantidad_entregada($codmon,$deposito,$fecven);

            if ($cantidad_entregada<$renglon['DF_CANTID']) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, "Máximo a Devolver s/Remito. $cantidad_entregada");
                
            }
        }
    }

     public function validateRenglonesCreateSobrante($attribute, $params)
    {   
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['DF_CODMON'];
            $deposito = $this->DE_DEPOSITO;
            if (!$this->existe_articulo($codmon, $deposito)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no existe en el depósito seleccionado');
            }
        }

        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['DF_CANTID'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'Cantidad debe ser un número');
            }
        }

        $codigos_monodrogas = [];
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['DF_CODMON'];
            if (in_array($codmon, $codigos_monodrogas)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no puede repetirse');
            }
            else{
                $codigos_monodrogas[] = $codmon;
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
        return $this->hasMany(Devolucion_salas_granel_renglones::className(), ['DF_NRODEVOL' => 'DE_NRODEVOL']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemito_original()
    {
        return $this->hasOne(Consumo_medicamentos_granel::className(), ['CM_NROREM' => 'DE_NUMREMOR']);
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

    public function getMonodrogasRemito()
    {
       $monodrogas = ArticGral::find();
       $monodrogas->join('INNER JOIN', 'planfar','planfar.PF_CODMON=artic_gral.AG_CODIGO AND planfar.PF_NROREM ='.$this->DE_NUMREMOR);     

       return $monodrogas->all();
    }

    public function cantidad_entregada($codmon,$deposito,$fecven){

        $query = Consumo_medicamentos_granel_renglones::find()->where(['PF_CODMON' => $codmon,
                                                             'PF_DEPOSITO' => $deposito,
                                                             'PF_FECVTO' => $fecven,
                                                             'PF_NROREM' => $this->DE_NUMREMOR]);
        $renglon_entrega = $query->one(); 
        
        $query = Devolucion_salas_granel::find();
        $query->joinWith(['renglones']);
        $query->where(['DE_NUMREMOR'=>$this->DE_NUMREMOR,'DF_CODMON' => $codmon,'DF_DEPOSITO' => $deposito,'DF_FECVTO' => $fecven]);

        

        $cantidad_devuelta = $query->sum('DF_CANTID');

        return $renglon_entrega->PF_CANTID-$cantidad_devuelta;
       
       

    }

    public static function getListaDeposito()
    {
        $opciones = Deposito::find();//->asArray()->all();
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

    private function existe_articulo($codmon,$deposito){

        if (($model = ArticGral::findOne(['AG_CODIGO' => $codmon, 'AG_DEPOSITO' => $deposito])) !== null) {
            return true;
        } else {
            return false;
        }
    }
}
