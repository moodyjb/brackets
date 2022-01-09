<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'loginEnabled') ?>

    <?= $form->field($model, 'role') ?>

    <?= $form->field($model, 'first') ?>

    <?= $form->field($model, 'last') ?>

    <?php // echo $form->field($model, 'street') ?>

    <?php // echo $form->field($model, 'street2') ?>

    <?php // echo $form->field($model, 'zip') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'birthdate') ?>

    <?php // echo $form->field($model, 'auth_key') ?>

    <?php // echo $form->field($model, 'access_token') ?>

    <?php // echo $form->field($model, 'password_hash') ?>

    <?php // echo $form->field($model, 'password_reset_token') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'token') ?>

    <?php // echo $form->field($model, 'login_at') ?>

    <?php // echo $form->field($model, 'logout_at') ?>

    <?php // echo $form->field($model, 'streetNumbers') ?>

    <?php // echo $form->field($model, 'loginAttempts') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
