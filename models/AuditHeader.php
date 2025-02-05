<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "audit_header".
 *
 * @property int $id
 * @property string|null $exp
 * @property string|null $date
 * @property string|null $partner_id
 * @property int|null $department_id
 * @property string|null $billno
 * @property string|null $status
 * @property string|null $type
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_user
 * @property int|null $updated_user
 * @property int|null $flag_del
 */
class AuditHeader extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'audit_header';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['id'], 'required'],
            [['id', 'department_id', 'created_user', 'updated_user', 'flag_del'], 'integer'],
            [['created_at', 'updated_at', 'date_action'], 'safe'],
            [['exp'], 'string', 'max' => 20],
            [['date'], 'string', 'max' => 50],
            [['partner_id'], 'string', 'max' => 100],
            [['note'], 'string', 'max' => 255],
            [['billno', 'refno'], 'string', 'max' => 30],
            [['status', 'type'], 'string', 'max' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'exp' => 'Exp',
            'date' => 'Date',
            'partner_id' => 'คู่ค้า/ผู้จัดจำหน่าย',
            'department_id' => 'คลังที่รับสินค้า',
            'billno' => 'หมายเลขอ้างอิง',
            'status' => 'สถานะ',
            'type' => 'Type',
            'date_action' => 'วันที่ส่งงาน',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_user' => 'Created User',
            'updated_user' => 'Updated User',
            'flag_del' => 'Flag Del',
            'note' => 'โน๊ต',
            'refno' => 'เลขที่อ้างอิง',
        ];
    }

    public function getTotal()
    {
        return AuditDetail::find()->where(['audit_id' => $this->id, 'flag_del' => 0])->sum('total_amount');
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
            $this->created_user = Yii::$app->user->identity->id;
            $this->updated_at = date('Y-m-d H:i:s');
            $this->updated_user = Yii::$app->user->identity->id;
            $this->flag_del = 0;
            $type = AuditType::findOne(['id' => $this->type]);
            $text = "มีการสร้างรายการ" . $type->name . " (#" . $this->billno . ")\nโดย " . Yii::$app->user->identity->fullname . '(' . Yii::$app->user->identity->position . ')';
            $lastid = AuditHeader::find()->max('id') + 1;
            if ($this->type == 'R') {
                $text .= 'url: https://wdw.appkm.com/requisition/view?id=' . $lastid;
            }
            if ($this->type == 'P') {
                $text .= 'url: https://wdw.appkm.com/purchase-orders/view?id=' . $lastid;
            }
            if ($this->type == 'S') {
                $text .= 'url: https://wdw.appkm.com/goods-receipts/view?id=' . $lastid;
            }
            Yii::$app->helpers->LineNotify($text);
        } else {
            $this->updated_at = date('Y-m-d H:i:s');
            $this->updated_user = Yii::$app->user->identity->id;
            if ($this->status == 0) {
                $type = AuditType::findOne(['id' => $this->type]);
                $text = "มีการยกเลิกรายการ" . $type->name . " (#" . $this->billno . ")\nโดย " . Yii::$app->user->identity->fullname . '(' . Yii::$app->user->identity->position . ')';
                Yii::$app->helpers->LineNotify($text);
            }
        }

        return parent::beforeSave($insert);
    }

    public function getDocument()
    {
        $type = $this->type;
        if ($type == 'R') {
            return 'ใบเบิก';
        } else if ($type == 'P') {
            return 'ใบสั่งซื้อ';
        } else if ($type == 'S') {
            return 'ใบรับสินค้า';
        }
    }

    public function getuserUpdate()
    {
        return $this->hasOne(Employee::class, ['id' => 'updated_user']);
    }

    public function getusercreate()
    {
        return $this->hasOne(Employee::class, ['id' => 'created_user']);
    }

    public function getPartner()
    {
        return $this->hasOne(AuditPartner::class, ['id' => 'partner_id']);
    }

    public function getDepartment()
    {
        return $this->hasOne(AuditDepartment::class, ['id' => 'department_id']);
    }

    public function getDepartment1()
    {
        return $this->hasOne(AuditDepartment::class, ['id' => 'partner_id']);
    }
}
