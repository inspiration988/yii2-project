<?php


namespace app\components;


use yii\db\ActiveRecord;

interface StorageInterface
{

    /**
     * @param ActiveRecord $model
     * @param array $data
     * @param int $step
     */
    public static function save(ActiveRecord $model, array $data, int $step): void;

    /**
     * @param string $attributeName
     * @param mixed $defualtValue
     * @return mixed
     */
    public static function getValue(string $attributeName ,mixed $defualtValue = null);


    /**
     *
     */
    public static function destroy(): void ;





}