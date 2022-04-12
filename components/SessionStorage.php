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
    public static function Save(ActiveRecord $model, array $data, int $step): void
    {
        $session = \Yii::$app->session;
        $model->load($data);

        foreach ($model->getAttributes() as $attr => $val) {
            if (!is_null($val)) {
                $session->set($attr, $val);
            }
        }

        $session->set('step', $step);

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

    public static function destroy(): void
    {
        Yii::$app->session->destroy();
    }


}