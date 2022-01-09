<?php

use yii\helpers\Html;

use app\assets\TutorialAsset;

TutorialAsset::register($this);
?>

<h3 ><span style='' class="glyphicon glyphicon-book"></span><i class='tutorial_h3'>Tutorial - Reset Password: Step 6</i></h3>
<img src='images/tutorials/resetPassword_60.png'>

<div class='buttons'>

<?= Html::a('Previous', ['site/tutorial-reset-password','step'=>50], ['class'=>'btn btn-default']) ?>

<?= Html::a('Next', ['site/tutorial-reset-password','step'=>70], ['class'=>'btn btn-default']) ?>
<?= Html::a('Exit', ['site/index'], ['class'=>'btn btn-warning', 'style'=>'margin-left:100px;']) ?>

</div>
