<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\AuditDepartment;
use yii\helpers\Json;

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
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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
    public function actionApprove()
    {
        return $this->render('approve');
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = "blank";
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $text = 'มีการเข้าสู่ระบบโดย ' . Yii::$app->user->identity->fullname . '(' . Yii::$app->user->identity->position . ')';
            Yii::$app->helpers->LineNotify($text);
            return $this->goHome();
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

        return $this->goHome();
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
    public function actionDashboard()
    {
        return $this->render('dashboard');
    }
    public function actionAdit()
    {
        header('Content-Type: application/json');
        $models = AuditDepartment::find()->asArray()->all();
        $departmentData = [];
        foreach ($models as $depart) {
            $departmentData[$depart['id']]['id'] = $depart['id'];
            $departmentData[$depart['id']]['department'] = $depart['department'];
        }
        return Json::encode($departmentData);
    }

    public function actionDashboardData($id)
    {
        header('Content-Type: application/json');


        $totalItem = [];

        $totalPrice = 0;
        //นำเข้าสินค้า เบิกสินค้า
        $modelAudit = Yii::$app->db->createCommand("SELECT log.part_id
    ,item.unit_price
    ,COALESCE(SUM(
        CASE WHEN header.type = 'S' THEN log.qty 
        WHEN header.type = 'R' THEN -log.qty 
        WHEN header.type = 'M' THEN log.qty
        WHEN header.type = 'T' AND header.department_id = $id AND header.department_id != header.partner_id THEN log.qty 
        WHEN header.type = 'T' AND header.partner_id = $id AND header.department_id != header.partner_id THEN -log.qty 
        ELSE 0 END)
    , 0) AS sum_qty
    ,uom.uom_name
    FROM item_part AS item
    LEFT JOIN audit_detail AS log ON item.id = log.part_id
    LEFT JOIN audit_header AS header ON header.id = log.audit_id 
    AND (
        (header.type IN ('S','T','M') AND header.department_id = $id) 
        OR (header.type IN ('R','T') AND header.partner_id = $id)
        )
    AND header.status = 1 
    AND header.flag_del = 0
    LEFT JOIN item_uom AS uom ON item.uom_id = uom.id
    WHERE item.flag_del = 0 
    AND COALESCE(log.flag_del, 0) = 0
    GROUP BY item.id, item.part_name
    HAVING sum_qty != 0")
            ->queryAll();


        foreach ($modelAudit as $audit) {
            $totalItem[] = $audit['part_id'];
            $totalPrice = $totalPrice + ($audit['sum_qty'] * $audit['unit_price']);
        }
        if (count($totalItem) > 0) {
            $departmentData['totalItem'] = count($totalItem);
            $departmentData['totalPrice'] = $totalPrice;
        }

        return Json::encode($departmentData);
    }
}
