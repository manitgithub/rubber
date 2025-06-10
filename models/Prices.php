<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prices".
 *
 * @property int $id
 * @property string $date
 * @property float $price
 * @property string $created_at
 * @property int|null $flagdel
 */
class Prices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'price'], 'required'],
            [['date', 'created_at'], 'safe'],
            [['price'], 'number'],
            [['flagdel'], 'integer'],
            [['date'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'วันที่',
            'price' => 'ราคา',
            'created_at' => 'Created At',
            'flagdel' => 'Flagdel',
        ];
    }
}
