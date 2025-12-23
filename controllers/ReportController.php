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
            $query->andFilterWhere([
                'or',
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

        $request = Yii::$app->request;
        $sdate = $request->get('sdate', date('Y-m-d'));
        $edate = $request->get('edate', date('Y-m-d'));
        $view_mode = $request->get('view_mode', 'daily'); // 'daily' or 'summary'

        $purchases = [];
        $member_summary = [];
        $total_weight = $total_dry_weight = $total_amount = 0;
        $total_count = 0;

        if ($view_mode === 'daily') {
            // Daily view - show individual transactions
            $purchases = \app\models\Purchases::find()
                ->joinWith('members')
                ->where(['between', 'purchases.date', $sdate, $edate])
                ->andWhere(['purchases.flagdel' => 0])
                ->orderBy(['members.memberid' => SORT_ASC])
                ->all();

            foreach ($purchases as $p) {
                $total_weight += $p->weight;
                $total_dry_weight += $p->dry_weight;
                $total_amount += $p->total_amount;
            }
            $total_count = count($purchases);
        } else {
            // Summary view - aggregate by member
            $member_summary = (new \yii\db\Query())
                ->select([
                    'member_id',
                    'count' => 'COUNT(*)',
                    'total_weight' => 'SUM(weight)',
                    'total_dry_weight' => 'SUM(dry_weight)',
                    'avg_percentage' => 'AVG(percentage)',
                    'avg_price' => 'AVG(price_per_kg)',
                    'total_amount' => 'SUM(total_amount)',
                ])
                ->from('purchases')
                ->where(['between', 'date', $sdate, $edate])
                ->andWhere(['flagdel' => 0])
                ->groupBy(['member_id'])
                ->orderBy(['member_id' => SORT_ASC])
                ->all();

            // Load member details and calculate totals
            foreach ($member_summary as &$row) {
                $member = Members::findOne($row['member_id']);
                $row['member'] = $member;
                $total_weight += $row['total_weight'];
                $total_dry_weight += $row['total_dry_weight'];
                $total_amount += $row['total_amount'];
                $total_count += $row['count'];
            }
        }

        return $this->render('daily', [
            'model' => $model,
            'sdate' => $sdate,
            'edate' => $edate,
            'view_mode' => $view_mode,
            'purchases' => $purchases,
            'member_summary' => $member_summary,
            'total_weight' => $total_weight,
            'total_dry_weight' => $total_dry_weight,
            'total_amount' => $total_amount,
            'total_count' => $total_count,
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

    public function actionSalesByMember()
    {
        $request = Yii::$app->request;
        $member_id = $request->get('member_id');
        $sdate = $request->get('sdate', date('Y-m-01')); // Default to first day of current month
        $edate = $request->get('edate', date('Y-m-d')); // Default to today
        $view_mode = $request->get('view_mode', 'daily'); // 'daily' or 'monthly'

        // Convert Buddhist Era to Christian Era if needed
        if ($sdate && preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $sdate, $matches)) {
            $year = (int) $matches[1];
            if ($year > 2500) { // Buddhist Era
                $year = $year - 543;
                $sdate = $year . '-' . $matches[2] . '-' . $matches[3];
            }
        }

        if ($edate && preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $edate, $matches)) {
            $year = (int) $matches[1];
            if ($year > 2500) { // Buddhist Era
                $year = $year - 543;
                $edate = $year . '-' . $matches[2] . '-' . $matches[3];
            }
        }

        // Get all members for dropdown
        $members = Members::find()
            ->orderBy(['memberid' => SORT_ASC])
            ->all();

        $purchases = [];
        $monthly_summary = [];
        $total_weight = $total_dry_weight = $total_amount = 0;
        $total_count = 0;

        if ($member_id) {
            if ($view_mode === 'daily') {
                // Daily view - show individual transactions
                $purchases = \app\models\Purchases::find()
                    ->joinWith('members')
                    ->where(['purchases.member_id' => $member_id])
                    ->andWhere(['between', 'purchases.date', $sdate, $edate])
                    ->andWhere(['purchases.flagdel' => 0])
                    ->orderBy(['purchases.date' => SORT_ASC])
                    ->all();

                foreach ($purchases as $p) {
                    $total_weight += $p->weight;
                    $total_dry_weight += $p->dry_weight;
                    $total_amount += $p->total_amount;
                }
                $total_count = count($purchases);
            } else {
                // Monthly view - aggregate by month
                $monthly_summary = (new \yii\db\Query())
                    ->select([
                        'month' => 'DATE_FORMAT(date, "%Y-%m")',
                        'count' => 'COUNT(*)',
                        'total_weight' => 'SUM(weight)',
                        'total_dry_weight' => 'SUM(dry_weight)',
                        'total_amount' => 'SUM(total_amount)',
                        'avg_price' => 'AVG(price_per_kg)',
                    ])
                    ->from('purchases')
                    ->where(['member_id' => $member_id])
                    ->andWhere(['between', 'date', $sdate, $edate])
                    ->andWhere(['flagdel' => 0])
                    ->groupBy(['DATE_FORMAT(date, "%Y-%m")'])
                    ->orderBy(['month' => SORT_ASC])
                    ->all();

                foreach ($monthly_summary as $row) {
                    $total_weight += $row['total_weight'];
                    $total_dry_weight += $row['total_dry_weight'];
                    $total_amount += $row['total_amount'];
                    $total_count += $row['count'];
                }
            }
        }

        return $this->render('sales-by-member', [
            'members' => $members,
            'member_id' => $member_id,
            'sdate' => $sdate,
            'edate' => $edate,
            'view_mode' => $view_mode,
            'purchases' => $purchases,
            'monthly_summary' => $monthly_summary,
            'total_weight' => $total_weight,
            'total_dry_weight' => $total_dry_weight,
            'total_amount' => $total_amount,
            'total_count' => $total_count,
        ]);
    }

}
