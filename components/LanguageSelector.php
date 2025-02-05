<?php

namespace app\components;

use app\models\Employee;
use app\models\User;
use Yii;
use yii\base\BootstrapInterface;
use yii\web\Cookie;
use yii\base\Exception;

class LanguageSelector implements BootstrapInterface
{
    public $supportedLanguages = [];

    public function bootstrap($app)
    {
        $app->language = 'th';
        if (!empty(Yii::$app->user->id)) {
            $identity = Employee::findOne(['id' => Yii::$app->user->id]);
            if ($identity) {
                $identity->updateAttributes(['updated_at' => date('Y-m-d H:i:s')]);
            }
        }
    }
}
