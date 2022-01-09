<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
$js = <<<EOD
	$(".field-contactform-name").hide();
	$(".field-contactform-email").hide();
	$(".field-contactform-subject").hide();
	$(".field-contactform-body").hide();
    $("label[for=contactform-r3gldwca]").text('Name');
    $("label[for=contactform-dttbd6xi]").text('Email');
    $("label[for=contactform-wf1b1apb]").text('Subject');
    $("label[for=contactform-l7amao75]").text('Body');
EOD;

$this->registerJs($js, View::POS_READY);

?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
    </p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'name')->textInput(['autocomplete'=>'off']) ?>
                <?= $form->field($model, 'email')->textInput(['autocomplete'=>'off']) ?>
                <?= $form->field($model, 'subject')->textInput(['autocomplete'=>'off']) ?>
                <?= $form->field($model, 'body')->textarea(['rows' => 6,'autocomplete'=>'off']) ?>



                <?= $form->field($model, 'R3GldWCA')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'dtTBD6Xi') ?>

                <?= $form->field($model, 'Wf1B1apb') ?>

                <?= $form->field($model, 'l7amAO75')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
