<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\Participants;
use yii\web\Response;
use yii\filters\VerbFilter;

class RegistrationController extends Controller
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
            ],
        ];
    }

    public function actionSaveData()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $data = json_decode($request->getRawBody(), true); // ใช้ getRawBody() แทน post()

        if (empty($data) || !isset($data['rows'])) {
            return ['status' => 'error', 'message' => 'No valid data received'];
        }
        $rows = $data['rows'];
        $errors = [];
        $runningId = $data['running'];

        $mapFields = [
            'first_name' => 0,
            'last_name' => 1,
            'email' => 2,
            'gender' => 3,
            'participant_telephone' => 4,
            'birthDate' => 5,
            'age_category' => 6,
            'bib_number' => 7,
            'health_issues' => 9,
            'emergency_contact' => 10,
            'emergency_contact_relationship' => 11,
            'emergency_contact_phone' => 12,
            'province' => 13,
            'nationalId' => 14,
            'shirt' => 15,
            'shirt_type' => 16,
            'reg_deliver_option' => 17,
            'race' => 18,
            'ticket_type' => 19,
            'price' => 21,
            'ticket_code' => 22,
            'user_code' => 22,
            'start_date' => 23,
            'picktime' => 24,
        ];

        foreach ($rows as $index => $row) {
            // ข้ามแถวแรกถ้าข้อมูลอาจเป็น header
            if ($index === 0 || (isset($row[0]) && strtolower($row[0]) === '_id')) {
                continue;
            }

            if (!is_array($row)) {
                $errors[] = ["row" => $index + 1, "errors" => "Invalid row format"];
                continue;
            }

            $model = new Participants();

            foreach ($mapFields as $field => $position) {
                $model->$field = isset($row[$position]) ? trim($row[$position]) : null;
            }
            $model->runningid = $runningId;

            if (!$model->save(false)) {
                $errors[] = ["row" => $index + 1, "errors" => $model->errors];
            }
        }

        if (!empty($errors)) {
            return ['status' => 'error', 'errors' => $errors];
        }

        return ['status' => 'success', 'message' => 'Data saved successfully'];
    }
}
