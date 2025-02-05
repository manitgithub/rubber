<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "addit_bom".
 *
 * @property int $id
 * @property int|null $item_in
 * @property string|null $qty_in
 * @property int|null $item_out
 * @property string|null $qty_out
 * @property int|null $lost
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_user
 * @property int|null $updated_user
 * @property int|null $flag_del
 * @property int|null $bill_id
 * @property int|null $depart_id
 * @property int|null $item_in_uom
 * @property int|null $item_out_uom
 */
class AdditBom extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'addit_bom';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_in', 'item_out', 'lost', 'created_user', 'updated_user', 'flag_del', 'bill_id', 'depart_id', 'item_in_uom', 'item_out_uom'], 'integer'],
            [['created_at', 'item_in_unit_price', 'item_out_unit_price', 'updated_at'], 'safe'],
            [['qty_in', 'qty_out'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_in' => 'Item In',
            'qty_in' => 'Qty In',
            'item_out' => 'Item Out',
            'qty_out' => 'Qty Out',
            'lost' => 'Lost',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_user' => 'Created User',
            'updated_user' => 'Updated User',
            'flag_del' => 'Flag Del',
            'bill_id' => 'Bill ID',
            'depart_id' => 'Depart ID',
            'item_in_uom' => 'Item In Uom',
            'item_out_uom' => 'Item Out Uom',
        ];
    }
    public function getItem($id)
    {
        $model = ItemPart::findOne($id);
        return $model->part_name;
    }
    public function getUom($id)
    {
        $model = ItemUom::findOne($id);
        return $model->uom_name;
    }
}
