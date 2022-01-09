<?php

use app\assets\PayersAsset;
use app\components\Season;
use app\components\AutoFinish;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

PayersAsset::register($this);
if (yii::$app->controller->id == 'players') {
    yii::$app->view->registerJs('var users = "' . Url::to(['user/players']) . '";');
} else {
    yii::$app->view->registerJs('var users = "' . Url::to(['user/all']) . '";');
}
yii::$app->view->registerJs('var street = "' . Url::to(['user/street']) . '";');
yii::$app->view->registerJs('var city = "' . Url::to(['user/city']) . '";');
yii::$app->view->registerJs('var role = "' . yii::$app->user->identity->role . '";');
yii::$app->view->registerJs('var controller = "' . yii::$app->controller->id . '";');
//yii::$app->view->registerJs('var mode = "' . $_SESSION['mode'] . '"');


$maxYear = date("Y", strtotime('-5 year', time()));
$minYear = date("Y", strtotime('-21 year', time()));

?>
<style>
    .identifier {
        display: grid;
        grid-gap: 50px;
        grid-template-columns: 150px 150px 150px;
    }

    .uspsAddr {
        display: grid;
        grid-gap: 50px;

        grid-template-columns: 250px 120px 120px 80px 80px;
    }

    .eAddr {
        display: grid;
        grid-gap: 50px;
        grid-template-columns: 120px 250px;
    }
