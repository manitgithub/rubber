<?php

namespace app\controllers;

use app\models\AuditDetail;
use app\models\AuditHeader;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class ChangeStockController extends Controller
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
        $startDate = Yii::$app->request->get('start-date');
        $endDate = Yii::$app->request->get('end-date');
        if (empty($startDate)) {
            $startDate = date('Y-m-') . "01";
        }
        if (empty($endDate)) {
            $endDate = date('Y-m-d');
        }

        $model = AuditHeader::find()
            ->where(['type' => 'M', 'flag_del' => 0])
            ->andWhere(['>=', 'DATE_FORMAT(created_at, "%Y-%m-%d")', $startDate])
            ->andWhere(['<=', 'DATE_FORMAT(created_at, "%Y-%m-%d")', $endDate])
            ->all();
        return $this->render('index', [
            'model' => $model,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function actionInit()
    {
        return $this->render('init');
    }

    public function actionCreate($department_id)
    {
        $model = new AuditHeader();
        $modelDetail = null;
        $model->billno = Yii::$app->helpers->generateAuditCode('M');
        $model->type = 'M';
        $model->status = '1';
        $model->partner_id = $department_id;
        $model->department_id = $department_id;
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                $model->date = date('Y-m-d H:i:s');
                $model->save(false);

                //new record
                if ($this->request->post('NewAuditDetail')) {
                    foreach ($this->request->post('NewAuditDetail') as $detail) {
                        $modelDetailNew = new AuditDetail();
                        $modelDetailNew->audit_id = $model->id;
                        $modelDetailNew->part_id = $detail['part_id'];
                        $modelDetailNew->audit_txt = $detail['audit_txt'];
                        $modelDetailNew->unit_price = $detail['currentStock'];
                        $modelDetailNew->qty_uom = $detail['qty'] - $detail['currentStock'];
                        $modelDetailNew->qty = (int) $detail['qty_uom'] * (int) $modelDetailNew->qty_uom;
                        $modelDetailNew->total_amount = $detail['total_amount'];
                        $modelDetailNew->uom_id = $detail['uom_id'];
                        $modelDetailNew->expdate = empty($detail['expdate']) ? '' : Yii::$app->helpers->dateTHtoEN($detail['expdate']);
                        $modelDetailNew->save(false);
                    }
                }

                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('_form', [
            'model' => $model,
            'modelDetail' => $modelDetail
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelDetail = AuditDetail::find()->where(['audit_id' => $id, 'flag_del' => 0])->all();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                $model->date = date('Y-m-d H:i:s');
                $model->save(false);

                //new record
                if ($this->request->post('NewAuditDetail')) {
                    foreach ($this->request->post('NewAuditDetail') as $detail) {
                        print_r($detail);
                        $modelDetailNew = new AuditDetail();
                        $modelDetailNew->audit_id = $model->id;
                        $modelDetailNew->part_id = $detail['part_id'];
                        $modelDetailNew->audit_txt = $detail['audit_txt'];
                        $modelDetailNew->unit_price = $detail['unit_price'];
                        $modelDetailNew->qty = $detail['qty'];
                        $modelDetailNew->qty_uom = '';
                        $modelDetailNew->total_amount = $detail['total_amount'];
                        $modelDetailNew->uom_id = '';
                        $modelDetailNew->expdate = '';
                        $modelDetailNew->save(false);
                    }
                }

                return $this->redirect(['index']);
            }
        }

        return $this->render('_form', [
            'model' => $model,
            'modelDetail' => $modelDetail
        ]);
    }

    public function actionCancel($id)
    {
        $model = $this->findModel($id);
        $model->status = '0';
        $model->save(false);

        return $this->redirect(['index']);
    }


    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->flag_del = 1;
        $model->save(false);

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = AuditHeader::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
