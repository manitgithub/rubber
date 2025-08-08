<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "purchases".
 *
 * @property int $id
 * @property string $receipt_number
 * @property string $date
 * @property string $member_id
 * @property float $weight
 * @property float $percentage
 * @property float $dry_weight
 * @property float $price_per_kg
 * @property float $total_amount
 * @property string|null $status
 * @property string $created_at
 * @property int|null $user_at
 * @property int|null $flagdel
 */
class Purchases extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchases';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['receipt_number', 'date', 'member_id', 'weight', 'percentage', 'dry_weight', 'price_per_kg', 'total_amount'], 'required'],
            [['date', 'created_at'], 'safe'],
            [['weight', 'percentage', 'dry_weight', 'price_per_kg', 'total_amount'], 'number'],
            [['user_at','receipt_id', 'flagdel'], 'integer'],
            [['receipt_number'], 'string', 'max' => 50],
            //[['receipt_number'], 'unique', 'message' => 'เลขที่รับนี้มีอยู่แล้ว'],
            [['member_id'], 'string', 'max' => 36],
            [['status'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'receipt_number' => 'เลขที่รายการ',
            'date' => 'วันที่',
            'member_id' => 'สมาชิก',
            'weight' => 'น้ำหนัก',
            'percentage' => 'เปอร์เซ็นต์',
            'dry_weight' => 'เนื้อยางแห้ง',
            'price_per_kg' => 'ราคา (บาท/กก.)',
            'total_amount' => 'ยอดรวม',
            'status' => 'สถานะ',
            'created_at' => 'Created At',
            'user_at' => 'User At',
            'flagdel' => 'Flagdel',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
                $this->flagdel = 0;
            }
            return true;
        }
        return false;
    }

    public function getMembers()
    {
        return $this->hasOne(Members::className(), ['id' => 'member_id']);
    }
    public function getReceipt()
{
    return $this->hasOne(Receipt::class, ['id' => 'receipt_id']);
}

}
