<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BracketsConfigurationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="brackets-configuration-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'yBracketsStart') ?>

    <?= $form->field($model, 'yTeamSeparationFactor') ?>

    <?= $form->field($model, 'team_w') ?>

    <?= $form->field($model, 'team_h') ?>

    <?php // echo $form->field($model, 'noTeams') ?>

    <?php // echo $form->field($model, 'xTeamSeparationFactor') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
