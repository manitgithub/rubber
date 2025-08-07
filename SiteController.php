<?php

namespace app\controllers;

use app\models\Abt;
use app\models\Amphur;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\ChangePasswordForm;
use app\models\F1;
use app\models\F2;
use app\models\Log;
use app\models\Moo;
use app\models\People;
use app\models\Province;
use app\models\SignupForm;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use app\models\ResendVerificationEmailForm;
use app\models\Tambol;
use app\models\User;
use app\models\VerifyEmailForm;
use yii\base\Model;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout', 'reset-password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionJsonProvince($id)
    {
        $this->layout = false;
        $model = Province::find()->where(['zone_id' => $id])->orderBy(['Province_Name' => SORT_ASC])->all();
        return $this->render('_json_province', [
            'model' =>  $model,
        ]);
    }

    public function actionJsonAmphur($id)
    {
        $this->layout = false;
        $model = Amphur::find()->where(['province_id' => $id])->orderBy(['Amphur_Name' => SORT_ASC])->all();
        return $this->render('_json_amphur', [
            'model' =>  $model,
        ]);
    }

    public function actionJsonTambol($id)
    {
        $this->layout = false;
        $model = Tambol::find()->where(['amper_id' => $id])->orderBy(['Tambol_Name' => SORT_ASC])->all();
        return $this->render('_json_tambol', [
            'model' =>  $model,
        ]);
    }

    public function actionJsonAbt($id)
    {
        $this->layout = false;
        $model = Abt::find()->where(['tambol_id' => $id])->orderBy(['abt_name' => SORT_ASC])->all();
        return $this->render('_json_abt', [
            'model' =>  $model,
        ]);
    }

    public function actionJsonVillage($id)
    {
        $this->layout = false;
        $model = Moo::find()->where(['abt_id' => $id])->orderBy(['name' => SORT_ASC])->all();
        return $this->render('_json_village', [
            'model' =>  $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'blank';
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/main/index']);
            //return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            // log
            $modelLog = new Log();
            $modelLog->username = $model->username;
            $modelLog->device = Yii::$app->request->userAgent;
            $modelLog->ipaddress = Yii::$app->request->userIP;
            $modelLog->save();

            // line alert
            Yii::$app->helpers->lineAlert($model->username, 'login');

            return $this->redirect(['/main/index']);
            //return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['/site/tcnap']);
        //return $this->goHome();
    }

    public function actionTcnap()
    {
        return $this->render('tcnap');
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $this->layout = 'blank';

        $role = Yii::$app->helpers->decodeUrl('role');


        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'ท่านได้สมัครเรียบร้อยแล้ว, กรุณารอการอนุมัติการใช้งาน');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
            'role' => $role,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionChangePassword()
    {
        $this->layout = 'blank';
        try {
            $model = new ChangePasswordForm(Yii::$app->user->identity->username);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }


        return $this->render('changePassword', [
            'model' => $model,
        ]);
    }
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    public function actionResetdb()
    {
        $table = Yii::$app->request->get('table');
        $from = Yii::$app->request->get('from');
        $to = Yii::$app->request->get('to');

        if (!empty($table) && !empty($from) && !empty($to)) {
            if ($table == 'f1') {
                $models = F1::find()
                    ->where(['!=', 'value.' . $from, null])
                    ->all();

                $modelsInsite = People::find()
                    ->where(['!=', 'value.' . $from, null])
                    ->all();
            } else if ($table == 'f2') {
                $models = F2::find()
                    ->where(['!=', 'value.' . $from, null])
                    ->all();

                $modelsInsite = Moo::find()
                    ->where(['!=', 'value.' . $from, null])
                    ->all();
            }
            if (Yii::$app->request->post()) {
                foreach ($models as $model) {
                    $value = [
                        $to => $model->value[$from],
                    ];

                    $model->updateAttributes(['value' => null]);
                    $model->updateAttributes(['value' => $value]);
                }

                foreach ($modelsInsite as $model) {
                    $value = [
                        $to => $model->value[$from],
                    ];

                    $model->updateAttributes(['value' => null]);
                    $model->updateAttributes(['value' => $value]);
                }

                return $this->redirect(['resetdb?table=' . $table . '&from=' . $from . '&to=' . $to]);
            }
        }

        return $this->render(
            'resetdb',
            [
                'models' => $models,
                'modelsInsite' => $modelsInsite,
                'table' => $table,
            ]
        );
    }
}
