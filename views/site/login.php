<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Login';
?>

<style>
.wrapper {
  display: grid;
  grid-template-columns:  40%;
  grid-gap: 20px;
  background-color: #fff;
  color: #444;
}
</style>
<div class="site-login">
  

        <h1><?= $this->title ?></h1>

    
        <div class="wrapper">
            <div>
            <h3>Sign in to an existing account</h3>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

           

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div>
                    <h3><?= Html::a('Reset', ['site/guest-request-pw-reset']) ?> your forgotten password</h3>
                </div>
                <div>
                    <h3>Not a user, then click here to <?= Html::a('Register', ['site/guest-request-new-account']) ?></h3>
                </div>
                <br>
                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button', 'id'=>'login-bttn']) ?>
                </div>

            <?php ActiveForm::end(); ?>
            </div>


</div>
</div>
