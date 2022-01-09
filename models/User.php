<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
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
    public $searchName;
    public $relationship;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
    /*
     *
     */
    public function getfullName()
    {
        return "$this->first $this->last";
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $role=yii::$app->user->identity->role;
        $controller = yii::$app->controller->id;
        $action = yii::$app->controller->action->id;

        //yii::error("\n*** rules- role=$role   controller=$controller", __METHOD__);

        $base =
        [
            [['id', 'role', 'token', 'first', 'last', 'birthdate', 'email', 'mobile',
            'street', 'street2', 'city', 'state', 'zip', 'loginAttempts',
            'relationship'], 'safe'],
            ['email', 'email']
        ];

        $searchName =
            [
            ['searchName','required'],
            ['searchName', function ($attribute, $params, $validator) {
                // replace multiple spaces with singles and capitalize
                $correctedSearchName =  preg_replace('# {2,}#', ' ', ucwords(strtolower(trim($this->searchName))));
                if (substr_count($correctedSearchName, " ") != 1) {
                    $this->addError('searchName', 'Error, format is 2 names: First Last');
                } else {
                    list($this->first, $this->last) = explode(" ", $correctedSearchName);
                }
            }]
            ];
        $firstLast = [
            [['first','last'],'required'],
            [['first','last'], function ($attribute, $params, $validator) {
                // replace multiple spaces with singles and capitalize
                $this->$attribute =  preg_replace(
                    '# {2,}#',
                    ' ',
                    ucwords(strtolower(trim($this->$attribute)))
                );
            }],
        ];
        $birthdate = [['birthdate', 'required']];

        $uspsAddress = [[['street', 'city', 'state', 'zip'],'required']];
        $eAddress = [[['mobile', 'email'],'required']];
        $relationship = [['relationship', 'required']];
        $newAccount = [[['street','city','state','zip','mobile','email'], 'required',
                    'when' => function ($model) {
                        return $model->id == -1;
                    },
                    'whenClient' => "function (attribute, value) {
                        return $('#user-id').val() == -1;
                    }"]
        ];


        switch ($controller) {
            case 'coaches':
                if ($this->isNewRecord && in_array($role, ['admin','registrar'])) {
                    //yii::error("\n*** searchName=", print_r($searchName, true));
                    return array_merge($base, $searchName, $eAddress);



                } elseif (in_array($role, ['admin','registrar'])) {
                    return array_merge($base, $firstLast, $eAddress);

                } else {
                    return array_merge($base, $firstLast, $eAddress);
                }
                break;

            case 'contacts':
                if ($this->isNewRecord && in_array($role, ['admin','registrar'])) {
                    return array_merge($base, $searchName, [['mobile','required']], $relationship);

                } else {
                    return array_merge($base, $firstLast, [['mobile','required']], $relationship);
                }
                break;

            case 'players':
                if ($this->isNewRecord && in_array($role, ['admin','registrar'])) {
                    //yii::error("\n*** searchName=", print_r($searchName, true));
                    return array_merge($base, $searchName, $birthdate, $uspsAddress);

                } elseif (in_array($role, ['admin','registrar'])) {
                    return array_merge($base, $firstLast, $uspsAddress);

                } else {
                    return array_merge($base, $firstLast, $uspsAddress, $birthdate);
                }
                break;

            case 'accounts':
            case 'user':
                if ($action == 'lookup') {
                    if ($this->isNewRecord && in_array($role, ['admin','registrar'])) {
                        return array_merge($base, $searchName, $newAccount);
                    } else {
                        return array_merge($base, $firstLast, $uspsAddress);
                    }
                } elseif ($action == 'create') {
                        return array_merge($base, $firstLast, $uspsAddress);

                } elseif ($action == 'update') {
                    return array_merge($base);

                } else {
                    echo "modes/user  validation logic error .... action=$action";
                    exit;
                }
                break;
            case 'teammates':
                if ($action == 'lookup') {
                    if ($_SESSION['otherCoach']==1) {
                        if ($this->isNewRecord && in_array($role, ['admin','registrar'])) {
                            return array_merge($base, $searchName, $uspsAddress, $eAddress);
                        } else {
                            return array_merge($base, $firstLast, $uspsAddress, $eAddress);
                        }
                    } else {
                        if ($this->isNewRecord && in_array($role, ['admin','registrar'])) {
                            return array_merge($base, $searchName, $uspsAddress);
                        } else {
                            return array_merge($base, $firstLast, $uspsAddress);
                        }
                    }
                } else {
                    return array_merge($base, $firstLast, $uspsAddress, $eAddress);
                }
                break;

        }
    }

    /*
    * arrays of zip .. i.e. [zip,zip]
    * return all zip,filtering done by client javascript
    */
    public function zip()
    {
        return ArrayHelper::map(
            (new Query())
            ->from('zipCodes')
            ->orderBy('zipCode')
            ->all(),
            'zipCode',
            'zipCode'
        );
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'loginEnabled' => 'yes']);
    }

    /**
     * {@inheritdoc}
     *  This method is used when you need to authenticate a user
     * by a single secret token (e.g. in a stateless RESTful application).
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
    public static function findIdentityByToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByUsername($email)
    {
        $user = static::findOne(['email' => $email, 'loginEnabled' => 'yes']);
        $_SESSION['payer_id'] = $user->id;
        //return static::findOne(['email' => $email, 'loginEnabled' => 'yes']);
        return $user;
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
            'loginEnabled' => 'yes',
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
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
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     * it returns a key used to verify cookie-based login. The key is stored in the login cookie and
     * will be later compared with the server-side version to make sure the login cookie is valid.
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
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
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state' => 'State',
            'county' => 'County',
            'city' => 'City',
            'street' => 'Street',
            'street2' => 'Apt, Lot',
            'zip' => 'Zip Code',
        ];
    }
}
