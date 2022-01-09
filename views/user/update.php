<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Coaches */

$this->title = 'Update User';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('@app/views/user/_formRaw', [
        'model' => $model,
    ]) ?>

</div>
