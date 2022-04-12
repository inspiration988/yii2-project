<?php

namespace app\components;

use Yii;
use yii\db\ActiveRecord;

class CookieStorage implements StorageInterface
{
    /**
     * save each attribute value in session
     * @param ActiveRecord $model
     * @param array $data
     * @param int $step
     */
    public static function SaveAsModel(ActiveRecord $model, array $data): void
    {
        $cookies = Yii::$app->response->cookies;
        $model->load($data);

        foreach ($model->getAttributes() as $attr => $val) {
            if (!is_null($val)) {
                $cookies->add(new \yii\web\Cookie([
                    'name' => $attr,
                    'value' => $val,
                ]));
            }
        }

    }

    /**
     * return the value of attribute from session if set
     * @param string $attributeName
     * @return mixed
     */
    public static function getValue(string $attributeName ,mixed $defualtValue = null)
    {
        $cookies = Yii::$app->request->cookies;
        return ($cookies->has($attributeName) ? $cookies->getValue($attributeName) : $defualtValue);
    }

    public static function setValue($attributeName, $value){
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => $attributeName,
            'value' => $value,
        ]));

    }

    /**
     *
     */
    public static function destroy(): void
    {
        // unset cookies
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time()-1000);
                setcookie($name, '', time()-1000, '/');
            }
        }
    }

    /**
     * @param ActiveRecord $model
     * @return mixed
     */
    public static function loadtoModel(ActiveRecord &$model){
        $index = $model::className();
        $cookies = Yii::$app->request->cookies;
        if($cookies->has($index)){
            $model->attributes =  $cookies->getValue($index);
        }
    }

}