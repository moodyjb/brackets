<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BracketsConfiguration */

$this->title = 'Update Brackets Configuration: ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'Brackets Configurations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_id, 'url' => ['view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="brackets-configuration-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
