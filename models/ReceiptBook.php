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
            [['book_number', 'start_number', 'end_number'], 'required'],
            [['start_number', 'end_number', 'current_number', 'is_active'], 'integer'],
            [['created_at'], 'safe'],
            [['book_number'], 'string', 'max' => 10],
            [['start_number'], 'integer', 'min' => 1],
            [['end_number'], 'integer', 'min' => 1],
            [['end_number'], 'compare', 'compareAttribute' => 'start_number', 'operator' => '>=', 'message' => 'หัวเลขสิ้นสุดต้องมากกว่าหรือเท่ากับหัวเลขเริ่มต้น'],
            [['current_number'], 'default', 'value' => function($model) {
                return $model->start_number;
            }],
            [['is_active'], 'boolean'],
            [['is_active'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_number' => 'เลขที่เล่มใบเสร็จ',
            'start_number' => 'เลขเริ่มต้น',
            'end_number' => 'เลขสิ้นสุด',
            'current_number' => 'เลขปัจจุบัน',
            'is_active' => 'สถานะการใช้งาน',
            'created_at' => 'วันที่สร้าง',
        ];
    }

    /**
     * ก่อนบันทึกข้อมูล
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
                if (empty($this->current_number)) {
                    $this->current_number = $this->start_number;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * ดึงเลขใบเสร็จถัดไป
     */
    public function getNextReceiptNumber()
    {
        if ($this->current_number > $this->end_number) {
            return null; // เล่มหมดแล้ว
        }
        
        $nextNumber = $this->current_number;
        $this->current_number++;
        $this->save(false);
        
        return $this->book_number . sprintf('%06d', $nextNumber);
    }

    /**
     * ตรวจสอบว่าเล่มหมดแล้วหรือยัง
     */
    public function isFinished()
    {
        return $this->current_number > $this->end_number;
    }

    /**
     * ดึงจำนวนใบเสร็จที่เหลือ
     */
    public function getRemainingCount()
    {
        return max(0, $this->end_number - $this->current_number + 1);
    }

    /**
     * ดึงจำนวนใบเสร็จที่ใช้ไปแล้ว
     */
    public function getUsedCount()
    {
        return $this->current_number - $this->start_number;
    }

    /**
     * ดึงเปอร์เซ็นต์การใช้งาน
     */
    public function getUsagePercentage()
    {
        $total = $this->end_number - $this->start_number + 1;
        $used = $this->getUsedCount();
        return $total > 0 ? round(($used / $total) * 100, 2) : 0;
    }

    /**
     * ดึงเล่มใบเสร็จที่ใช้งานอยู่
     */
    public static function getActiveBook()
    {
        return self::find()->where(['is_active' => 1])->andWhere(['<=', 'current_number', new \yii\db\Expression('end_number')])->one();
    }

    /**
     * เปิดใช้งานเล่มนี้และปิดเล่มอื่น
     */
    public function activate()
    {
        // ปิดการใช้งานเล่มอื่นทั้งหมด
        self::updateAll(['is_active' => 0]);
        
        // เปิดการใช้งานเล่มนี้
        $this->is_active = 1;
        return $this->save(false);
    }
}
