<?php

namespace app\controllers;

use Yii;
use app\models\CheckDuplicates;
use app\components\Season;
use app\models\User;
use app\models\Directives;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

class UserController extends Controller
{
    /*
    * If session has expired redirect to login
    * Since methods can be invoked from multiple sources, need to save the invokee
    */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        // If session expired; reset payer_id to logged in user.
        if (!isset(yii::$app->session['payer_id'])) {
            yii::$app->session['payer_id'] = yii::$app->user->id;
        }
        // Need to save where invoked in order to return there
        if (preg_match('/update|create|check-duplicates|coach-multiple|lookup/', yii::$app->request->referrer) ==0) {
            Url::remember(yii::$app->request->referrer, yii::$app->controller->id);
        }
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    /*
    *   AJAX, provide a list of address for autocomplete
    *   ... invoked from views/user/_form
    */
    public function actionStreet()
    {
        $query = (new Query())
            ->from('user')
            ->select(['street as index','street as label','city','state','zip'])
            ->andWhere(['like','street', trim($_GET['term']) ])
            ->distinct(true)
            ->orderBy('street')
            ;
        return json_encode($query->all());
    }

    /*
    *   AJAX, provide a list of address for autocomplete
    *   ... invoked from views/user/_form
    */
    public function actionCity()
    {
        $query = (new Query())
            ->from('zipCodes')
            ->select(['id as index','city as label','state','zipCode'])
            ->andWhere(['like','city', trim($_GET['term']) ])
            ->distinct(true)
            ->orderBy('city')
            ;
        return json_encode($query->all());
    }
    /*
    *   AJAX, list of users
    */
    public function actionAll()
    {
        /*
        *   Players & Parents within the last 2 years
        */
        $recent = (new Query())
            ->from('user')
            ->leftJoin('directives players', 'players.user_id=user.id')
            ->leftJoin('directives parents', 'parents.payer_id=user.id')
            ->select(['user.id'])
            ->distinct(true)
            ->where(['>','players.season', (new Season)->year()-3])
            ->orWhere(['>','parents.season', (new Season)->year()-3])
            ;
        ;
        $users = (new Query())
            ->from(['unq'=>$recent])
            ->leftJoin('user', 'user.id=unq.id')
            ->select(['unq.id as index','concat(first," ",last) as label',
                    'first','last','mobile','email','street','street2',
                    'city','state','zip','birthdate'
                    ])
            ->andWhere(['like','concat(first," ",last)', preg_replace('!\s+!', ' ', trim($_GET['term'])) ])
            ;

        return Json_encode($users->all());
    }
    /*
    *   AJAX
    *   List of players for players/add registered within the last 2 years
    */
    public function actionPlayers()
    {
        $players = (new Query())
            ->from('directives')
            ->leftJoin('user', 'user.id=directives.user_id')
            ->select(['user_id as index','concat(first," ",last) as label',
                    'first','last','mobile','email','street','street2',
                    'city','state','zip','birthdate'
            ])
            ->distinct(true)
            ->where(['>','season', (new Season)->year()-3])
            ->andWhere(['like','concat(first," ",last)', preg_replace('!\s+!', ' ', trim($_GET['term'])) ])
            ->orderBy(['last'=>\SORT_ASC,'first'=>SORT_ASC])
            ;

        return Json_encode($players->all());
    }


    /**
     * Add a user (may be a player, coach, contact, etc)
     * The user may already exist or may need to be created
     */
    public function actionLookup()
    {
        $model = new User();

        // AJAX validation
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
// print_r($_POST);
// exit;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            // save user properties in needed later
            $_SESSION['checkDuplicates'] = $model->attributes;

// print_r($_POST);
// echo "<br><br>";
// print_r($model->attributes);
// exit;

            if ($model->id < 0) {
                return $this->redirect(['check-duplicates']);

            } elseif ($model->id > 0 && in_array(yii::$app->user->identity->role, ['admin','registrar'])) {
                $_SESSION['Directives']['user_id'] = $model->id;
                $_SESSION['PreviousPlayerContacts']['contacts_id'] = $model->id;
                return $this->redirect(['directives','user_id'=>$model->id]);

            } elseif ($model->id > 0) {
                // Lookup was a new parent.
                return $this->redirect(['check-duplicates']);
            } else {
                // error
                echo "user/lookup has returned a undefined model->id";
                exit;
            }
        }

        if ($model->hasErrors()) {
            echo "<br>validation error=";
            print_r($model->attributes);
            echo "<br>";
            print_r($model->getErrors());
            exit;
        }

