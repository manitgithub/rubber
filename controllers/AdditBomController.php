<?php

namespace app\controllers;

use app\models\AdditBom;
use app\models\AuditDetail;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ItemBarcode;
use app\models\AuditHeader;
use app\models\ItemPart;

use Yii;
use yii\rbac\Item;

/**
 * AdditBomController implements the CRUD actions for AdditBom model.
 */
class AdditBomController extends Controller
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
     * Lists all AdditBom models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AdditBom::find(),
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
     * Displays a single AdditBom model.
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
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * Creates a new AdditBom model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AdditBom();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $barcode = ItemBarcode::findOne($this->request->post('AdditBom')['barcodeID']);
                $model->item_out = $barcode->item_id;
                $model->item_out_uom = $barcode->uom_id;

                $updateDetail = AuditDetail::find()->where(['id' => $model->bill_id])->one();
                $updateDetail->bom = 2;
                $updateDetail->save(false);

                $model->save(false);

                //add item to stock
                $modelH = new AuditHeader();
                $modelH->billno = Yii::$app->helpers->generateAuditCode('S');
                $modelH->type = 'S';
                $modelH->date =  date('Y-m-d H:i:s');
                $modelH->status = '1';
                $modelH->partner_id = '90';
                $modelH->department_id = $model->depart_id;
                $modelH->note = 'รับมาจากคลังผลิต #' . sprintf("%06d", $model->id);
                $modelH->exp = date('Y-m-d', strtotime('+7 days'));
                $modelH->refno = sprintf("%06d", $model->id);
                $modelH->save(false);

                $modelDetailNew = new AuditDetail();
                $modelDetailNew->audit_id = $modelH->id;
                $modelDetailNew->part_id = ItemPart::findOne($model->item_out)->id;
                $modelDetailNew->audit_txt = ItemPart::findOne($model->item_out)->part_name;
                $modelDetailNew->unit_price = $this->request->post('AdditBom')['total'];
                $modelDetailNew->qty_uom = $model->qty_out;
                $modelDetailNew->qty = $model->qty_out;
                $modelDetailNew->total_amount = $model->qty_out * $this->request->post('AdditBom')['total'];
                $modelDetailNew->uom_id = $model->item_out_uom;
                //+7 day
                $modelDetailNew->expdate = date('Y-m-d', strtotime('+7 days'));
                $modelDetailNew->save(false);

                //อัพเดทราคาสินค้า
                $modelItem = ItemPart::findOne($model->item_out);
                $modelItem->unit_price = $this->request->post('AdditBom')['total'];
                $modelItem->save(false);


                return $this->redirect(['update', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionLoss()
    {
        $model = new AdditBom();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->item_out = $_GET['item_in'];
                $model->item_out_uom = $_GET['uom_id'];
                $model->save(false);

                //add item to stock
                $modelH = new AuditHeader();
                $modelH->billno = Yii::$app->helpers->generateAuditCode('R');
                $modelH->type = 'R';
                $modelH->date = date('Y-m-d H:i:s');
                $modelH->status = '1';
                $modelH->partner_id = $model->depart_id;
                $modelH->department_id = $model->depart_id;
                $modelH->note = $this->request->post('AdditBom')['remark'];
                $modelH->refno = sprintf("%06d", $model->id);
                $modelH->save(false);

                $modelDetailNew = new AuditDetail();
                $modelDetailNew->audit_id = $modelH->id;
                $modelDetailNew->part_id = ItemPart::findOne($model->item_out)->id;
                $modelDetailNew->audit_txt = ItemPart::findOne($model->item_out)->part_name;
                $modelDetailNew->unit_price = $this->request->post('AdditBom')['total'];
                $modelDetailNew->qty_uom =  $model->lost;
                $modelDetailNew->qty =  $model->lost;
                $modelDetailNew->uom_id = $model->item_out_uom;
                $modelDetailNew->total_amount = $model->qty_out * $this->request->post('AdditBom')['total'];

                //+7 day
                $modelDetailNew->save(false);

                //อัพเดทราคาสินค้า
                $modelItem = ItemPart::findOne($model->item_out);
                $modelItem->unit_price = $this->request->post('AdditBom')['total'];
                $modelItem->save(false);


                return $this->redirect(['requisition/update', 'id' => $modelH->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('loss', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing AdditBom model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AdditBom model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AdditBom model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return AdditBom the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdditBom::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
