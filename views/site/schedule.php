<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
?>

<h3>Schedule</h3>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{items}',
    //'enableSorting' => false,

    'formatter' =>['class'=>'yii\i18n\Formatter','nullDisplay' => '',],
    'columns' => [
        ['attribute'=>'group'],
        //'year',
        ['attribute'=>'month'],
        'revised',
        ['header'=>'Delete', 'format'=>'raw', 'value'=>function ($model) {
            return html::a(
                'X',
                Url::to(['site/delete']),
                ['class'=>'btn btn-primary',
                'data'=>['confirm'=>'Really', 'method'=>'post',
                'params'=>['fileName'=>$model['fileName']]]]
            );
        }],

        
    ]
]);
?>
    
    