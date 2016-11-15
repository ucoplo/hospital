<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $LE_NUMLEGA
 * @property string $username // LE_NUMLEGA
 * @property string $password
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $LE_ACTIVO
 * @property string $password write-only password
 *
 * ORIGINAL:
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 'F';
    const STATUS_ACTIVE = 'T';
    // const STATUS_DELETED = 0;
    // const STATUS_ACTIVE = 10;

    public static function getDb()
    {
        return Yii::$app->dbUser;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'legajos';
        //return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['LE_ACTIVO', 'default', 'value' => self::STATUS_ACTIVE],
            ['LE_ACTIVO', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($LE_NUMLEGA)
    {
        return static::findOne(['LE_NUMLEGA' => $LE_NUMLEGA, 'LE_ACTIVO' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by LE_NUMLEGA
     *
     * @param string $LE_NUMLEGA
     * @return static|null
     */
    /*public static function findByUsername($LE_NUMLEGA)
    {
        return static::findOne(['LE_NUMLEGA' => $LE_NUMLEGA,
                                'LE_ACTIVO' => self::STATUS_ACTIVE]);
    }*/

    public static function findByLegajo($LE_NUMLEGA)
    {
        return static::findOne(['LE_NUMLEGA' => $LE_NUMLEGA,
                                'LE_ACTIVO' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'LE_ACTIVO' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        //return Yii::$app->security->validatePassword($password, $this->password);
        // Esto lo modifiqué porque en el sistema del hospital que heredamos 
        // utilizan el prefijo xyz y postfijo 123 para enviar las claves.
        // Por ende todas las claves están almacenadas con el hash modificado
        // con ese criterio.
        $passXyz123 = hash('sha256', 'xyz'.$password.'123', false);
        $passwd_BE = $passXyz123.$this->salt;
        $passHash = hash('sha256', $passwd_BE, false);
        return ($passHash === $this->password);

   }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function habilitado($action) {
        // controller codigo    = $action->controller->codigo = '222'
        // controller id        = $action->controller->id = 'medico'
        // action id            = $action->id = 'update'
        //echo getcwd ();exit;
        include "../../validacion_intranet.php";
        $check=tiene_permiso(
            $this->LE_NUMLEGA,
            $this->grupo,
            Yii::$app->params['CodModulo'].
            $action->controller->CodController.
            $action->id);
        return $check;
    }
}
