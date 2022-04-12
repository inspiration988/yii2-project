<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string $name
 * @property int|null $province_id
 *
 * @property Address[] $addresses
 * @property Province $province
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['province_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['province_id'], 'exist', 'skipOnError' => true, 'targetClass' => Province::className(), 'targetAttribute' => ['province_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'province_id' => 'Province ID',
        ];
    }

    /**
     * Gets query for [[Addresses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['city_id' => 'id']);
    }

    /**
     * Gets query for [[Province]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(Province::className(), ['id' => 'province_id']);
    }
}
