<?php

namespace app\controllers;

use app\models\AuditDetail;
use app\models\AuditHeader;
use app\models\ItemPart;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class GoodsReceiptsController extends Controller
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
                            'actions' => ['view'],
                            'allow' => true,
                        ],
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
            ->where(['type' => 'S', 'flag_del' => 0])
            ->andWhere(['>=', 'DATE_FORMAT(created_at, "%Y-%m-%d")', $startDate])
            ->andWhere(['<=', 'DATE_FORMAT(created_at, "%Y-%m-%d")', $endDate])
            ->all();
        return $this->render('index', [
            'model' => $model,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function actionCreate($po_id = null)
    {
        $model = new AuditHeader();
        $modelDetail = [];
        $model->billno = Yii::$app->helpers->generateAuditCode('S');
        $model->type = 'S';
        $model->date_action = date('Y-m-d');
        $model->status = '1';
        if ($po_id) {
            $modelPO = AuditHeader::findOne(['id' => $po_id]);
            $model->partner_id = $modelPO->partner_id;
            $model->department_id = $modelPO->department_id;
            $model->note = 'อ้างอิงจากใบสั่งซื้อ เลขที่ #' . $modelPO->billno;
            $modelDetail = AuditDetail::find()->where(['audit_id' => $modelPO->id, 'flag_del' => 0])->all();
        }
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
                        $modelDetailNew->unit_price = $detail['unit_price'];
                        $modelDetailNew->qty_uom = $detail['qty'];
                        $modelDetailNew->qty = (int) $detail['qty_uom'] * (int) $detail['qty'];
                        $modelDetailNew->total_amount = $detail['total_amount'];
                        $modelDetailNew->uom_id = $detail['uom_id'];
                        $modelDetailNew->expdate = $detail['expdate'];
                        $modelDetailNew->save(false);

                        $modelItem = ItemPart::findOne(['id' => $modelDetailNew->part_id]);
                        $modelItem->unit_price = $modelDetailNew->unit_price / (int) $detail['qty_uom'];
                        $modelItem->save(false);
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

    public function actionPrint($id)
    {
        $this->layout = 'print';
        $model = $this->findModel($id);
        $modelDetail = AuditDetail::find()->where(['audit_id' => $id, 'flag_del' => 0])->all();

        return $this->render('print', [
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

    public function actionCancel($id, $mess)
    {
        $model = $this->findModel($id);
        $model->status = '3';
        $model->note = $model->note . ' ยกเลิกโดย ' . Yii::$app->user->identity->username . ' เนื่องจาก ' . $mess;
        $text = 'เอกสารเลขที่ ' . $model->billno . ' ถูกยกเลิกโดย ' . Yii::$app->user->identity->fullname . ' เนื่องจาก ' . $mess;
        $text .= 'url: https://wdw.appkm.com/site/approve';

        Yii::$app->helpers->LineNotify($text);
        $model->save(false);
        return $this->redirect(['index']);
    }

    public function actionAppv($id)
    {
        $model = $this->findModel($id);
        $model->status = '0';
        $model->note = $model->note . '/n อนุมัติโดย ' . Yii::$app->user->identity->username . ' เมื่อ ' . date('Y-m-d H:i:s');
        $model->save(false);
        return $this->redirect(['index']);
    }



    public function actionConfirm($id)
    {
        $model = $this->findModel($id);
        $model->status = '1';
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
    public function actionView($id)
    {
        $this->layout = 'blank';
        $model = $this->findModel($id);
        $modelDetail = AuditDetail::find()->where(['audit_id' => $id, 'flag_del' => 0])->all();

        return $this->render('view', [
            'model' => $model,
            'modelDetail' => $modelDetail
        ]);
    }
    public function actionUpdateDetail($id)
    {
        $model = AuditDetail::findOne(['id' => $id]);
        return $this->render('_form_detail', [
            'model' => $model,
        ]);
    }
}
