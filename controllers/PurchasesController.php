<?php

namespace app\controllers;

use app\models\Purchases;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * PurchasesController implements the CRUD actions for Purchases model.
 */
class PurchasesController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Purchases models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Purchases::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Purchases model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Purchases model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Purchases();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                // Set the status to 'active' after saving
                Yii::$app->session->setFlash('success');
                return $this->redirect(['create', 'msg' => 'success', 'date' => $model->date]);
        } 
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Purchases model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                            Yii::$app->session->setFlash('success');

            return $this->redirect(['create', 'id' => $model->id, 'date' => $model->date]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Purchases model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
    //    $this->findModel($id);
        $model = Purchases::findOne($id);
        $model->flagdel = 1;
        $model->save(false);
        Yii::$app->session->setFlash('delete');

        return $this->redirect(['create']);
    }

    /**
     * Finds the Purchases model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Purchases the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchases::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPayment()
        {
    $startDate = Yii::$app->request->get('start_date');
    $endDate = Yii::$app->request->get('end_date');

    $query = \app\models\Purchases::find()
        ->andFilterWhere(['>=', 'date', $startDate])
        ->andFilterWhere(['<=', 'date', $endDate])
        ->andFilterWhere(['flagdel' => 0])
        ->andFilterWhere(['status' => 'UNPAID'])
        ->orderBy(['member_id' => SORT_ASC, 'date' => SORT_ASC]);

    $payments = $query->all();

    $groupedPayments = [];
    foreach ($payments as $payment) {
        $groupedPayments[$payment->member_id][] = $payment;
    }

    return $this->render('payment', [
        'groupedPayments' => $groupedPayments,
        'startDate' => $startDate,
        'endDate' => $endDate,
    ]);
    }

public function actionRunAllReceipts($start_date = null, $end_date = null)
{
    if (empty($start_date) || empty($end_date)) {
        Yii::$app->session->setFlash('error', 'กรุณาระบุช่วงวันที่ให้ครบถ้วน');
        return $this->redirect(['purchases/payment']);
    }

    // ดึงรายการที่ยังไม่มีใบเสร็จ (เช็คว่ารายการยังไม่ถูกเชื่อมกับ receipts)
    $purchases = \app\models\Purchases::find()
        ->where(['between', 'date', $start_date, $end_date])
        ->andWhere(['flagdel' => 0])
        ->andWhere(['status' => 'UNPAID'])
        ->andWhere(['receipt_id' => null])
        ->orderBy(['member_id' => SORT_ASC, 'date' => SORT_ASC])
        ->all();

    $grouped = [];
    foreach ($purchases as $p) {
        $grouped[$p->member_id][] = $p;
    }

    $book = \app\models\ReceiptBook::find()->where(['is_active' => 1])->one();
    if (!$book) {
        Yii::$app->session->setFlash('error', 'ไม่พบเล่มใบเสร็จที่กำลังใช้งาน');
        return $this->redirect(['purchases/payment']);
    }

    $countReceipts = 0;

    foreach ($grouped as $memberId => $list) {
        // รันใบเสร็จใหม่ให้แต่ละคน
        $book->running_no += 1;

        $receipt = new \app\models\Receipt();
        $receipt->member_id = $memberId;
        $receipt->book_no = $book->book_no;
        $receipt->running_no = $book->running_no;
        $receipt->receipt_date = date('Y-m-d');
        $receipt->total_amount = array_sum(array_map(fn($p) => $p->total_amount, $list));
        $receipt->created_by = Yii::$app->user->id;
        $receipt->created_at = date('Y-m-d H:i:s');
        $receipt->start_date = $start_date;
        $receipt->end_date = $end_date;
        $receipt->date = date('Y-m-d');
        $receipt->save(false);

        foreach ($list as $p) {
            $p->receipt_id = $receipt->id;
            $p->status = 'PAID';
            $p->save(false);
        }

        $countReceipts++;
    }

    $book->updated_at = date('Y-m-d H:i:s');
    $book->save(false);

    Yii::$app->session->setFlash('success', "สร้างใบเสร็จเรียบร้อยแล้ว ($countReceipts ใบ)");
    return $this->redirect(['purchases/payment', 'start_date' => $start_date, 'end_date' => $end_date]);
}

public function actionPrintAllBills($filter_date = null, $book_no = null, $run_no = null, $member_id = null)
{
    $this->layout = 'print';
    $query = \app\models\Receipt::find();

    if ($filter_date) {
        $query->andWhere(['DATE(receipt_date)' => $filter_date]);
    }
    if ($book_no) {
        $query->andWhere(['book_no' => $book_no]);
    }
    if ($run_no) {
        $query->andWhere(['running_no' => $run_no]);
    }
    if ($member_id) {
        $query->andWhere(['member_id' => $member_id]);
    }

    $receipts = $query->orderBy(['member_id' => SORT_ASC])->all();

    return $this->render('print-all-bills', [
        'receipts' => $receipts,
    ]);
}

public function actionViewMemberItems($member_id, $start_date, $end_date)
{
    $this->layout = 'blank';
        $member = \app\models\Members::findOne($member_id);

    $purchases = \app\models\Purchases::find()
        ->where(['member_id' => $member_id])
        ->andWhere(['between', 'date', $start_date, $end_date])
        ->andWhere(['status' => 'UNPAID'])
        ->andWhere(['receipt_id' => null])
        ->orderBy(['date' => SORT_ASC])
        ->all();

    return $this->render('_member_items', [
        'member' => $member,
        'purchases' => $purchases,
        'startDate' => $start_date,
        'endDate' => $end_date,
    ]);
}

public function actionBill($filter_date = null, $book_no = null, $run_no = null, $member_id = null)
{
    // ถ้าไม่มีการกรองวันที่ ให้ใช้วันที่ปัจจุบัน
    if (empty($filter_date)) {
        $filter_date = date('Y-m-d');
    }

    $query = \app\models\Receipt::find()
        ->joinWith(['member', 'purchases'])
        ->orderBy(['receipt_date' => SORT_ASC]);

    if ($filter_date) {
        $query->andWhere(['DATE(receipt_date)' => $filter_date]);
    }

    if (!empty($book_no)) {
        $query->andWhere(['book_no' => $book_no]);
    }

    if (!empty($run_no)) {
        $query->andWhere(['running_no' => $run_no]);
    }

    if (!empty($member_id)) {
        $query->andWhere(['member_id' => $member_id]);
    }

    $receipts = $query->all();

    return $this->render('bill', [
        'receipts' => $receipts,
        'filterDate' => $filter_date,
        'bookNo' => $book_no,
        'runNo' => $run_no,
        'memberId' => $member_id,
    ]);
}

public function actionPrintBill($id)
{
    $this->layout = 'print';
    $receipt = \app\models\Receipt::find()
        ->with(['member', 'purchases'])
        ->where(['id' => $id])
        ->one();

    if (!$receipt) {
        throw new NotFoundHttpException("ไม่พบใบเสร็จนี้");
    }

    return $this->render('print-bill', [
        'receipt' => $receipt,
    ]);
}


}


