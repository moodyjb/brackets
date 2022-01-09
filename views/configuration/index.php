<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BracketsConfigurationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Brackets Configurations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brackets-configuration-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Brackets Configuration', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'name','format'=>'raw','header'=>'Display<br>Tournament', 'value'=>function ($model) {
                return Html::a($model->name, ['brackets/view'], ['class'=>'btn btn-default']);
            }],
            ['attribute'=>'brackets_id','format'=>'raw','header'=>'Update<br>Configuration', 'value'=>function ($model) {
                return Html::a($model->brackets_id, ['configuration/update','id'=>1], ['class'=>'btn btn-default']);
            }],
            'user_id',
            'yBracketsStart',
            'yTeamSeparationFactor',
            'team_w',
            'team_h',
            'noTeams',
            'xTeamSeparationFactor',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
