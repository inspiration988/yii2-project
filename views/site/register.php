<?php

use app\components\SessionStorage;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

?>





    <div id="step_1" class="<?= (Yii::$app->session->get('step') == 1 || is_null(Yii::$app->session->get('step'))) ? "" : "hidden" ?>">
        <?php
        $form = ActiveForm::begin([
            'id' => 'register-step-1',
        ]); ?>

        <?= $form->field($user, 'first_name')->textInput(['value' => Yii::$app->tempStorage::getValue('first_name')]) ?>
        <?= $form->field($user, 'last_name')->textInput(['value' => Yii::$app->tempStorage::getValue('last_name' )]) ?>
        <?= $form->field($user, 'phone')->textInput(['value' => Yii::$app->tempStorage::getValue('phone' )]) ?>

        <?= Html::hiddenInput('step', 1) ?>

        <div class="form-group">
            <div class="offset-lg-1 col-lg-11">
                <?= Html::submitButton('next', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <div id="step_2" class="<?= Yii::$app->session->get('step') == 2 ? "" : "hidden" ?>">
        <?php
        $form = ActiveForm::begin([
            'id' => 'register-step-2',
        ]); ?>

        <?= $form->field($address, 'street')->textInput(['value' => Yii::$app->tempStorage::getValue('street')]) ?>
        <?= $form->field($address, 'house')->textInput(['value' => Yii::$app->tempStorage::getValue('house')]) ?>
        <?= $form->field($address, 'number')->textInput(['value' => Yii::$app->tempStorage::getValue('number')]) ?>
        <?= $form->field($address, 'zipcode')->textInput(['value' => Yii::$app->tempStorage::getValue('zipcode')]) ?>
        <?= $form->field($address, 'province')->dropDownList($provinceList , ['id'=>'pro-id' , ['options'=>[Yii::$app->tempStorage::getValue('province')=>['Selected'=>true]]]]); ?>
        <?= $form->field($address, 'city_id')->widget(DepDrop::classname(), [
            'pluginOptions'=>[
                'depends'=>['pro-id'],
                'placeholder'=>'select city',
                'url'=>Url::to(['/site/city-list'])
            ],
            'options' => [ Yii::$app->tempStorage::getValue('city_id')],
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
    <div id="step_3" class="<?= Yii::$app->session->get('step') == 3 ? "" : "hidden" ?>">
        <?php
        $form = ActiveForm::begin([
            'id' => 'register-step-3',
        ]); ?>

        <?= $form->field($user, 'iban')->textInput(['value' => Yii::$app->tempStorage::getValue('iban')]) ?>

        <?= Html::hiddenInput('step', 3) ?>

        <div class="form-group">
            <div class="offset-lg-1 col-lg-11">
                <?= Html::button('previous', ['class' => 'btn btn-success', 'id' => 'back-step-2']) ?>
                <?= Html::submitButton('next', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <div id="step_4" class="<?= Yii::$app->session->get('step') == 4 ? "" : "hidden" ?>">
        successfull
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