        // For a player fillin parent's address
        if (yii::$app->controller->id == 'players') {
            $parent = User::find()
                ->select(['street','street2','city','state','zip'])
                ->where(['id'=>$_SESSION['payer_id']])
                ->one();
            $model->attributes = $parent->attributes;
        }
        // if (isset($_SESSION['checkDuplicates'])) {
        //     $model->attributes = $_SESSION['checkDuplicates'];
        //     $model->searchName = $_SESSION['checkDuplicates']['first'] ." ". $_SESSION['checkDuplicates']['last'];
        // }
        $model->id = -1;
        return $this->render('create', ['model' => $model]);
    }
    /*
    * Check Exact, Partial, asEntered User matches
    * Table user primary key = 'id'
    */
    public function actionCheckDuplicates()
    {
        // print_r($_POST);
        // exit;
        $model = new CheckDuplicates();
        if (isset($_POST['id'])) {
            if ($_POST['id'] > 0) {
                if (yii::$app->controller->id == 'contacts') {
                    // Add an existing user
                    $_SESSION['PreviousPlayerContacts']['contacts_id'] = $_POST['id'];
                    return $this->redirect(['directives']);
                } else {
                    // Add an existing user
                    $_SESSION['Directives']['user_id'] = $_POST['id'];
                    return $this->redirect(['directives']);
                }
            } else {
                // Create a new user.
                return $this->redirect(['create']);
            }
            //
        }
        // check-duplicates ==> '@app/views/checkDuplicates/_form' to allow labeling

        return $this->render('check-duplicates', [
            'model' => $model,
        ]);
    }
    /*
    *   asEntered selected from 'check-duplicates'
    */
    public function actionCreate()
    {
        $user = new User();
        $user->attributes = $_SESSION['checkDuplicates'];

        $user->id = null;
        if (!$user->save(false)) {
            yii::error("\n*** directive validation errors=".print_r($user->getErrors(), true), __METHOD__);
            yii::$app->session->setFlash(
                'error',
                "error validating directive".print_r($user->getErrors(), true)
            );
        }

        if (yii::$app->controller->id == 'contacts') {
            $_SESSION['PreviousPlayerContacts']['contacts_id'] = $user->id;
        } else {
            $_SESSION['Directives']['user_id'] = $user->id;
        }

        return $this->redirect(['directives']);
    }
    /*
    *   Only needed for table 'user'
    *   Invoked from admin / Users - Name-Address-Mobile-Email
    */
    public function actionIndex()
    {
        $query = (new Query())->from('user')->orderBy(['last'=>SORT_ASC,'last'=>SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' =>['pageSize'=>-1]
                    ]);
        return $this->render("@app/views/user/index", ['dataProvider'=>$dataProvider]);
    }
    /*
    *   Do not check for duplicates
    *   ... player contacts must use update-contacts as the params are different
    */
    public function actionUpdate()
    {
        if (isset($_POST['id'])) {
            $_SESSION['id'] = $_POST['id'];
        }
        $model = User::findOne($_SESSION['id']);

        // AJAX validation
        if (!isset($_POST['referrer']) && Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $_SESSION['checkDuplicates'] = $model->attributes;
            return $this->redirect((isset(yii::$app->controller->id) ? Url::previous(yii::$app->controller->id) : ['family/summary']));
        }

        // defaults to controller/_form; like players/_form, coaches/_form/ contacts/_form
        return $this->render('update', ['model' => $model]);
    }
    /*
    *   Previous coach to be added as denoted in $_POST['Directives']['user_id']
    *   NMay be coaching other teams; select one of those or create a new coach position.
    */
    public function actionCoachMultipleTeams()
    {
        if (isset($_POST['Directives']['user_id'])) {
            $_SESSION['Directives']['user_id'] = $_POST['Directives']['user_id'];
        }

        $model = new Directives(['season'=>(new Season)->year(), 'user_id'=>$_SESSION['Directives']['user_id'],
            'mode'=>'coach',  'id' => -1]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        /*
        *   Check duplicates returns the choice of grade group and team
        *   in model[id]
        */
        if (isset($_POST['Directives']['id']) && $model->load(Yii::$app->request->post())) {

            if ($model->id == -1) {
                // selected the new model
                if ($model->validate()) {
                    $model->id = null;
                    if (!isset($_SESSION['otherCoach'])) {
                        // only panel user from this family
                        $model->payer_id = $_SESSION['payer_id'];
                    }
                    $model->save();
                    $_SESSION['other'] = 1;
                    $_SESSION['directives_id'] = $model->id;
                    //return $this->redirect(Url::previous('coaches'));
                } else {
                    echo "<br>validation failed -";
                    print_r($model->getErrors());
                    exit;
                }
                // error
            } elseif ($model->id > 0) {
                // chose existing coach grade group & team
                // update to season and family
                $directive = Directives::findOne($model->id);
                if (!isset($_SESSION['otherCoach'])) {
                    $model->payer_id = $_SESSION['payer_id'];
                }
                $directive->season = (new Season)->year();
                $directive->save(false);
                $_SESSION['other'] = 0;
                $_SESSION['directives_id'] = $directive->id;
                //return $this->redirect(Url::previous('coaches'));
            } else {
                // error
            }
            if (yii::$app->controller->id == 'teammates') {

                return $this->redirect(['teammates/membership']);
            } else {
                return $this->redirect(Url::previous('coaches'));
            }
        }
        // Same coach any other coaching volunteer/assignment
        $query = (new Query())
            ->from('directives')
            ->leftJoin('teams', 'teams.id=directives.teams_id')
            ->select(['directives.id','user_id','payer_id','directives.gradeGroup','directive','teams_id','name as teamName'])
            ->where(['season'=>(new Season)->year(), 'mode'=>'coach', 'user_id'=>$_SESSION['Directives']['user_id']])
            ;

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        return $this->render('_duplicate-directives', [
            'error' => '',
            'dataProvider' => $dataProvider,
            'model' => $model,
            'coachName'=>User::find()
                ->select(['concat(first," ",last)'])
                ->where(['id'=>$_SESSION['Directives']['user_id']])
                ->scalar()
            ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {

        $this->findModel($_POST['id'])->delete();

        return $this->redirect(Url::previous(yii::$app->controller->id));
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
