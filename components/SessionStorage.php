<?php

namespace app\components;

use Yii;
use yii\db\ActiveRecord;

class SessionStorage implements StorageInterface
{
    /**
     * save each attribute value in session
     * @param ActiveRecord $model
     * @param array $data
     * @param int $step
     */
    public static function SaveAsModel(ActiveRecord $model, array $data): void
    {
        $session = \Yii::$app->session;
        $model->load($data);
        $values = [];
        foreach ($model->getAttributes() as $attr => $val) {
            if (!is_null($val)) {
                $values[$attr] = $val;
            }
        }
        $session->set($model::className(), $values);

    }

    /**
     * return the value of attribute from session if set
     * @param string $attributeName
     * @return mixed
     */
    public static function getValue(string $attributeName, mixed  $defualtValue = null)
    {
        return (Yii::$app->session->has($attributeName) ? Yii::$app->session->get($attributeName) : $defualtValue);
    }

    public static function setValue($attributeName, $value){
        $session = \Yii::$app->session;
        $session->set($attributeName, $value);
    }

    public static function destroy(): void
    {
        Yii::$app->session->destroy();
    }

    public static function loadtoModel(ActiveRecord &$model){
        $index = $model::className();
        if(Yii::$app->session->has($index)){
            $model->attributes = Yii::$app->session->get($index);
        }
    }




}