<?php

namespace rushstart\user\controllers;

use rushstart\user\models\LoginByEmailForm;
use rushstart\user\models\SignupByEmailForm;
use Yii;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * User controller for the `user` module
 */
class UserController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionList()
    {
        //user list
    }

    public function actionIndex()
    {
        //current user
        dpm(Yii::$app->user->accessChecker);
        dpm(Yii::$app->user->can('change_user_password'));
        dpm(Yii::$app->getAuthManager());
        return $this->render('index');
    }

    public function actionEdit()
    {
        //current user update
    }

    public function actionUpdate($id)
    {
        //user update
    }

    public function actionView($id)
    {
        //user view
    }

    public function actionDelete($id)
    {
        //user delete
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginByEmailForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Signs user up.
     *
     * @return Response|string
     * @throws Exception
     */
    public function actionSignup()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignupByEmailForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            if (Yii::$app->user->isGuest) {
                $this->redirect(['login']);
            } else {
                return $this->goHome();
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
