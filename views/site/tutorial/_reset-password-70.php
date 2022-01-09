<?php

use yii\helpers\Html;

use app\assets\TutorialAsset;

TutorialAsset::register($this);

?>
<h3 ><span style='' class="glyphicon glyphicon-book"></span><i class='tutorial_h3'>Tutorial - Reset Password: Step 7</i></h3>

<img src='images/tutorials/resetPassword_70.png'>

<div class='buttons'>

<?= Html::a('Beginning', ['site/tutorial-reset-password','step'=>10], ['class'=>'btn btn-default']) ?>

<?= Html::a('Previous', ['site/tutorial-reset-password','step'=>60], ['class'=>'btn btn-default']) ?>
<?= Html::a('Exit', ['site/index'], ['class'=>'btn btn-warning', 'style'=>'margin-left:100px;']) ?>

</div>
