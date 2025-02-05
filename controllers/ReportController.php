<?php

namespace app\controllers;

use app\models\Employee;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class ReportController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),

            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionMonthlyReport()
    {
        return $this->render('monthly-report');
    }
    public function actionCurrentStock()
    {
        return $this->render('current-stock');
    }
    public function actionCurrentStockDetail()
    {
        return $this->render('current-stock-detail');
    }
    public function actionExpiredProduct()
    {
        return $this->render('expired-product');
    }
    public function actionRemainingProduct()
    {
        return $this->render('remaining-product');
    }

    public function actionMovementProduct()
    {
        return $this->render('movement-product');
    }

    public function actionMonthly()
    {
        return $this->render('monthly');
    }
    public function actionReportProduct()
    {
        return $this->render('report-product');
    }
    public function actionReportPartner()
    {
        return $this->render('report-partner');
    }
    public function actionRequisitionProduct()
    {
        return $this->render('requisition-product');
    }
}
