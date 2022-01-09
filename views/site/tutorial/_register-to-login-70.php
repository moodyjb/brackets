<?php

use yii\helpers\Html;

use app\assets\TutorialAsset;

TutorialAsset::register($this);

?>
<h3><span class="glyphicon glyphicon-book"></span><i class='tutorial_h3'>Tutorial - Register to login - Step 7</i></h3>
 <p style='font-size:1.50em;'>
        There may already be an existing account by your spouse. This is a list of users
        with an exact or partial match to name, address, email, or mobile number.
        If you find your family already listed here, then remember the email address,
        click <i style='background-color:yellow;'>Cancel</i>, and login with the remembered email address. If you have forgotten
        the associated password, you may request a password reset.
    </p>
    <p style='font-size:1.50em;'>
        If no existing account matches your family, then at the bottom under 'As Entered ... create a new person',
        click <i style='background-color:yellow;'>Select</i> next to the row of data as you entered it. Then you will enter a password
        for your new account.
    </p>
<img src='images/tutorials/register-to-login_70.png'>

<br><br>
<div class='buttons'>

    <?= Html::a('Previous', ['site/tutorial-register-to-login', 'step' => 60], ['class' => 'btn btn-default']) ?>

    <?= Html::a('Next', ['site/tutorial-register-to-login', 'step' => 80], ['class' => 'btn btn-default']) ?>
    <?= Html::a('Exit', ['site/index'], ['class'=>'btn btn-warning', 'style'=>'margin-left:100px;']) ?>

</div>
