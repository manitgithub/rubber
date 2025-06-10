<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "receipt_books".
 *
 * @property int $id
 * @property string $book_number
 * @property int $start_number
 * @property int $end_number
 * @property int $current_number
 * @property int|null $is_active
 * @property string|null $created_at
 */
class ReceiptBook extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receipt_books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_number', 'end_number'], 'required'],
            [['start_number', 'end_number', 'current_number', 'is_active'], 'integer'],
            [['created_at'], 'safe'],
            [['book_number'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_number' => 'Book Number',
            'start_number' => 'Start Number',
            'end_number' => 'End Number',
            'current_number' => 'Current Number',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
        ];
    }
}
