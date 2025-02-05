<?php

namespace app\controllers;

use app\models\Employee;
use app\models\ItemCategory;
use app\models\ItemPart;
use app\models\ItemUom;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use app\models\ItemBarcode;
/*
 * EmployeeController implements the CRUD actions for Employee model.
 */

class ProductController extends Controller
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
    public function actionTypeIndex()
    {
        $model = ItemCategory::find()->where(['flag_del' => 0])->all();
        return $this->render('type-index', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new ItemPart();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                $model->img = $model->upload($model, 'img');
                $model->save(false);
                $modelItemBarcode = new ItemBarcode();
                $modelItemBarcode->item_id = $model->id;
                $modelItemBarcode->barcode = 'N' . $model->id;
                $modelItemBarcode->uom_id = $model->uom_id;
                $modelItemBarcode->unit = '1';
                $modelItemBarcode->save(false);
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    public function actionTypeCreate()
    {
        $model = new ItemCategory();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                $model->save(false);
                return $this->redirect(['type-index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('_type-form', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                $model->img = $model->upload($model, 'img');
                $model->save(false);
                return $this->redirect(['index', 'minor_id' => $model->category_id]);
            }
        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    public function actionTypeUpdate($id)
    {
        $model = ItemCategory::findOne($id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                $model->save(false);
                return $this->redirect(['type-index']);
            }
        }

        return $this->render('_type-form', [
            'model' => $model,
        ]);
    }


    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->flag_del = 1;
        $model->save(false);

        $modelItemBarcode = ItemBarcode::find()->where(['item_id' => $model->id])->all();
        foreach ($modelItemBarcode as $item) {
            $item->flag_del = 1;
            $item->save(false);
        }

        return $this->redirect(['index']);
    }

    public function actionDeleteBarcode($id)
    {
        $model = ItemBarcode::findOne($id);
        $model->flag_del = 1;
        $model->save(false);
        return $this->redirect(['update', 'id' => $model->item_id]);
    }
    public function actionDeletefind()
    {
        $model = ItemPart::find()->where(['flag_del' => 1])->all();
        foreach ($model as $item) {
            $modelItemBarcode = ItemBarcode::find()->where(['item_id' => $item->id])->all();
            foreach ($modelItemBarcode as $item) {
                $item->flag_del = 1;
                $item->save(false);
            }
        }
        //return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = ItemPart::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionList()
    {
        $model = ItemPart::find()->where(['flag_del' => 0])->all();
        $data = [];
        foreach ($model as $item) {
            $data[] = [
                'id' => $item->id,
                'name' => $item->part_name,
                'code' => $item->part_code,
                'category' => $item->category->name,
                'uom' => $item->uom->uom_name,
                'img' => $item->img
            ];
        }
        echo header('Content-type: application/json');
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function actionUpdateItemBarcodeAll()
    {
        // $model = ItemPart::find()->where(['flag_del' => 0])->all();
        // foreach ($model as $item) {
        // $modelItemBarcode = ItemBarcode::find()->where(['item_id' => $item->id])->one();
        // if ($modelItemBarcode == null) {
        //     $modelItemBarcode = new ItemBarcode();
        //     $modelItemBarcode->item_id = $item->id;
        //     $modelItemBarcode->barcode = $item->id;
        //     $modelItemBarcode->uom_id = $item->uom_id;
        //     $modelItemBarcode->unit = '1';
        //     $modelItemBarcode->save(false);
        //   }
        //  $updateMoldel = $this->findModel($item->id);
        //   $updateMoldel->part_code = 'P' . $item->id;
        //  $updateMoldel->save(false);
        //  }

        //    $model = ItemBarcode::find()->where(['uom_id' => '24'])->all();
        ///  foreach ($model as $item) {
        //    echo $item->id . '<br>';
        //   $modelItemBarcode = new ItemBarcode();
        //   $modelItemBarcode->item_id = $item->item_id;
        //   $modelItemBarcode->barcode = 'kk' . $item->barcode;
        //   $modelItemBarcode->uom_id = '14';
        //   $modelItemBarcode->unit = '1000';
        //   $modelItemBarcode->save(false);

        //   $item->unit = '1';
        // $item->save(false);
        // }
    }
}
