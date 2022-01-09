<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RequestedCoaches */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="requested-coaches-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if (Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar() !== 'basicDemo') : ?> 
        <div>Currently using the <b>Production</b> database<div>
        <br>
        <?= Html::a('Change to test', ['site/switch','url'=>'indexDemo.php'], ['class' => 'btn btn-success']) ?>
      
    <?php else : ?> 
        <div>Currently using the <b>Test</b> database for learning ... registrations ignored.<div> 
        <br>
        <?= Html::a('Change to production', ['site/switch','url'=>'index.php'], ['class' => 'btn btn-success']) ?>

    <?php endif; ?>


   
    <?= Html::a("Cancel", ['site/index'], ['class' => 'btn btn-warning']) ?>
    

    <?php ActiveForm::end(); ?>

</div>
