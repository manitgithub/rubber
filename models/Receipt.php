<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "receipts".
 *
 * @property int $id
 * @property int $member_id
 * @property string $book_no
 * @property int $running_no
 * @property string $receipt_no
 * @property string $receipt_date
 * @property float|null $total_amount
 * @property int|null $created_by
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Purchases[] $purchases
 */
class Receipt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receipts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'book_no', 'running_no', 'receipt_no', 'receipt_date'], 'required'],
            [['member_id', 'running_no', 'created_by'], 'integer'],
            [['receipt_date', 'created_at', 'updated_at'], 'safe'],
            [['total_amount'], 'number'],
            [['book_no'], 'string', 'max' => 10],
            [['receipt_no'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'book_no' => 'Book No',
            'running_no' => 'Running No',
            'receipt_no' => 'Receipt No',
            'receipt_date' => 'Receipt Date',
            'total_amount' => 'Total Amount',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Purchases]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurchases()
    {
        return $this->hasMany(Purchases::class, ['receipt_id' => 'id']);
    }
public function getMember()
{
    return $this->hasOne(Members::class, ['id' => 'member_id']);
}



}
