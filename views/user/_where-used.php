<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Where Used";
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',
            'cntctsSeason',
            'plyrsSeason',
            'pyrsSeason',
            'cchsSeason',
            'lastSeason',
            ['class' => 'yii\grid\ActionColumn',
                'template' =>'{delete}',

        ],
        ],
    ]); ?>


</div>
