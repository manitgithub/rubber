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
use yii\rbac\Item;

use app\models\ItemPart;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class RequisitionController extends Controller
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
            ->where(['type' => 'R', 'flag_del' => 0])
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
    public function actionCreate($department_id, $po_id = null)
    {
        $model = new AuditHeader();
        $modelDetail = [];
        $model->billno = Yii::$app->helpers->generateAuditCode('R');
        $model->type = 'R';
        $model->date_action = date('Y-m-d H:i');
        $model->status = '2';
        $model->department_id = $department_id;
        $model->partner_id = '';
        $model->note = '';

        if ($po_id) {
            $modelPO = AuditHeader::findOne(['id' => $po_id]);
            $model->note = 'อ้างอิงจากใบสั่งซื้อ เลขที่ #' . $modelPO->billno;
            $modelDetail = AuditDetail::find()->where(['audit_id' => $modelPO->id, 'flag_del' => 0])->all();
        }
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                $model->date = date('Y-m-d H:i:s');
                $dataDetail = [];

                if ($this->request->post('NewAuditDetail')) {
                    foreach ($this->request->post('NewAuditDetail') as $detail) {
                        $dataDetail[$detail['depart_id']]['data'][] = [
                            'part_id' => $detail['part_id'],
                            'audit_txt' => $detail['audit_txt'],
                            'unit_price' => null,
                            'qty_uom' => $detail['qty'],
                            'qty' => (int) $detail['qty_uom'] * (int) $detail['qty'],
                            'total_amount' => $detail['total_amount'],
                            'uom_id' => $detail['uom_id'],
                            'expdate' => null,
                        ];
                    }
                }

                $rowId = 1;
                foreach ($dataDetail as $depart_id => $dataList) {
                    $modelHeader = $model;
                    $modelHeader = new AuditHeader();
                    $modelHeader->billno = $model->billno . '/' . $rowId;
                    $modelHeader->type = $model->type;
                    $modelHeader->date_action = $model->date_action;
                    $modelHeader->date = $model->date;
                    $modelHeader->status = $model->status;
                    $modelHeader->department_id = $model->department_id;
                    $modelHeader->partner_id = $depart_id;
                    $modelHeader->note = $model->note;
                    $modelHeader->refno = $model->refno;
                    $modelHeader->save(false);

                    foreach ($dataList['data'] as $detail) {
                        $modelDetailNew = new AuditDetail();
                        $modelDetailNew->audit_id = $modelHeader->id;
                        $modelDetailNew->part_id = $detail['part_id'];
                        $modelDetailNew->audit_txt = $detail['audit_txt'];
                        $modelDetailNew->unit_price = ItemPart::findOne($detail['part_id'])->unit_price;
                        $modelDetailNew->qty_uom = $detail['qty_uom'];
                        $modelDetailNew->qty = $detail['qty'];
                        $modelDetailNew->total_amount = ItemPart::findOne($detail['part_id'])->unit_price * $detail['qty_uom'];
                        $modelDetailNew->uom_id = $detail['uom_id'];
                        $modelDetailNew->expdate = empty($detail['expdate']) ? '' : Yii::$app->helpers->dateTHtoEN($detail['expdate']);
                        if ($model->department_id == '15') {
                            $modelDetailNew->bom = 1;
                        }


                        $modelDetailNew->save(false);
                    }

                    $rowId++;
                }

                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('_create', [
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
        $model->note = $model->note . ' ยกเลิกโดย ' . Yii::$app->user->identity->fullname . ' เนื่องจาก ' . $mess;
        $model->save(false);
        $text = 'เอกสารเลขที่ ' . $model->billno . ' ถูกยกเลิกโดย ' . Yii::$app->user->identity->fullname . ' เนื่องจาก ' . $mess;
        $text .= 'url: https://wdw.appkm.com/site/approve';

        Yii::$app->helpers->LineNotify($text);

        return $this->redirect(['index']);
    }
    public function actionAppv($id)
    {
        $model = $this->findModel($id);
        $model->status = '0';
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


    public function actionCallStock($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // $modelList = Yii::$app->db->createCommand("SELECT id, part_name as text  FROM item_part")->queryAll();
        $modelList = Yii::$app->db->createCommand("SELECT
        detail.id,
        detail.item_id,
        detail.barcode,
        detail.uom_id,
        detail.unit,
        item.part_name,
        uom.uom_name,
        uom_main.uom_name AS uom_name_main,
        depart.department,
        depart.id AS depart_id,
        COALESCE(SUM(
                CASE 
                    WHEN header.type = 'S' THEN log.qty 
                    WHEN header.type = 'R' THEN -log.qty 
                    WHEN header.type = 'M' THEN log.qty
                    WHEN header.type = 'T' AND header.department_id = depart.id AND header.department_id != header.partner_id THEN log.qty 
                    WHEN header.type = 'T' AND header.partner_id = depart.id AND header.department_id != header.partner_id THEN -log.qty 
                    ELSE 0 
                END), 0) AS stock
        FROM item_barcode AS detail
        JOIN audit_department AS depart ON 1=1
        JOIN item_part AS item ON item.id = detail.item_id 
        JOIN item_uom AS uom ON uom.id = detail.uom_id 
        JOIN item_uom AS uom_main ON uom_main.id = item.uom_id 
        LEFT JOIN audit_detail AS log ON log.part_id = detail.item_id AND log.flag_del = 0
        LEFT JOIN audit_header AS header ON header.id = log.audit_id 
            AND (
                (header.type IN ('S','T','M') AND header.department_id = depart.id) 
                OR (header.type IN ('R','T') AND header.partner_id = depart.id)
            )
            AND header.status = 1 AND header.flag_del = 0
        WHERE detail.flag_del = 0 AND item.flag_del = 0 AND item.part_name like '%" . $q . "%'
        GROUP BY 
            detail.id,
            detail.item_id,
            detail.barcode,
            detail.uom_id,
            detail.unit,
            item.part_name,
            uom.uom_name,
            uom_main.uom_name,
            depart.department,
            depart.id
        HAVING stock > 0")->queryAll();

        return ['results' => $modelList];
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
}
