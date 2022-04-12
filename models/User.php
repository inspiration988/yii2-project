<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property integer $created_at
 * @property integer $update_at
 * @property string $payment_data_id
 *
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    public $iban;

    const SCENARIO_STEP_1 = 1;
    const SCENARIO_STEP_2 = 2;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name' , 'last_name' , 'phone'] , 'required' , 'on' => self::SCENARIO_STEP_1],
            ['phone', 'match', 'pattern' => '/^09[0-9]/'],
            [['created_at'] , 'integer' ],
            [['first_name' , 'last_name' , 'payment_data_id' , 'iban'] , 'string' ],
            [['payment_data_id' , 'iban'] , 'required' , 'on' => self::SCENARIO_STEP_2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['phone'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['user_id' => 'id']);
    }
}
