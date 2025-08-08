<?php

namespace app\controllers;

use app\models\Employee;
use app\models\Members;
use app\models\Prices;
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
    public function actionReportSummary()
    {

        return $this->render('report-summary');
    }
    public function actionMemberReport()
    {
        $request = Yii::$app->request;
        $keyword = $request->get('keyword');
        $membertype = $request->get('membertype');
        $sdate = $request->get('sdate');
        $edate = $request->get('edate');

        $query = Members::find();

        if ($keyword) {
            $query->andFilterWhere(['or',
                ['like', 'memberid', $keyword],
                ['like', 'idcard', $keyword],
                ['like', 'name', $keyword],
                ['like', 'surname', $keyword],
                ['like', 'phone', $keyword],
            ]);
        }

        if ($membertype) {
            $query->andWhere(['membertype' => $membertype]);
        }

        // Filter by register date (stdate)
        if ($sdate && $edate) {
            $query->andWhere(['between', 'stdate', $sdate, $edate]);
        } elseif ($sdate) {
            $query->andWhere(['>=', 'stdate', $sdate]);
        } elseif ($edate) {
            $query->andWhere(['<=', 'stdate', $edate]);
        }

        $query->orderBy(['memberid' => SORT_ASC]);

        $members = $query->all();

        return $this->render('member-report', [
            'members' => $members,
            'keyword' => $keyword,
            'membertype' => $membertype,
            'sdate' => $sdate,
            'edate' => $edate,
        ]);
    }
    public function actionDaily()
{
    $model = new \yii\base\DynamicModel(['date']);
    $model->addRule('date', 'required');

    $sdate = Yii::$app->request->get('sdate', date('Y-m-d'));
    $edate = Yii::$app->request->get('edate', date('Y-m-d'));

    $purchases = \app\models\Purchases::find()
        ->where(['between', 'date', $sdate, $edate])
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
        'sdate' => $sdate,
        'edate' => $edate,
        'purchases' => $purchases,
        'total_weight' => $total_weight,
        'total_dry_weight' => $total_dry_weight,
        'total_amount' => $total_amount,
    ]);
}

    public function actionQualityReport()
    {
        $request = Yii::$app->request;
        $sdate = $request->get('sdate', date('Y-m-01'));
        $edate = $request->get('edate', date('Y-m-d'));

        // Aggregate quality metrics by date
        $results = (new \yii\db\Query())
            ->select([
                'date' => 'DATE(date)',
                'count' => 'COUNT(*)',
                'avg_percentage' => 'AVG(percentage)',
                'min_percentage' => 'MIN(percentage)',
                'max_percentage' => 'MAX(percentage)',
                'sum_weight' => 'SUM(weight)',
                'sum_dry_weight' => 'SUM(dry_weight)',
            ])
            ->from('purchases')
            ->where(['between', 'date', $sdate, $edate])
            ->andWhere(['flagdel' => 0])
            ->groupBy(['DATE(date)'])
            ->orderBy(['date' => SORT_ASC])
            ->all();

        // Overall stats
        $overall = (new \yii\db\Query())
            ->select([
                'count' => 'COUNT(*)',
                'avg_percentage' => 'AVG(percentage)',
                'min_percentage' => 'MIN(percentage)',
                'max_percentage' => 'MAX(percentage)',
                'sum_weight' => 'SUM(weight)',
                'sum_dry_weight' => 'SUM(dry_weight)',
            ])
            ->from('purchases')
            ->where(['between', 'date', $sdate, $edate])
            ->andWhere(['flagdel' => 0])
            ->one();

        return $this->render('quality-report', [
            'sdate' => $sdate,
            'edate' => $edate,
            'results' => $results,
            'overall' => $overall,
        ]);
    }

    public function actionPriceReport()
    {
        $request = Yii::$app->request;
        $sdate = $request->get('sdate', date('Y-m-01'));
        $edate = $request->get('edate', date('Y-m-d'));

        $prices = Prices::find()
            ->where(['between', 'date', $sdate, $edate])
            ->orderBy(['date' => SORT_ASC])
            ->all();

        // Aggregate stats
        $stats = (new \yii\db\Query())
            ->select([
                'count' => 'COUNT(*)',
                'avg_price' => 'AVG(price)',
                'min_price' => 'MIN(price)',
                'max_price' => 'MAX(price)',
            ])
            ->from(Prices::tableName())
            ->where(['between', 'date', $sdate, $edate])
            ->one();

        return $this->render('price-report', [
            'sdate' => $sdate,
            'edate' => $edate,
            'prices' => $prices,
            'stats' => $stats,
        ]);
    }

}
