<?php
namespace app\controllers;

use app\components\SendMessage;
use app\models\Calendar;
use app\models\Coach;
use app\models\ContactForm;
use app\models\Directives;
use app\models\EditableContentForm;
use app\models\LoginForm;
use app\models\Messages;
use app\models\RequestedAccount;
use app\models\ResetPasswordForm;
use app\models\GuestRequestPwResetForm;
use app\models\GuestPwResetForm;
use app\models\UploadScheduleForm;
use app\models\SwitchDbForm;
use app\models\User;
use app\models\NewAccountUser;
use Exception;
use PHPUnit\Framework\MockObject\Stub\ReturnArgument;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\data\Sort;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\base\Security;
use yii\httpclient\Client;


/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    // public function behaviors()
    // {
    //     return [
    //         'access' => [
    //             'class' => AccessControl::class,
    //             'ruleConfig' => ['class' => 'app\components\AccessRule'],
    //             'rules' => [
    //                 [
    //                     'allow' => true,
    //                     'roles' => ['admin'],
    //                 ],
    //                 [
    //                     'allow' => true,
    //                     'actions' => ['login', 'guest-request-new-account', 'guest-new-account',
    //                                 'guest-pw-reset', 'guest-request-pw-reset','error','new-account','check-email'],
    //                     'roles' => ['?','@'],
    //                 ],
    //                 [
    //                     'actions' => ['unload', 'load', 'index',  'contact', 'captcha'],
    //                     'allow' => true,
    //                     'roles' => ['?','@'],
    //                 ],
    //                 [
    //                     'actions' => ['switch','logout', 'edit', 'reset-password'],
    //                     'allow' => true,
    //                     'roles' => ['@'],
    //                 ],
    //             ],
    //         ],
    //         'verbs' => [
    //             'class' => VerbFilter::class,
    //             'actions' => [
    //                 'logout' => ['post'],
    //             ],
    //         ],
    //     ];
    // }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    public function actionError()
    {
        echo "<br>*** site error";
    }
    /*
     * Invoked on each javascript unload event
     * .. used to track when a user logs in and out. Each unload sets logout_at = time()
     */
    public function actionUnload()
    {
        User::updateAll(['logout_at' => date("Y-m-d H:i:s")], ['id' => yii::$app->user->id]);
    }
    /*
     * Invoked on javascript load events. Each load nulls the logout time.
     */
    public function actionLoad()
    {
        User::updateAll(['logout_at' => null], ['id' => yii::$app->user->id]);
    }

    /*
    *   AJAX ... check for duplicate email
    *   Issue just a warning ... does not prevent form submition as a model error would
    */
    public function actionCheckEmail()
    {
        //$_POST['email'] = 'burnett.moody+72@gmail.com';
        $dup = User::find()->where(['email'=>$_POST['email']])->one();
        if ($dup) {
            return "dup";
        } else {
            return "ok";
        }
    }
    // reference: https://www.html5rocks.com/en/tutorials/eventsource/basics/#toc-js-api
    // reference: https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    /**
     *
     */
    public function actionEdit()
    {
        $model = new EditableContentForm();
        $myfile = fopen("../views/site/editableContent.txt", "r") or die("Unable to open file!");
        $model->content = urldecode(fread($myfile, filesize("../views/site/editableContent.txt")));
        fclose($myfile);

        if ($model->load(Yii::$app->request->post())) {
            $myfile = fopen("../views/site/editableContent.txt", "w") or die("Unable to open file!");
            if (fwrite($myfile, urlencode($model->content)) === false) {
                echo "Can not write to file";
                exit;
            }

            fclose($myfile);

            return $this->redirect(['site/index']);
        }

        return $this->render('edit', ['model' => $model]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            $_SESSION['payer_id'] = yii::$app->user->id;
            return $this->goHome();
        }


        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->attemptsLimit() && $model->validate()) {
            /*
             * Successful login ... remember me set for 200 days
             */
            if ($model->login()) {
                // when parent logins; their id set in session
                $_SESSION['payer_id'] = yii::$app->user->id;
                return $this->redirect(['family/summary']);
            }
        }

        return $this->render('login', [
            'model' => $model,
            'ip' => Yii::$app->getRequest()->getUserIP(),
        ]);
    }
    /*
    *   Not used ... moved to family/summary
    *   Confirmation / Review link to access site
    *   Link contained from messages or confirmation
    */
    public function actionZZZConfirmation()
    {
        $user = User::findOne($_SESSION['payer_id']);

        // Generate a token; do not reuse a token, regenerate identifing token with a time stamp and append to the validaion link
        $i = 0;
        do {
            $user->token = Yii::$app->getSecurity()->generateRandomString(16) . '_' . time();
            $i++;
        } while ($i < 5 && User::find()->where(['token' => $user->token])->one());
        $user->save(false);

        $link = Html::a(
            'here',
            Yii::$app->urlManager->createAbsoluteUrl(
                ['site/registration-review','token' => $user->token]));

        try {
            $expired = function () {
                return date("D H:i M d", time()+24*60*60);
            };
            if (! \Yii::$app->mailer
                    ->compose()
                    ->setFrom('burnett.moody@gmail.com')
                    ->setTo('burnett.moody@gmail.com')
                    ->setSubject("Milan Sofball Registration Review")
                    ->setHtmlBody(<<<EOD
                        Click $link to review your registration. This link may be only once and is
                        valid until {$expired()}.
EOD
                    )
                    ->send()) {
                // Failed
                yii::$app->session->setFlash('danger', 'Reset message failed');
            } else {
                yii::$app->session->setFlash(
                    'success',
                    <<<EOD
                Check your email for a registration review message. Click the link in it and review your registration'
EOD
                );
            }
            return $this->redirect(['family/summary']);

        } catch (\Exception $e) {
            yii::$app->session->setFlash('danger', 'Email failed; application error. Try again tomorrow.');
            yii::error("email exception - " . $e->getMessage(), __METHOD__);
            return $this->redirect(['family/summary']);
        }
    }
    /*
    * NOT used moved to family/review
    * Registration review login
    */
    public function actionZZZRegistrationReview($token)
    {
        //$user = User::find()->where(['token'=>$token])->one();
        $user = User::findIdentityByToken($token);

        if (!$user) {
            return $this->render('_error', ['message'=>'Invalid or previously used  link.']);
        }
        // the token may have '_" in the random string; so take the last one with array_pop
        $segments = explode("_", $token);
        $time = array_pop($segments);

        if (time() > (int) $time + 24*60*60) {
            return $this->render('_error', ['message'=>'Your review registration has expired.']);
        }
        // login the user
        Yii::$app->user->login($user, 200*24*3600);
        return $this->redirect(['family/summary']);
    }
    /**
     * Register for a new account.
     */
    public function actionGuestRequestNewAccount()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RequestedAccount();
        /*
        *   Client ajax validation
        */
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        // Generate a token; do not reuse a token, regenerate identifing token with a time stamp and append to the validaion link
        $i = 0;
        do {
            $model->password_reset_token = Yii::$app->getSecurity()->generateRandomString(16) . '_' . time();
            $i++;
        } while ($i < 5 && RequestedAccount::find()->where(['password_reset_token' => $model->password_reset_token])->one());

        $model->created_at = date("Y-m-d H:i:s");

        if ($model->load(Yii::$app->request->post())  && $model->save(false)) {

            $link = Html::a(
                'here',
                Yii::$app->urlManager->createAbsoluteUrl(
                    ['site/guest-new-account',
                    'token' => $model->password_reset_token
                    ]
                )
            );

            $expired = function () {
                return date("H:i", time()+3600);
            };
            $message = new Messages();
            $message->subject = 'Milansofball New Account';
            $message->content = <<<EOD
                        Click $link to create your new account. This link may be only once and is
                        valid for 60 minutes until {$expired()} today.
EOD;
            $rc  =(new SendMessage)->email($message, $model->email);

            if (!$rc['success']) {
                // Email Failed #!##@%
                yii::$app->session->setFlash('danger', 'Reset message failed');

            } else {
                // Email successful
                yii::$app->session->setFlash(
                    'success',
                    <<<EOD
                Check your email for a confirmation message. Click the link in it and complete your registration
EOD
                );
            }
            return $this->render('new-account-initiated');

        } else {
            return $this->render('_requested-account', [
                'model' => $model,
            ]);
        }
    }
    /**
     * via email link; add passwords to complete the new account
     */
    public function actionGuestNewAccount($token)
    {
        $requestedAccount = RequestedAccount::find()->where(['password_reset_token'=>$token])->one();
        if (!$requestedAccount) {
            return $this->render('_error', ['message'=>'Invalid or previously used  link.']);
        }
        // the token may have '_" in the random string
        $segments = explode("_", $token);
        $time = array_pop($segments);

        // FIXME ... time window
        if (time() > (int) $time + 13600) {
            return $this->render('_error', ['message'=>'Your new account link has expired.']);
        }

        $_SESSION['User'] = $requestedAccount->attributes;
        $_SESSION['mode'] = 'payer';
        $_SESSION['payer_id'] = -999;   /* user/before action needs anon-nulll value */
        $_SESSION['callback'] = 'site/new-account';


        return $this->redirect(['user/check-duplicates']);

    }
    /*
    * Callback from user/create and check_duplicates
    */
    public function actionNewAccount()
    {
        $user_id = $_SESSION[$_SESSION['mode'].'_id'];
        $user = User::findOne($user_id);
        if (!$user) {
            echo "No User";
            exit;
        }

        $model = new GuestPwResetForm();

        /*
        *   Client ajax validation
        */
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $user->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            $user->password_reset_token = null;
            $user->auth_key = Yii::$app->getSecurity()->generateRandomString(32);
            $user->loginEnabled = 'yes';
            $user->streetNumbers = preg_replace('/[^0-9]/', '', $user->street);
            $user->save(false);
            yii::$app->session->setFlash('success', 'Your account has been created');
            return $this->redirect(['login']);
        }
        // used to compare addresses .... only compares the integers.

        return $this->render('_guest-pw-reset', ['model'=>$model,'title'=>'Create a password for your new account']);

    }
    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
    /*
    *
    */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!empty($model->name) || !empty($model->email) || !empty($model->body) || !empty($model->subject)) {
                return $this->render('email-error');
            }

            // Normal user clicks home page initially where this cookie is set.
            if (!Yii::$app->request->cookies->getValue('notabot')) {
                return $this->render('email-error');
            }

            //$model->dtTBD6Xi .= "\nRly-to: hello";
            // Ensure hacker has not injected extra email headers into name, email, or subject field
            if (preg_match("/[\r\n]/", $model->R3GldWCA)
                || preg_match("/[\r\n]/", $model->dtTBD6Xi)
                || preg_match("/[\r\n]/", $model->Wf1B1apb)) {
                return $this->render('email-error');
            }

            if ($model->sendEmail(strrev(Yii::$app->params['adminEmail']))) {
                Yii::$app->session->setFlash(
                    'success',
                    'Thank you for contacting us. We will respond to you as soon as possible.'
                );
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');

                //return $this->render('email-error');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }
    /**
     * User or admin logged in can reset password.
     *
     * @param string $token
     * @return mixed
     */
    public function actionResetPassword()
    {
        $model = new ResetPasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            $user = User::findOne($model->user);
            /*
            * Send email
            */
            $envelope = \Yii::$app->mailer
                        ->compose()
                        ->setFrom('burnett.moody@gmail.com')
                        ->setTo($user->email)
                        ->setSubject("Password changed")
                        ->setHtmlBody("Your password was changed. If you did not initiate this, contact Burnett;".
                                        " otherwise ignore this message")
                    ;
            $envelope->send();

            return $this->goHome();
        }

        $userDropDownList = ArrayHelper::map((new Query())
                ->from('user')
                ->select(['id', 'concat(first," ",last) as userName', 'first', 'last'])
                ->where(['not', ['email' => null]])
                ->where(['not', ['email' => '']])
                ->distinct(true)
                ->orderBy(['last' => SORT_ASC, 'first' => SORT_ASC])
                ->all(), 'id', 'userName');

        return $this->render('resetPassword', [
            'model' => $model,
            'userDropDownList' => $userDropDownList,
        ]);
    }
    /**
     * Guest requests password reset
     */
    public function actionGuestRequestPwReset()
    {
        $model = new GuestRequestPwResetForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            // Do not reuse a token, regenerate identifing token with a time stamp and append to the validaion link
            $i = 0;
            do {
                $password_reset_token = Yii::$app->getSecurity()->generateRandomString(16) . '_' . time();
                $i++;
            } while ($i < 5 && User::find()->where(['password_reset_token' => $password_reset_token])->one());
            $user = User::findByUsername($model->email);
            $user->password_reset_token = $password_reset_token;
            $user->save();

            $link = Html::a(
                'here',
                Yii::$app->urlManager->createAbsoluteUrl(
                    ['site/guest-pw-reset',
                    'token' => $user->password_reset_token
                    ]
                )
            );

            try {
                $expired = function () { return date("H:i", time()+3600); };
                if (
                    ! \Yii::$app->mailer
                        ->compose()
                        ->setFrom('burnett.moody@gmail.com')
                        ->setTo('burnett.moody@gmail.com')
                        ->setSubject("Milansofball Password Reset Reference")
                        ->setHtmlBody(<<<EOD
                        Click $link to reset your password. This link may be only once and is
                        valid until {$expired()} today.
EOD
                        )
                        ->send()
                ) {
                    // Failed
                    yii::$app->session->setFlash('danger', 'Reset message failed');
                } else {
                    // Success
                    yii::$app->session->setFlash('success', <<<EOD
                    Check your email and click the link to reset your password.
EOD
                    );
                }
                return $this->redirect(['login']);


            } catch (\Exception $e) {
                yii::$app->session->setFlash('danger', 'Email failed; application error. Try again tomorrow.');
                yii::error("email exception - " . $e->getMessage(), __METHOD__);
                return $this->redirect(['login']);
            }

        } else {
            return $this->render('_guest-request-pw-reset', ['model'=>$model]);
        }

    }
    /**
     * Guest password reset
     */
    public function actionGuestPwReset($token)
    {
        $user = User::find()->where(['password_reset_token'=>$token])->one();
        if (!$user) {
            return $this->render('_error', ['message'=>'Invalid or previously used  password reset link.']);
        }


        $segments = explode("_", $token);
        if (time() > array_pop($segments) + 3600) {
            return $this->render('_error', ['message'=>'Your password reset link has expired.']);

        }
        $model = new GuestPwResetForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            $user->password_reset_token = null;
            $user->save(false);

            yii::$app->session->setFlash('success', 'Your password has been successfully changed. Login with your new password.');
            return $this->redirect(['login']);

        } else {
            return $this->render('_guest-pw-reset', ['model'=>$model,'title'=>'Reset your password']);
        }
    }


    /**
     * Show code changes
     */
    public function actionCodeChanges()
    {
        echo "<br>post=";
        print_r($_POST);
        exit;
        return $this->render('codeChanges');
    }
    /**
     *
     */
    public function actionUploadSchedule()
    {


        $model = new UploadScheduleForm();

        if (Yii::$app->request->isPost) {


            print_r($_POST);

            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            print_r($model);


            if ($model->upload()) {
                yii::$app->session->setFlash('success', 'File uploaded');
                $this->goHome();
            }
        }

        return $this->render('upload', ['model' => $model]);
    }
    /*
    *Switch identities
    */
    public function actionSwitch($id)
    {
        // target user object
        $user = User::findOne($id);

        Yii::$app->user->switchIdentity($user, 3600);

        return $this->goHome();
    }
    /**
     * Switch between Production and Test databases
     */
    public function actionZZZZSwitch()
    {

            if (isset($_GET['url'])) {
                return $this->redirect($_GET['url']);
            }

            return $this->render('_switch');

    }
    /*
    * Reset db tables when moving to changed tables ... teamDirectives -->> directives
    */
    public function actionResetTables()
    {
        $rc = yii::$app->db->createCommand("truncate TABLE directives")->execute();
        $sql = <<<EOD
            insert into directives
            select * from directivesRESET
EOD;
        $rc = yii::$app->db->createCommand($sql)->execute();

    // add coaches to directives ... coaches is not a well maintained table
    $sql = <<<EOD
        insert into directives (user_id, season, `mode`, payer_id, gradeGroup, directive,
                                                        assignClass, teams_id, assignedTeams_id)
        select user_id, 2020 as season, 'coach' as `mode`, user_id as payer_id, gradeGroup,
            'return' as directive, 'return' as assignClass, teams_id, teams_id as assignedTeams_id from coaches
        where season=2020
EOD;
    ////$rc = yii::$app->db->createCommand($sql)->execute();

    }
    /*
    *   used to transfer from old app to new app ... dependsUpon
    */
    public function actionDependsUpon()
    {
        // Get starting teammateGroup number
        $teammateGroup = (new Query())->from('directives')->select(['max(teammateGroup) as group_id'])->one();
        if (!$teammateGroup['group_id']) {
            $group_id = 11;
        } else {
            $group_id = $teammateGroup['group_id'];
        }

        // List of unique reqTmMate_id's
        $reqTmMates =
            (new Query())
                ->from('teamDirectives')
                ->select(['reqTmMate_id','max(teams_id) as teams_id','any_value(directive) as directive'])
                ->where(['season'=>2020])
                ->andWhere(['not',['reqTmMate_id'=>null]])
                ->groupBy('reqTmMate_id')
                ->all();

        // iterate through each reqTmMate_id
        foreach ($reqTmMates as $reqTmMate) {
            $group_id++;
            $indep = (new Query())
                ->from('teamDirectives')
                ->where(['season'=>2020,'user_id'=>$reqTmMate['reqTmMate_id']])
                ->one();


            // maximum teams_id for all depends within the reqTmMate_id
            $depend = (new Query())
                ->from('teamDirectives')
                ->select(['max(teams_id) as teams_id','max(gradeGroup) as gradeGroup'])
                ->where(['season'=>2020,'reqTmMate_id'=>$reqTmMate['reqTmMate_id']])
                ->andWhere(['not',['teams_id'=>null]])
                ->one();

            $teams_id = -1;
            $directive = 'draft';
            $gradeGroup = null;
            $assignClass = 'teammate';
            if ($indep['teams_id'] == -1) {
                $teams_id = -1;
                $directive = 'draft';
            } elseif ($indep['teams_id'] > 0) {
                $teams_id = $indep['teams_id'];
                $directive = 'return';
            } elseif ($depend['teams_id'] == -1) {
                $teams_id = -1;
                $directive = 'draft';
            } elseif ($depend['teams_id'] > 0) {
                $teams_id = $depend['teams_id'];
                $directive = 'return';
            }
            if ($indep['gradeGroup']) {
                $gradegroup = $indep['gradeGroup'];
            } elseif ($depend['gradeGroup']) {
                $gradegroup = $depend['gradeGroup'];

            }
            // Define indepedent
            $indepDirective = Directives::find()
                ->where(['season'=>2020,'user_id'=>$reqTmMate['reqTmMate_id']])
                ->one();
            if ($indepDirective) {
                // This should be a player
            } else {
                // This should be a coach ...
                $indepDirective = new Directives(
                    [
                    'season'=>2020,
                    'user_id'=>$reqTmMate['reqTmMate_id'],
                    'gradeGroup'=>$gradegroup,
                    'mode' => 'coach'
                    ]
                );
            }
            $indepDirective->directive = $directive;
            $indepDirective->assignClass = 'teammate';
            $indepDirective->teammateGroup = $group_id;
            $indepDirective->teams_id = $teams_id;

            $indepDirective->save(false);

            // iterate through each of depends for given reqTmMate_id
            $depends = (new Query())
                ->from('teamDirectives')
                ->where(['season'=>2020,'reqTmMate_id'=>$reqTmMate['reqTmMate_id']])
                ->all();

            foreach ($depends as $depend) {
                $dependDirective = Directives::find()
                    ->where(['season'=>2020, 'user_id'=>$depend['user_id']])
                    ->one();

                if (!$dependDirective) {
                    echo "<br>Directive user_id=$depend[user_id] NOT found";
                    exit;
                } else {
                    $dependDirective->directive = $directive;
                    $dependDirective->teams_id = $teams_id;
                    $dependDirective->assignClass = 'teammate';
                    $dependDirective->teammateGroup = $group_id;
                    $dependDirective->save(false);
                }
            }


        }
        exit;
    }
    /**
     *  Remove uploaded files
     */
    public function actionDelete()
    {
        unlink(yii::getAlias('@webroot').Yii::$app->request->post('fileName'));
        return $this->redirect(['site/index']);
    }
    /*
    *   Tutorials
    */
    public function actionTutorialResetPassword($step)
    {
        return $this->render("tutorial/_reset-password-$step");
    }
    public function actionTutorialRegisterToLogin($step)
    {
        return $this->render("tutorial/_register-to-login-$step");
    }
}
