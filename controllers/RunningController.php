<?php

namespace app\controllers;

use app\models\Running;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Participants;
use Yii;

/**
 * RunningController implements the CRUD actions for Running model.
 */
class RunningController extends Controller
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
     * Lists all Running models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Running::find(),
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
     * Displays a single Running model.
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
    public function actionPickup($id)
    {
        return $this->render('pickup', [
            'model' => $this->findModel($id),
        ]);
    }
    /**
     * Creates a new Running model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Running();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->img = $model->upload($model, 'img', 'img');
                $model->save(false);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Running model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->img = $model->upload($model, 'img', 'img');
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Running model.
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

    public function actionUpdateparticipant()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $nationId = $request->get('nationId');
        $runningId = $request->get('runningId');

        // ค้นหาผู้เข้าร่วมที่มี nationalId และ runningId ที่ตรงกันทั้งหมด
        $participants = Participants::findAll(['nationalId' => $nationId, 'runningid' => $runningId]);

        if ($participants) {
            $updatedParticipants = [];
            $pick = 0;

            foreach ($participants as $participant) {
                if ($participant->status == 1) {
                    $updatedParticipants[] = $participant;
                    $pick = 1;

                    continue; // ข้ามรายการที่มีสถานะเป็น 1 (รับของไปแล้ว)
                }
                //$participant->status = 1;
                //$participant->picktime = date('Y-m-d H:i:s');
                //$participant->save(false);

                $updatedParticipants[] = $participant;
            }

            if ($pick == 0) {
                return ['status' => 'success', 'message' => 'ยืนยันการรับของ', 'data' => $updatedParticipants];
            } else {
                return ['status' => 'error', 'message' => 'รับแล้ว หากข้อมูลไม่ถูกต้องให้ติตต่อ Admintrator เพื่อแก้ไขข้อมูล', 'data' => $updatedParticipants];
            }
        } else {
            return ['status' => 'error', 'message' => 'ไม่พบข้อมูล', 'data' => null];
        }
    }

    public function actionShowRunning($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $sql = "SELECT SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS pick, SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) AS notpick FROM participants WHERE runningid = $id;";
        $result = Yii::$app->db->createCommand($sql)->queryOne();
        $report = [];
        $report['main'] = $result;
        /** 
        $sql2 = "SELECT SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS pick, SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) AS notpick,age_category FROM participants WHERE runningid = $id GROUP BY age_category;";
        $result2 = Yii::$app->db->createCommand($sql2)->queryAll();

        $report['age'] = $result2;
         */
        return $report;
    }


    /**
     * Finds the Running model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Running the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Running::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
