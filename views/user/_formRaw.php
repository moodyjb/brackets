<?php

use app\assets\PayersAsset;
use app\components\Season;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

PayersAsset::register($this);
if (yii::$app->controller->id == 'players') {
    yii::$app->view->registerJs('var users = "' . Url::to(['user/players']) . '"');
} else {
    yii::$app->view->registerJs('var users = "' . Url::to(['user/all']) . '"');
}
yii::$app->view->registerJs('var street = "' . Url::to(['user/street']) . '"');
yii::$app->view->registerJs('var city = "' . Url::to(['user/city']) . '"');
yii::$app->view->registerJs('var role = "' . yii::$app->user->identity->role . '"');
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


    <?= $form->field($model, 'id')->label(false)->hiddenInput() ?>
    <?= $form->field($model, 'role')->label(false)->hiddenInput() ?>

    <div class='identifier'>
            <?= $form->field($model, 'first')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'last')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'birthdate', ['labelOptions' => ['style' => 'display:block', 'class' => 'control-label']])
                ->widget(
                    \yii\jui\DatePicker::class,
                    [
                        'dateFormat' => 'yyyy-MM-dd',
                        'options' => ['class' => 'form-control'],
                        'clientOptions' => ['changeYear' => true, 'changeMonth' => true, 'maxDate' => "$maxYear-12-31", 'minDate' => "$minYear-01-01", 'yearRange' => "$minYear:$maxYear"],
                    ]
                ) ?>
    </div>


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
    <div class='eAddr'>
        <?= $form->field($model, 'mobile', ['enableAjaxValidation' => true])->widget(\yii\widgets\MaskedInput::class, [
            'mask' => '999-999-9999'
        ]) ?>


        <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput() ?>

    </div>
    <br>

    <div class="form-group">
        <?= Html::submitButton('Next', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
