<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use app\assets\SiteAsset;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

SiteAsset::register($this);

yii::$app->view->registerJs('var city = "' . Url::to(['user/city']) . '"');

$this->title = 'Register for a new account';
?>

<style>
.names {
  display: grid;
  grid-template-columns:  150px 190px;
  grid-gap: 10px;
  background-color: #fff;
  color: #444;
}
.streetAddr {
  display: grid;
  grid-template-columns: 250px 120px 120px 80px 80px;
  grid-gap: 10px;
  background-color: #fff;
  color: #444;
}
.eAddress {
  display: grid;
  grid-template-columns:  220px 125px;
  grid-gap: 10px;
  background-color: #fff;
  color: #444;
}
.password {
  display: grid;
  grid-template-columns:  150px 150px;
  grid-gap: 10px;
  background-color: #fff;
  color: #444;
}
.captcha {
  display: grid;
  grid-template-columns:  200px;
  grid-gap: 10px;
  background-color: #fff;
  color: #444;
}
</style>
<div class="site-login">


        <h1><?= $this->title ?></h1>

        <div class="wrapper">
            <div>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <div class='names'>
                    <?= $form->field($model, 'first')->textInput() ?>

                    <?= $form->field($model, 'last')->textInput() ?>
                </div>

                <div class='streetAddr'>
                    <?= $form->field($model, 'street')->textInput() ?>

                    <?= $form->field($model, 'street2')->textInput() ?>



        <?= $form->field($model, 'city', ['enableAjaxValidation' => true])
            ->widget(AutoComplete::class, [
                'options' => ['id' => 'user-city', 'class' => 'form-control'],
                'clientOptions' => [
                    'appendTo' => '#newAccount',
                    'source' => new JsExpression("function(request, response) {

                                $.getJSON(city, {
                                    term: request.term
                                },function(data) {
                                    response(data);
                                    }
                                );
                            }"),
                ],
                'clientEvents' => [

                    'select' => new JsExpression('function (event, ui) {
                            console.log(ui);
                                $("#requestedaccount-state").val(ui.item.state);
                                $("#requestedaccount-zip").val(ui.item.zipCode);

                          }'),
                ],
            ])
        ?>


        <?= $form->field($model, 'state')->dropDownList(
            ['Illinois' => 'Illinois', 'Iowa' => 'Iowa'],
            ['prompt' => '']
        ) ?>


        <?= $form->field($model, 'zip')->dropDownList((new User)->zip(), ['prompt' => '']) ?>
                </div>

                <div class='eAddress'>
                    <?= $form->field($model, 'email', ['enableAjaxValidation' => true])
                    ->textInput(['autofocus' => true])->hint('Your login identity') ?>

                    <?= $form->field($model, 'mobile')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '999-999-9999']) ?>
                </div>

                <div>

                        <div id='email-warning' style='display:none; margin-top: -12px; margin-bottom:12px; padding: 6px; border:solid 1px #000; background-color:yellow'>
                          The above email address already exists in this application. You could have been
                           <ul>
                            <li>Previously registered. If you have forgotten your password, then click
                            <?= Html::a('here to reset it.', ['guest-request-pw-reset']) ?></li>
                            <li>Not registered but<ul>
                            <li>have been a coach.</li>
                            <li>listed as a player's emergency contact.</li>
                            <li>requested by a player to be her team coach.</li>
                            </ul></li>
                          </ul>
                          You may continue registering and after confirming your email, you will review the existing user matches
                          and either accept one as yours or create a new user identity for yourself.
                    </div>

                </div>

                <div class='captcha'>
                    <?= $form->field($model, 'verifyCode')->label('I am NOT a robot code')->widget(Captcha::className(),[
                        'id'=>'hihihi', 'imageOptions'=>['width'=>150],
                    ]) ?>
                </div>

                <br>
                <div class="form-group">
                    <?= Html::submitButton('Register', ['class' => 'btn btn-primary', 'name' => 'login-button', 'id'=>'login-bttn']) ?>
                </div>

            <?php ActiveForm::end(); ?>
            </div>


</div>
</div>
