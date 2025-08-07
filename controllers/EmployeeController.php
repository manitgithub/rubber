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
class EmployeeController extends Controller
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
        $model = Employee::find()->all();
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new Employee();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $role = [];
                foreach (Yii::$app->params['menu'] as $index => $menu) {
                    if (!empty($this->request->post('sel_' . $index))) {
                        $role[] = $index;
                    }
                }
                $model->role = implode(',', $role);

                if (!empty(Yii::$app->request->post('password'))) {
                    $model->setPassword(Yii::$app->request->post('password'));
                    $model->generateAuthKey();
                    $model->generateEmailVerificationToken();
                }
                $model->img = $model->upload($model, 'img');
                $model->save(false);
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $role = [];
            foreach (Yii::$app->params['menu'] as $index => $menu) {
                if (!empty($this->request->post()['sel_' . $index])) {
                    $role[] = $index;
                }
            }
            $model->role = implode(',', $role);

            if (!empty(Yii::$app->request->post('password'))) {
                $model->setPassword(Yii::$app->request->post('password'));
            }
            $model->img = $model->upload($model, 'img');
            $model->save(false);
            return $this->redirect(['index']);
        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }


    public function actionProfile()
    {
        $id = Yii::$app->user->identity->id;
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if (!empty(Yii::$app->request->post('password'))) {
                $model->setPassword(Yii::$app->request->post('password'));
            }
            $model->img = $model->upload($model, 'img');
            $model->save(false);
            return $this->redirect(['site/index']);
        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Employee::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
