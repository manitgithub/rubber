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
                'actions' => [
                    'save-data' => ['POST'],
                ],
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

        $fields = [
            //'_id',
            'first_name',
            'last_name',
            'email',
            'gender',
            'participant_telephone',
            'birthDate',
            'age_category',
            'bib_number',
            'health_issues',
            'emergency_contact',
            'emergency_contact_relationship',
            'emergency_contact_phone',
            'province',
            'nationalId',
            'shirt',
            'shirt_type',
            'reg_deliver_option',
            'reg_deliver_to_street',
            'reg_deliver_to_locality',
            'reg_deliver_to_city',
            'reg_deliver_to_recipient',
            'reg_deliver_to_telephone',
            'reg_deliver_to_postalCode',
            'reg_deliver_to_address',
            'reg_deliver_to_province',
            'remarks',
            'delivery',
            'deliver_to_recipient',
            'deliver_to_address',
            'reg_status',
            'group_name',
            'registration_date',
            'registration_type',
            'ticket_type',
            'race',
            'price',
            'user_code',
            'payment_method',
            'payment_amount',
            'payment_date',
            'payment_reference',
            'registration_id',
            'participant_id',
            'payment_id',
            'codes',
            'tracking_code',
            'registration_code',
            'start_date',
            'ticket_code',
            'image_other_url',
            'pickup_status',
            'pickup_id',
            'pickup_at'
        ];



        foreach ($rows as $index => $row) {
            if (count($row) !== count($fields)) {
                Yii::debug(["Row Index" => $index, "Row Data" => $row, "Expected Fields" => count($fields)], 'debug');
                file_put_contents('debug.log', json_encode(["Row Index" => $index, "Row Data" => $row, "Expected Fields" => count($fields)], JSON_PRETTY_PRINT), FILE_APPEND);
                continue;
            }
            if ($index === 0 || (isset($row[0]) && $row[0] === '_id')) {
                continue;
            }

            if (count($row) !== count($fields)) {
                //    var_dump($row);
                //  exit;
                $errors[] = ["row" => $index + 1, "message" => "Data does not match the expected structure"];
                continue;
            }

            $rowData = array_combine($fields, $row);
            $model = new Participants();
            $model->attributes = $rowData;

            if (!$model->save()) {
                $errors[] = ["row" => $index + 1, "errors" => $model->errors];
            }
        }

        if (!empty($errors)) {
            return ['status' => 'error', 'errors' => $errors];
        }

        return ['status' => 'success', 'message' => 'Data saved successfully'];
    }
}
