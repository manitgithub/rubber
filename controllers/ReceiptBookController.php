<?php

namespace app\controllers;

use Yii;
use app\models\ReceiptBook;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ReceiptBookController implements the CRUD actions for ReceiptBook model.
 */
class ReceiptBookController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'activate' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ReceiptBook models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ReceiptBook::find()->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReceiptBook model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ReceiptBook model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReceiptBook();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'เพิ่มเล่มใบเสร็จเรียบร้อยแล้ว');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ReceiptBook model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'แก้ไขข้อมูลเล่มใบเสร็จเรียบร้อยแล้ว');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ReceiptBook model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        // ตรวจสอบว่าเล่มนี้กำลังใช้งานอยู่หรือไม่
        if ($model->is_active) {
            Yii::$app->session->setFlash('error', 'ไม่สามารถลบเล่มใบเสร็จที่กำลังใช้งานอยู่ได้');
            return $this->redirect(['index']);
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'ลบเล่มใบเสร็จเรียบร้อยแล้ว');

        return $this->redirect(['index']);
    }

    /**
     * เปิดใช้งานเล่มใบเสร็จ
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->isFinished()) {
            Yii::$app->session->setFlash('error', 'ไม่สามารถเปิดใช้งานเล่มใบเสร็จที่หมดแล้วได้');
            return $this->redirect(['index']);
        }

        if ($model->activate()) {
            Yii::$app->session->setFlash('success', 'เปิดใช้งานเล่มใบเสร็จ ' . $model->book_number . ' แล้ว');
        } else {
            Yii::$app->session->setFlash('error', 'ไม่สามารถเปิดใช้งานเล่มใบเสร็จได้');
        }

        return $this->redirect(['index']);
    }

    /**
     * ดึงสถิติการใช้งาน
     * @return mixed
     */
    public function actionStats()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $activeBook = ReceiptBook::getActiveBook();
        $totalBooks = ReceiptBook::find()->count();
        $finishedBooks = ReceiptBook::find()->where(['>', 'current_number', new \yii\db\Expression('end_number')])->count();

        return [
            'activeBook' => $activeBook ? [
                'book_number' => $activeBook->book_number,
                'remaining' => $activeBook->getRemainingCount(),
                'usage_percentage' => $activeBook->getUsagePercentage(),
            ] : null,
            'totalBooks' => $totalBooks,
            'finishedBooks' => $finishedBooks,
            'availableBooks' => $totalBooks - $finishedBooks,
        ];
    }

    /**
     * Finds the ReceiptBook model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReceiptBook the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReceiptBook::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
