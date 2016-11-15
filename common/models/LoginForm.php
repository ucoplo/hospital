<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $LE_NUMLEGA;
    public $password;
    public $rememberMe = true;

    private $_user;

    public function load($request ,$formName='LoginForm'){
        // En la BD Legajos heredada del sistema del hopsital previo a este
        // desarrollo los legajos son completados a 6 dígitos (0s a la izq)
        if (isset($request['LoginForm']))
            $request['LoginForm']['LE_NUMLEGA'] =
            str_pad($request['LoginForm']['LE_NUMLEGA'], 6, "0", STR_PAD_LEFT);
        return parent::load($request,$formName);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['LE_NUMLEGA', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Legajo o Contraseña incorrecta.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            // Esto lo hago para compatibilizar con el metodo de login de Juan:
            $numrand= rand(1000000,9999999);
            $legajo = $this->LE_NUMLEGA;
            $grupo_db = $this->user->grupo;
            $id_usuario = $legajo.$numrand;
            $id_session = hash('sha256',$id_usuario,false);
            //                            $query="INSERT INTO sessions VALUES ('".$id_session."','".$legajo."','".$grupo_db."','".date("Y/m/d H:i:s", strtotime("+30 minutes"))."')";
            $query="INSERT INTO sessions_intranet VALUES ('$id_session','$legajo','$grupo_db','".date("Y/m/d H:i:s", strtotime("+2 hour"))."')";
            //echo "[\"$query\"]";
            
            $connection = \Yii::$app->dbUser;
            
            $result = Yii::$app->dbUser->createCommand()->insert('sessions_intranet', [
                //'name' => 'Sam',
                'id_session' => $id_session,
                'legajo' => $legajo,
                'id_privilegios' => $grupo_db,
                'expiracion' => date("Y/m/d H:i:s", strtotime("+2 hour")),
            ])->execute();

            //si no hubo errores, le doy al usuario la cookie de sesion
            if($result == 1)
                setcookie("id_sessionIntra", $id_session, time()+7200,"/");
            
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);

        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByLegajo($this->LE_NUMLEGA);
        }

        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'LE_NUMLEGA' => 'Legajo',
            'password' => 'Contraseña',
            'rememberMe' => 'Recordarme',
        
        ];
    }
}
