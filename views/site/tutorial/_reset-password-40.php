<?php

use yii\helpers\Html;

use app\assets\TutorialAsset;

TutorialAsset::register($this);

?>

<h3 ><span style='' class="glyphicon glyphicon-book"></span><i class='tutorial_h3'>Tutorial - Reset Password: Step 4</i></h3>
<img src='images/tutorials/resetPassword_40.png'>

<div class='buttons'>

<br>
<?= Html::a('Previous', ['site/tutorial-reset-password','step'=>30], ['class'=>'btn btn-default']) ?>

<?= Html::a('Next', ['site/tutorial-reset-password','step'=>50], ['class'=>'btn btn-default']) ?>
<?= Html::a('Exit', ['site/index'], ['class'=>'btn btn-warning', 'style'=>'margin-left:100px;']) ?>

</div>