</style>
<div class="account-form">


    <?php $form = ActiveForm::begin(['id' => 'payers',]); ?>

    <?php if (date("n") > 0 && date("n") < 9 && date("Y") != (new Season)->year()) : ?>
        <div style='color:white; font-size:18px; background-color:red; padding:24px;'>
            <?= (new Season)->year() ?> is not the correct season. Do not proceed until corrected.
        </div>
    <?php endif; ?>

    <?php if (in_array(yii::$app->user->identity->role, ['admin','registrar'])) : ?>
        <?php if ($model->isNewRecord) : ?>
            <?php if (yii::$app->controller->action->id == 'lookup') : ?>
            <div style='font-size:1.25em; background-color:yellow; color:blue; padding:12px;
                                        border:solid 3px blue; box-shadow:6px 6px #cccc00;'>
                <?= $this->render('@app/views/user/_formHdr'.ucfirst(yii::$app->controller->id)) ?>
            </div>
            <?php endif ; ?>
        <?php else : ?>
            <h3>Update a <?= ucfirst(yii::$app->controller->id)." - $model->first $model->last" ?></h3>
        <?php endif; ?>

    <?php else : ?>
        <?php if ($model->isNewRecord) : ?>
            <h3>Create a <?= ucfirst(yii::$app->controller->id) ?></h3>
        <?php else : ?>
            <h3>Update a <?= ucfirst(yii::$app->controller->id)." - $model->first $model->last" ?></h3>
        <?php endif ; ?>
    <?php endif ; ?>

    <?= $form->field($model, 'id')->label(false)->hiddenInput() ?>
    <?= $form->field($model, 'role')->label(false)->hiddenInput() ?>

    <div class='identifier'>

        <?php if (yii::$app->controller->action->id == 'lookup' && $model->isNewRecord && in_array(yii::$app->user->identity->role, ['admin', 'registrar'])) : ?>
            <?= $form->field($model, 'searchName', ['enableAjaxValidation' => true])
                ->widget(
                    AutoComplete::class,
                    [
                        // validation requires the id be 'model-attribte'
                        'options' => ['id' => "user-searchname", 'class' => 'form-control'],
                        'clientOptions' => [
                            'source' => new JsExpression("function(request, response) {
                                console.log('users= '+users);
                                $.getJSON(users, {
                                    term: request.term
                                },function(data) {
                                    response(data);
                                    }
                                );
                            }"),
                        ],
                        'clientEvents' => (new AutoFinish)->searchName()
                    ]
                )->hint('Do not make spelling corrections here') ?>

        <?php else : ?>
            <?= $form->field($model, 'first')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'last')->textInput(['maxlength' => true]) ?>
        <?php endif; ?>

        <?php if (yii::$app->controller->id == 'players') : ?>
            <?= $form->field($model, 'birthdate', ['labelOptions' => ['style' => 'display:block', 'class' => 'control-label']])
                ->widget(
                    \yii\jui\DatePicker::class,
                    [
                        'dateFormat' => 'yyyy-MM-dd',
                        'options' => ['class' => 'form-control'],
                        'clientOptions' => ['changeYear' => true, 'changeMonth' => true, 'maxDate' => "$maxYear-12-31", 'minDate' => "$minYear-01-01", 'yearRange' => "$minYear:$maxYear"],
                    ]
                )
            ?>
        <?php endif; ?>

        <?php if (yii::$app->controller->id == 'contacts') : ?>
            <?= $form->field($model, 'relationship')->dropDownList([
                'mother'=>'Mother',
                'father'=>'Father',
                'stepMother'=>'Stepmother',
                'stepFather'=>'Stepfather',
                'grandMother'=>'Grandmother',
                'grandFather'=>'Grandfather',
                'aunt'=>'Aunt',
                'uncle'=>'Uncle',
                'sister'=>'Sister',
                'brother'=>'Brother',
                'self'=>'Self',
                'friend'=>'Friend',
                'volunteer'=>'Head coach volunteer',
            ], ['prompt'=>'']) ?>
        <?php endif ; ?>

    </div>

    <?php if (yii::$app->controller->id != 'contacts') : ?>

        <div class='uspsAddr'>
            <?= $form->field($model, 'street', ['enableAjaxValidation' => true])
                ->widget(AutoComplete::class, [
                    'options' => ['id' => 'user-street', 'class' => 'form-control'],
                    'clientOptions' => [
                        'appendTo' => '#newAccount',
                        'source' => new JsExpression("function(request, response) {
                                    $.getJSON(street, {
                                        term: request.term
                                    }, response);
                                }"),
                    ],
                    'clientEvents' => [
                        'select' => new JsExpression('function (event, ui) {
                                console.log(ui);
                                    $("#user-street2").val(ui.item.street2);
                                    $("#user-city").val(ui.item.city);
                                    $("#user-state").val(ui.item.state);
                                    $("#user-zip").val(ui.item.zip);
                            }'),
                    ],
                ])
            ?>

            <?= $form->field($model, 'street2')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'city', ['enableAjaxValidation' => true])
                ->widget(AutoComplete::class, [
                    'options' => ['id' => 'user-city', 'class' => 'form-control'],
                    'clientOptions' => [
                        'appendTo' => '#newAccount',
                        'source' => new JsExpression("function(request, response) {
                            $.getJSON(city, {
                                term: request.term
                            },function(data) {
                                response(data);
                                }
                            );
                        }")
                    ],
                    'clientEvents' => [
                        'select' => new JsExpression('function (event, ui) {
                                    $("#user-state").val(ui.item.state);
                                    $("#user-zip").val(ui.item.zipCode);
                            }'),
                    ],
                ])
            ?>

            <?= $form->field($model, 'state')->dropDownList(
                ['Illinois' => 'Illinois', 'Iowa' => 'Iowa'],
                ['prompt' => '']
            ) ?>


            <?= $form->field($model, 'zip')->dropDownList($model::zip(), ['prompt' => '']) ?>

        </div>
    <?php endif ; ?>

    <div class='eAddr'>
        <?= $form->field($model, 'mobile', ['enableAjaxValidation' => true])->widget(\yii\widgets\MaskedInput::class, [
            'mask' => '999-999-9999'
        ]) ?>

        <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput() ?>
    </div>

    <div id='checkDupAlert' style='display:none; font-size:1.25em; background-color:yellow; color:blue; padding:12px;border:solid 3px blue; box-shadow:6px 6px #cccc00;'>
        <?= $this->render('@app/views/user/_formFtr'.ucfirst(yii::$app->controller->id)) ?>
    </div>

    <br>

    <div class="form-group">
        <?= Html::submitButton('Next', ['class' => 'btn btn-primary','name'=>'submitButton']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
