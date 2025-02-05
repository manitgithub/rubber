<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "audit_detail".
 *
 * @property int $id
 * @property int $audit_id
 * @property int $part_id
 * @property string|null $audit_txt
 * @property float|null $unit_price
 * @property string|null $qty
 * @property string|null $qty_uom
 * @property string|null $total_amount
 * @property int|null $uom_id
 * @property string|null $expdate
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_user
 * @property int|null $updated_user
 * @property int|null $flag_del
 */
class AuditDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'audit_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['audit_id', 'part_id'], 'required'],
            [['id', 'audit_id', 'part_id', 'uom_id', 'created_user', 'updated_user', 'flag_del', 'bom'], 'integer'],
            [['unit_price'], 'number'],
            [['expdate', 'created_at', 'updated_at'], 'safe'],
            [['audit_txt'], 'string', 'max' => 255],
            [['qty', 'qty_uom', 'total_amount'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'audit_id' => 'Audit ID',
            'part_id' => 'Part ID',
            'audit_txt' => 'Audit Txt',
            'unit_price' => 'Unit Price',
            'qty' => 'Qty',
            'qty_uom' => 'Qty Uom',
            'total_amount' => 'Total Amount',
            'uom_id' => 'Uom ID',
            'expdate' => 'Expdate',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_user' => 'Created User',
            'updated_user' => 'Updated User',
            'flag_del' => 'Flag Del',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
            $this->created_user = Yii::$app->user->identity->id;
            $this->updated_at = date('Y-m-d H:i:s');
            $this->updated_user = Yii::$app->user->identity->id;
            $this->flag_del = 0;
        } else {
            $this->updated_at = date('Y-m-d H:i:s');
            $this->updated_user = Yii::$app->user->identity->id;
        }
        return parent::beforeSave($insert);
    }

    public function getUom()
    {
        return $this->hasOne(ItemUom::className(), ['id' => 'uom_id']);
    }
    public function getItemPart()
    {
        return $this->hasOne(ItemPart::className(), ['id' => 'part_id']);
    }
    public function getHeader()
    {
        return $this->hasOne(AuditHeader::className(), ['id' => 'audit_id']);
    }
}
