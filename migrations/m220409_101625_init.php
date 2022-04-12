<?php

use yii\db\Migration;

/**
 * Class m220409_101625_init
 */

class m220409_101625_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        //user table
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'payment_data_id' => $this->string(255),
            'phone' => $this->char(11)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull()->unsigned(),
            'updated_at' => $this->integer()->unsigned(),
        ], $tableOptions);




        //address table
        $this->createTable('{{%address}}', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'user_id' => $this->integer(10)->unsigned(),
            'city_id' => $this->integer(10)->unsigned(),
            'street' => $this->string(255)->notNull(),
            'house' => $this->string(255)->notNull(),
            'number' => $this->string(255)->notNull(),
            'zipcode' => $this->string(20)->notNull(),
        ], $tableOptions);


        //province table
        $this->createTable('{{%province}}', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
        ], $tableOptions);


        // city table
        $this->createTable('{{%city}}', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
            'province_id' => $this->integer(10)->unsigned(),
        ], $tableOptions);


        $this->createIndex(
            'idx-address-city_id',
            'address',
            'city_id'
        );
        $this->addForeignKey(
            'fk-address-city_id',
            'address',
            'city_id',
            'city',
            'id'
        );


        $this->createIndex(
            'idx-address-user_id',
            'address',
            'user_id'
        );
        $this->addForeignKey(
            'fk-address-user_id',
            'address',
            'user_id',
            'user',
            'id'
        );

        $this->createIndex(
            'idx-city-province_id',
            'city',
            'province_id'
        );
        $this->addForeignKey(
            'fk-city-province_id',
            'city',
            'province_id',
            'province',
            'id'
        );

    }

}


