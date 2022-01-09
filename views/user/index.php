<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "All Users";
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            //'loginEnabled',
            //'role',
            'first',
            'last',
            'street',
            'street2',
            'zip',
            'email:email',
            'mobile',
            'birthdate',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update}',
            'buttons' =>
                ['update' => function ($url, $model) {
                    $mode = 'user';
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil"></span>',
                        Url::to(['user/update']),
                        ['title' => Yii::t('app', 'View registration'),
                         'data' => [
                             'method' => 'post',
                             'params' => ['id'=>$model['id']]
                         ]

                        ]
                    );
                },

                ],
            ],
        ],
    ]); ?>


</div>
