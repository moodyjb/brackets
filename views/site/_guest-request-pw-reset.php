<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
?>
<div class="site-reset-password">
   

            <h3><?= $this->title ?></h3>

            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                
                <div style='font-size:1.25em;'>
                Instructions
                    <ul>
                        <li>Enter your email address.</li>
                        <li>Click 'Submit'.</li>
                        <li>You should immediately receive an confirmation email.</li>
                        <li>In the confirmation email, click the link and then enter your new password.</li>
                    </ul>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
