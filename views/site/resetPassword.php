<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
?>
<div class="site-reset-password">
    <h3>Change password</h3>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>


                <?php if (yii::$app->user->identity->role == 'admin') : ?>
                    <?= $form->field($model, 'user')->dropDownList(
                        $userDropDownList,
                        ['prompt'=>'','class'=>'form-control',
                        'onchange'=>'if ( !$(this).val()) return false; $("#user").submit();',
                        ]
                    ) ?>
                <?php else : ?>
                    <?php $model->user = yii::$app->user->id ?>
                    <?= $form->field($model, 'user')->label(false)->hiddenInput() ?>

                    <?= Html::label('Username')?>
                    <div style='border:solid 1px #000;' class='form-control'>
                        <?= yii::$app->user->identity->email ?>
                    </div>
                    <br>
                <?php endif; ?>

                <?php if (yii::$app->user->identity->role != 'admin') : ?>
                    <?= $form->field($model, 'currentPassword')->passwordInput(['autofocus' => true]) ?>
                <?php endif; ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>


                <?= $form->field($model, 'password_repeat')->passwordInput(['autofocus' => true]) ?>



                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
