<?php

use app\models\Address;
use app\models\Province;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $user User */
/* @var $address Address */

$provinceList = ArrayHelper::map(Province::find()->all(), 'id', 'name');
?>

    <div id="step_1" class="<?= $step == 1 ? "" : "hidden" ?>">
        <?php
        $form = ActiveForm::begin([
            'id' => 'register-step-1',
        ]); ?>

        <?= $form->field($user, 'first_name')->textInput() ?>
        <?= $form->field($user, 'last_name')->textInput() ?>
        <?= $form->field($user, 'phone')->textInput() ?>

        <?= Html::hiddenInput('step', 1) ?>

        <div class="form-group">
            <div class="offset-lg-1 col-lg-11">
                <?= Html::submitButton('next', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <div id="step_2" class="<?= $step == 2 ? "" : "hidden" ?>">
        <?php
        $form = ActiveForm::begin([
            'id' => 'register-step-2',
        ]); ?>

        <?= $form->field($address, 'street')->textInput() ?>
        <?= $form->field($address, 'house')->textInput() ?>
        <?= $form->field($address, 'number')->textInput() ?>
        <?= $form->field($address, 'zipcode')->textInput() ?>
        <?= $form->field($address, 'province')->dropDownList($provinceList , ['id'=>'pro-id' ]); ?>
        <?= $form->field($address, 'city_id')->widget(DepDrop::classname(), [
            'pluginOptions'=>[
                'depends'=>['pro-id'],
                'placeholder'=>'select city',
                'url'=>Url::to(['/site/city-list'])
            ],
        ]); ?>

        <?= Html::hiddenInput('step', 2) ?>

        <div class="form-group">
            <div class="offset-lg-1 col-lg-11">
                <?= Html::button('previous', ['class' => 'btn btn-success', 'id' => 'back-step-1']) ?>
                <?= Html::submitButton('next', ['class' => 'btn btn-primary']) ?>

            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <div id="step_3"  class="<?= $step == 3 ? "" : "hidden" ?>">
        <?php
        $form = ActiveForm::begin([
            'id' => 'register-step-3',
        ]); ?>

        <?= $form->field($user, 'iban')->textInput() ?>

        <?= Html::hiddenInput('step', 3) ?>

        <div class="form-group">
            <div class="offset-lg-1 col-lg-11">
                <?= Html::button('previous', ['class' => 'btn btn-success', 'id' => 'back-step-2']) ?>
                <?= Html::submitButton('next', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>


<?php
$js = <<< JS
$('body').on("click" , "#back-step-1" , function (){
    $("#step_2").addClass("hidden");
    $("#step_1").removeClass("hidden");
});

$('body').on("click" , "#back-step-2" , function (){
    $("#step_3").addClass("hidden");
    $("#step_2").removeClass("hidden");
});

JS;
$this->registerJs($js);

