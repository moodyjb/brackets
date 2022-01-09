<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $verifyCode;
    public $rememberMe = true;

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['email', 'password'], 'required'],
            ['email','exist', 'targetClass' => User::class, 'targetAttribute' => 'email','message'=>'Not registered.'],
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
                // failure
                $this->addError($attribute, 'Incorrect email or password.');
                $user->loginAttempts++;
                $user->login_at = date("Y-m-d H:i:s");
                $user->save(false);
            } else {
                //  success
                $user->loginAttempts=0;
                $user->login_at = date("Y-m-d H:i:s");
                $user->save(false);
            }
        }
    }
    public function attemptsLimit()
    {
        $user = $this->getUser();
        
        if (!$user) {
            $this->addError('email', 'Not registered.');
            return false;
        }
        if ($user->loginAttempts > 2 && strtotime($user->login_at)+10 > time()) {
            // After 3 attempts you get Too many with 10 minutes
            // After that you only get 1 attempt per 10 minutes
            $user->login_at = date("Y-m-d H:i:s");
            $user->save(false);
            $this->addError('email', 'Too many attempts within 10 minutes');
            return false;
        }

        return true;
    }
    /**
     * Logs in a user using the provided email and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        $user = $this->getUser();
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 200*24*3600);
        }
        
        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->email);
        }

        return $this->_user;
    }
}
