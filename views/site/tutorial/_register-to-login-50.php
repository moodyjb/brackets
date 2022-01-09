<?php

use yii\helpers\Html;

use app\assets\TutorialAsset;

TutorialAsset::register($this);

?>
<h3><span class="glyphicon glyphicon-book"></span><i class='tutorial_h3'>Tutorial - Register to login - Step 5</i></h3>

<img src='images/tutorials/register-to-login_50.png'>

<br><br>
<div class='buttons'>

    <?= Html::a('Previous', ['site/tutorial-register-to-login', 'step' => 40], ['class' => 'btn btn-default']) ?>

    <?= Html::a('Next', ['site/tutorial-register-to-login', 'step' => 60], ['class' => 'btn btn-default']) ?>
    <?= Html::a('Exit', ['site/index'], ['class'=>'btn btn-warning', 'style'=>'margin-left:100px;']) ?>

</div>
