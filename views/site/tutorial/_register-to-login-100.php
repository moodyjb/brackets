<?php

use yii\helpers\Html;

use app\assets\TutorialAsset;

TutorialAsset::register($this);

?>
<h3><span class="glyphicon glyphicon-book"></span><i class='tutorial_h3'>Tutorial - Register to login - Step 10</i></h3>

<p style='font-size:1.50em;'>
This is your family's home page. See other tutorials for registering players.
</p>

<img src='images/tutorials/register-to-login_100.png'>

<br><br>
<div class='buttons'>

    <?= Html::a('Previous', ['site/tutorial-register-to-login', 'step' => 90], ['class' => 'btn btn-default']) ?>

    <?= Html::a('Beginning', ['site/tutorial-register-to-login', 'step' => 10], ['class' => 'btn btn-default']) ?>
    <?= Html::a('Exit', ['site/index'], ['class'=>'btn btn-warning', 'style'=>'margin-left:100px;']) ?>

</div>
