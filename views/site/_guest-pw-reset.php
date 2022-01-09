<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = $title;
?>
<div class="site-reset-password">


            <h3><?= $this->title ?></h3>

            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password', ['enableAjaxValidation' => true])->passwordInput(['autofocus' => true])
                    ->hint('minimum of 6 and maximum of 18 characters') ?>


                <?= $form->field($model, 'password_repeat', ['enableAjaxValidation' => true])->passwordInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
