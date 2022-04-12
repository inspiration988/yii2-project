<?php

namespace app\controllers;

use app\components\RequestHttp;
use app\models\Address;
use app\models\City;
use app\models\User;
use Yii;
use yii\web\Controller;


class SiteController extends Controller
{

    protected string $url = "https://37f32cl571.execute-api.eu-central-1.amazonaws.com/default/wunderfleet-recruiting-backend-dev-save-payment-data";

    /**
     * submit user data
     * @return string
     */
    public function actionRegister(): string
    {


        $user = new User();
        $user->scenario = User::SCENARIO_STEP_1;

        $address = new Address();

        // Load Temp storage to model
        Yii::$app->tempStorage::loadtoModel($user);
        Yii::$app->tempStorage::loadtoModel($address);


        $step = 1;
        $message = "";



        if (Yii::$app->request->post('step', false)) {
            $post = Yii::$app->request->post();
            $step = Yii::$app->request->post('step');

            // SET SCENARIO
            if ($step+1 == 3) {
                $user->scenario = User::SCENARIO_STEP_2;
            }


            if ($user->load($post)) {
                $model = $user;
            } elseif ($address->load($post)) {
                $model = $address;
            } else {
                throw new \Exception("Invalid model ");
            }


            if ($step == 3) {
                // Load From TempStorage
                if ($user->save()) {
                    $address->user_id = $user->id;
                    if ($address->save()) {
                        $data = [
                            "customerId" => $user->id,
                            "iban" => $user->iban,
                            "owner" => $user->first_name . " " . $user->last_name
                        ];
                        $curlApi = RequestHttp::post($this->url, $data);
                        $paymentDataId = "";
                        if ($curlApi->getStatusCode() == 200) {
                            $result = json_decode($curlApi->getContent(), true);
                            $paymentDataId = $result['paymentDataId'];
                            $user->payment_data_id = $paymentDataId;
                            $user->save();

                        }

                        Yii::$app->tempStorage::destroy();
                         $this->redirect(['final', 'paymentId' => $paymentDataId]);
                    }
                }

            }

            // if error dosen't occured
            if (empty($user->getErrors()) && empty($address->getErrors())) {
                Yii::$app->tempStorage::SaveAsModel($model, $post);
            }


            $step++;
        }



        return $this->render('register', compact('user', 'address', 'step', 'message'));

    }

    public function actionFinal($paymentId = "")
    {
        Yii::$app->tempStorage::destroy();
        /**
         * for prevent xss injection or other security issue , i can send paymentId by session or use security method such as htmlspecialchars
         */
        if (empty($paymentId)) {
            $message = "unsuccessfull registration";
        } else {
            $message = "sucessfull registration , your paymentDataId is " . htmlspecialchars($paymentId);
        }
        return $this->render('final', ['message' => $message]);
    }

    /**
     *
     * return the list of cities based on proivinced_id
     * @return array|string[]
     */
    public function actionCityList(): array
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $provinceId = $parents[0];
                $list = City::find()->andWhere(['province_id' => $provinceId])->asArray()->all();
                foreach ($list as $i => $pro) {
                    $out[] = ['id' => $pro['id'], 'name' => $pro['name']];

                }
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionIndex(){
        return $this->redirect(['register']);
    }
}
