<?php
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'],
                                ])
                                 ?>

    <?= $form->field($model, 'imageFile')
    ->label('Local file to upload')
    ->fileInput(['errorOptions'=>['encode'=>false, 'class'=>'help-block']]) ?>

    <button>Submit</button>

<?php ActiveForm::end() ?>