<?php

use yii\helpers\Html;

use app\assets\TutorialAsset;

TutorialAsset::register($this);

?>
<h3><span class="glyphicon glyphicon-book"></span><i class='tutorial_h3'>Tutorial - Register to login - Step 8</i></h3>
<p style='font-size:1.50em;'>
   On the previous screen, if no existing account matched your family, then at the bottom under
   'As Entered ... create a new person', you clicked <i style='background-color:yellow;'>Select</i> next to the row of data as you entered it.
</p>
<p style='font-size:1.50em;'>
    Thus you are creating a new account and now you need to supply a password.
</p>
<img src='images/tutorials/register-to-login_80.png'>

<br><br>
<div class='buttons'>

    <?= Html::a('Previous', ['site/tutorial-register-to-login', 'step' => 70], ['class' => 'btn btn-default']) ?>

    <?= Html::a('Next', ['site/tutorial-register-to-login', 'step' => 90], ['class' => 'btn btn-default']) ?>
    <?= Html::a('Exit', ['site/index'], ['class'=>'btn btn-warning', 'style'=>'margin-left:100px;']) ?>

</div>
