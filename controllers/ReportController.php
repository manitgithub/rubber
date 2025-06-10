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
    public function actionDaily()
{
    $model = new \yii\base\DynamicModel(['date']);
    $model->addRule('date', 'required');

    $date = Yii::$app->request->get('date', date('Y-m-d'));
    $model->date = $date;

    $purchases = \app\models\Purchases::find()
        ->where(['date' => $date])
        ->andWhere(['flagdel' => 0])
        ->all();

    $total_weight = $total_dry_weight = $total_amount = 0;
    foreach ($purchases as $p) {
        $total_weight += $p->weight;
        $total_dry_weight += $p->dry_weight;
        $total_amount += $p->total_amount;
    }

    return $this->render('daily', [
        'model' => $model,
        'date' => $date,
        'purchases' => $purchases,
        'total_weight' => $total_weight,
        'total_dry_weight' => $total_dry_weight,
        'total_amount' => $total_amount,
    ]);
}

}
