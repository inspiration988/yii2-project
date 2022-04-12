<?php

namespace app\controllers;

use app\models\Address;
use app\models\Province;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['register'],
                'rules' => [
                    [
                        'actions' => ['register'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * submit user data
     * @return string
     */
    public function actionRegister(): string
    {
        if (Yii::$app->tempStorage::getValue('step') == 4) {
            Yii::$app->tempStorage::destroy();
        }

        $user = new User();
        $address = new Address();
        $step = 1;

        $provinceList = ArrayHelper::map(Province::find()->all(), 'id', 'name');
        if (Yii::$app->request->post('step', false)) {


            $post = Yii::$app->request->post();
            if ($user->load($post)) {
                $model = $user;
            } elseif ($address->load($post)) {
                $model = $address;
            } else {
                throw new \Exception("Invalid model ");
            }

            $step = Yii::$app->request->post('step');
            Yii::$app->tempStorage::Save($model, $post, $step + 1);


            if ($step == 3) {
                $user->first_name = Yii::$app->tempStorage::getValue('first_name');
                $user->last_name = Yii::$app->tempStorage::getValue('last_name');
                $user->phone = Yii::$app->tempStorage::getValue('phone');
                $user->created_at = time();
                if ($user->save()) {
                    $address->user_id = $user->id;
                    $address->house = Yii::$app->tempStorage::getValue('house');
                    $address->zipcode = Yii::$app->tempStorage::getValue('zipcode');
                    $address->street = Yii::$app->tempStorage::getValue('street');
                    $address->number = Yii::$app->tempStorage::getValue('number');
                    $address->city_id = Yii::$app->tempStorage::getValue('city_id');
                    if ($address->save()) {

                    }
                }

            }
        }
        if ($step == 1) {
            $user->scenario = User::SCENARIO_STEP_1;
        } elseif ($step == 3) {
            $user->scenario = User::SCENARIO_STEP_2;
        }

        return $this->render('register', compact('user', 'address', 'step', 'provinceList'));

    }


}
