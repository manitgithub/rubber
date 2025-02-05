<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_barcode".
 *
 * @property int $id
 * @property int|null $item_id
 * @property string|null $barcode
 * @property int|null $uom_id
 * @property int|null $unit
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_user
 * @property int|null $updated_user
 * @property int|null $flag_del
 */
class ItemBarcode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_barcode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'item_id', 'uom_id', 'unit', 'created_user', 'updated_user', 'flag_del'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['barcode'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Item ID',
            'barcode' => 'Barcode',
            'uom_id' => 'Uom ID',
            'unit' => 'Unit',
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
    public function getItem()
    {
        return $this->hasOne(ItemPart::className(), ['id' => 'item_id']);
    }

    public function getUom()
    {
        return $this->hasOne(ItemUom::className(), ['id' => 'uom_id']);
    }
}
