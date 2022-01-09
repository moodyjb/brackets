<?php

use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="editable-text-form">

    <?php $form = ActiveForm::begin(); ?>

  




<?= $form->field($model, 'content')->widget(CKEditor::className(),[
    'preset' => 'full',

]) ?>

    <div class="form-group">
        <?= Html::submitButton('Update', ['class' =>  'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>