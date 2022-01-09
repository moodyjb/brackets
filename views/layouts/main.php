<?php
use app\assets\AppAsset;
use app\components\Database;
use app\components\Authorization;
use app\models\Configurations;
use app\models\Season;
use app\widgets\Alert;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
AppAsset::register($this);

/* demo */

Yii::$app->view->registerJs('var csrf = "' . Yii::$app->request->csrfToken. '";', \yii\web\View::POS_HEAD);
Yii::$app->view->registerJs('var unloadUrl = "' . Url::to(["site/unload"]). '";', \yii\web\View::POS_HEAD);
Yii::$app->view->registerJs('var loadUrl = "' . Url::to(["site/load"]). '";', \yii\web\View::POS_HEAD);

/*
* Registrar is authorized by date and time
*/
$registrar = false;
if (isset(yii::$app->user->identity->role) && yii::$app->user->identity->role == 'registrar') {
    $registrar = true; //Authorization::registrar();
}
$admin=false;
if (!yii::$app->user->isGuest && yii::$app->user->identity->role == 'admin') {
    $admin = true;
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <link rel="shortcut icon" type='image/x-icon' href="images/favicon.ico"/>
    <?php $this->head() ?>


</head>
<body>
<?php $this->beginBody() ?>



<div class="wrap">
    <?php
    if (Yii::$app->getRequest()->getUserIP() == '127.0.0.1') {
        $navBarOptions = [
            'class' => 'navbar-light navbar-fixed-top bg-light',
            'style'=>"background-color:#ffaaaa"
        ];
    } else {
        $navBarOptions = [
            'class' => 'navbar-inverse navbar-fixed-top bg-light',
        ];
    }
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => $navBarOptions,
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [


            ['label' => 'My Tournaments', 'visible'=>!yii::$app->user->isGuest, 'url' => ['/configuration/index']],

            ['label' => 'Configure', 'visible'=>!yii::$app->user->isGuest, 'url' => ['/configuration/index']],
            ['label' => 'Generate', 'visible'=>!yii::$app->user->isGuest, 'url' => ['/brackets/dev03']],

            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->email . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>


        <!-- Modal -->
        <div id='modal' class="modal fade " tabindex="-1" role="dialog">
            <div class="modal-dialog " role="document" style='width:30%'>
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" style='display:inline-block'></h3>
                        <button type="button" style='display:inline-block important; vertical-align:top; padding-top:20px; font-size:1.50em;' class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button id='cancel' type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <?php if (yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar() == 'basicDemo') : ?>
        <div style='font-size:24px; color:#ff0000;'><br>Test database for learning. DO NOT register players. All changes ignored.</div>
        <?php endif; ?>

        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
