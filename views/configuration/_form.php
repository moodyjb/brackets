<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BracketsConfiguration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="brackets-configuration-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'yBracketsStart')->textInput() ?>

    <?= $form->field($model, 'yTeamSeparationFactor')->textInput() ?>

    <?= $form->field($model, 'team_w')->textInput() ?>

    <?= $form->field($model, 'team_h')->textInput() ?>

    <?= $form->field($model, 'noTeams')->textInput() ?>

    <?= $form->field($model, 'xTeamSeparationFactor')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
