<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BracketsConfiguration */

$this->title = 'Create Brackets Configuration';
$this->params['breadcrumbs'][] = ['label' => 'Brackets Configurations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brackets-configuration-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
