<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Paciente;

/**
 * PacienteBuscar represents the model behind the search form about `farmacia\models\Paciente`.
 */
class PacienteBuscar extends Paciente
{
    public $nacionalidadDescripcion;
    public $paisDescripcion;
    public $provinciaDescripcion;
    public $partidoDescripcion;
    public $localidadDescripcion;
    public $obraSocialDescripcion;
    public $localidadNacimientoDescripcion;
    public $calleDescripcion;
    public $tipoViviendaDescripcion;
    public $nivelInstruccionDescripcion;
    public $situacionLaboralDescripcion;
    public $ocupacionDescripcion;
    public $artDescripcion;
    public $tipoDocumentoDescripcion;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PA_CODPAR', 'PA_APENOM', 'PA_NOMBRE', 'PA_APELLIDO', 'PA_HISCLI', 'PA_TIPDOC', 'PA_NUMDOC', 'PA_FECNAC', 'PA_NACION', 'PA_SEXO', 'PA_DIREC', 'PA_CODCALL', 'PA_NROCALL', 'PA_BARRIO', 'PA_CUERPO', 'PA_PISO', 'PA_TIPOVIV', 'PA_DPTO', 'PA_CODLOC', 'PA_CODPRO', 'PA_TELEF', 'PA_OBSERV', 'PA_NIVEL', 'PA_VENNIV', 'PA_CODOS', 'PA_NROAFI', 'PA_ADEU', 'PA_ENTDE', 'PA_LOCNAC', 'PA_APEMA', 'PA_UBIC', 'PA_USANIT', 'PA_MEDDER', 'PA_APEMEDD', 'PA_CODPAIS', 'PA_ASOCIAD', 'PA_NIVINST', 'PA_SITLABO', 'PA_OCUPAC', 'PA_APEFA', 'PA_TELFA', 'PA_FOTDOCU', 'PA_FALLEC', 'PA_NOMELEG', 'PA_EMPEMPL', 'PA_EMPDIR', 'PA_CUITEMP', 'PA_EMAIL', 'PA_REGISTRADO', 'PA_USANITSU', 'PA_ART', 'nacionalidadDescripcion', 'paisDescripcion', 'provinciaDescripcion', 'partidoDescripcion', 'localidadDescripcion', 'obraSocialDescripcion', 'localidadNacimientoDescripcion', 'calleDescripcion', 'tipoViviendaDescripcion', 'nivelInstruccionDescripcion', 'situacionLaboralDescripcion', 'ocupacionDescripcion', 'artDescripcion', 'tipoDocumentoDescripcion'], 'safe'],
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
        $query = Paciente::find();

        $pagina = 0;
        if (isset($_POST['pagina']))
            $pagina = $_POST['pagina'];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $pagina,
            ]
        ]);

        if (isset ($_POST['PacienteBuscar'])) {
            $this->load(Yii::$app->request->post());
        }
        else {
            // si no se reciben parámetros, no se retorna ningún resultado
            //$query->where('0=1');
            return $dataProvider;
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'PA_HISCLI' => $this->PA_HISCLI,
            'PA_TIPDOC' => $this->PA_TIPDOC,
            'PA_NUMDOC' => $this->PA_NUMDOC,
        ]);

        // Se busca por cada nombre y cada apellido, por si aparecen en otro orden. Así, encontrará si se busca "Juan Pérez" o "Pérez Juan"
        $nombres = explode(" ", $this->PA_APENOM);
        foreach ($nombres as $nombre) {
            $query->andFilterWhere(['like', 'PA_APENOM', $nombre]);
        }

        return $dataProvider;
    }

    public function buscarSugerencias($params)
    {
        $query = Paciente::find();

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

        $query->andFilterWhere([
            'PA_HISCLI' => $this->PA_HISCLI,
            'PA_TIPDOC' => $this->PA_TIPDOC,
            'PA_NUMDOC' => $this->PA_NUMDOC,
        ]);

        $query->andFilterWhere(['like', 'PA_APENOM', $this->PA_APENOM]);
        $query->andFilterWhere(['PA_CODCALL' => $this->PA_CODCALL]);
        $query->andFilterWhere(['PA_NROCALL' => $this->PA_NROCALL]);
        $query->andFilterWhere(['PA_FECNAC' => $this->PA_FECNAC]);        

        return $dataProvider;
    }
}
