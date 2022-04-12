<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $city_id
 * @property string $street
 * @property string $house
 * @property string $number
 * @property string $zipcode
 *
 * @property City $city
 * @property User $user
 */
class Address extends \yii\db\ActiveRecord
{
    public $province;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'city_id' , 'number'], 'integer'],
            [['street', 'house', 'number', 'zipcode' ,'city_id'], 'required'],
            [['street', 'house', 'number' , 'zipcode'], 'string', 'max' => 255],
            ['province'  , 'safe'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'city_id' => 'City ID',
            'street' => 'Street',
            'house' => 'House',
            'number' => 'Number',
            'zipcode' => 'Zipcode',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
