<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div>
    <p class="text-success">
        <?= $message?>
    </p>

    <?= Html::button(Html::a('Back' , Url::to(['site/register'])) , ['class' => 'btn btn-success'])?>
</div>