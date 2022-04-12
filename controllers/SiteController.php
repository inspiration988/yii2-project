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
                        $result = $this->sendPaymentData($user->id, $user->iban, $user->first_name . " " . $user->last_name);
                        var_dump($result);die;
                        if ($result) {
                            $user->payment_data_id = json_decode($result, 1);
                            $user->save();
                        }
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


    /**
     * @param integer $customerId
     * @param string $iban
     * @param string $fullName
     * @return string
     */
    private function sendPaymentData(int $customerId, string $iban, string $fullName): string
    {
        $response = [];
        $data = [
            "customerId" => $customerId,
            "iban" => $iban,
            "owner" => $fullName
        ];
        $url = "http://37f32cl571.execute-api.eu-central-1.amazonaws.com/default/wunderfleet-recruiting-backend-dev-save-payment-data";
        $ch = curl_init($url);
        $payload = json_encode(array("data" => $data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        var_dump(curl_error($ch));die;
//        if ($result === false) {
//            return false;
//        }
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        var_dump($httpcode);die;
        $response = [
            'httpCode' => $httpcode,
            'result' => $result
        ];
        curl_close($ch);
        return $response;
    }

}
