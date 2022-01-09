<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="events-form">

<h3>Edit this node</h3>
    <?php $form = ActiveForm::begin(); ?>

    <?php if ($models[0]->round > 1) : ?>
        <?= $form->field($models[1], '[1]team')->textInput() ?>
        <?= $form->field($models[1], '[1]score')->textInput() ?>
        <input type="radio" id="home" name="Brackets[0][winner]" value="1" <?= $models[0]['winner']==1 ? 'checked' : ''?>>
        <?= $form->field($models[2], '[2]team')->textInput() ?>
        <?= $form->field($models[2], '[2]score')->textInput() ?>
        <input type="radio" id="visitor" name="Brackets[0][winner]" value="2" <?= $models[0]['winner']==2 ? 'checked' : ''?>>

    <?php else : ?>
        <?= $form->field($models[0], '[0]team')->textInput() ?>
    <?php endif ; ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

