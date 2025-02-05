<?php

namespace app\models;

use \yii\web\UploadedFile;

use Yii;

/**
 * This is the model class for table "item_part".
 *
 * @property int $id
 * @property string|null $part_code
 * @property string $part_name
 * @property int|null $category_id
 * @property int|null $uom_id
 * @property string|null $unit_price
 * @property string|null $unit_cost
 * @property string|null $description
 * @property string|null $status
 * @property string|null $barcode
 * @property string|null $img
 * @property string|null $unit_price2
 * @property string|null $unit_price3
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_user
 * @property int|null $updated_user
 * @property int|null $flag_del
 */
class ItemPart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_part';
    }
    public $upload_foler = 'uploads';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['part_name', 'part_code', 'category_id'], 'required'],
            [['id', 'category_id', 'uom_id', 'created_user', 'updated_user', 'flag_del', 'min_qty'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['part_code', 'unit_price', 'unit_cost', 'unit_price2', 'unit_price3'], 'string', 'max' => 20],
            [['part_name'], 'string', 'max' => 500],
            [['description'], 'string', 'max' => 3000],
            [['status', 'typepart'], 'string', 'max' => 2],
            [['barcode'], 'string', 'max' => 100],
            [['img'], 'string', 'max' => 200],
            [
                ['img'],
                'file',
                'skipOnEmpty' => true,
                'extensions' => 'png,jpg'
            ]
        ];
    }
    public function upload($model, $attribute)
    {
        $photo = UploadedFile::getInstance($model, $attribute);
        $path = $this->getUploadPath();
        if ($this->validate() && $photo !== null) {
            $fileName = md5($photo->baseName . time()) . '.' . $photo->extension;
            if ($photo->saveAs($path . $fileName, ['quality' => 50])) {
                if (!empty($model->getOldAttribute($attribute)) && file_exists($path . $model->getOldAttribute($attribute))) {
                    unlink($path . $model->getOldAttribute($attribute));
                }
                return $fileName;
            }
        }
        return $model->isNewRecord ? false : $model->getOldAttribute($attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'part_code' => 'โค๊ดสินค้า',
            'part_name' => 'ชื่อสินค้า',
            'category_id' => 'กลุ่มสินค้า',
            'uom_id' => 'หน่วยเล็กสุด',
            'unit_price' => 'ราคา/หน่วยเล็กสุด',
            'unit_cost' => 'Unit Cost',
            'min_qty' => 'ขั้นต่ำ',
            'description' => 'คำอธิบาย',
            'status' => 'สถานะ',
            'barcode' => 'Barcode',
            'img' => 'Img',
            'unit_price2' => 'Unit Price2',
            'unit_price3' => 'Unit Price3',
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

    public function getUploadPath()
    {
        return Yii::getAlias('@webroot') . '/' . $this->upload_foler . '/';
    }

    public function getUploadUrl()
    {
        return Yii::getAlias('@web') . '/' . $this->upload_foler . '/';
    }

    public function getPhotoViewer()
    {
        return empty($this->img) ? Yii::getAlias('@web') . '/img/item.png' : $this->getUploadUrl() . $this->img;
    }

    public function getUom()
    {
        return $this->hasOne(ItemUom::className(), ['id' => 'uom_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(ItemMinorGroup::className(), ['id' => 'category_id']);
    }

    public function getStock($depart_id = null)
    {
        $item_id = $this->id;
        if ($depart_id) {
            $modelStock = Yii::$app->db->createCommand("SELECT 
                SUM(
                    CASE WHEN header.type = 'S' THEN log.qty 
                    WHEN header.type = 'R' THEN -log.qty 
                    WHEN header.type = 'M' THEN log.qty
                    WHEN header.type = 'T' AND header.department_id = $depart_id AND header.department_id != header.partner_id THEN log.qty 
                    WHEN header.type = 'T' AND header.partner_id = $depart_id AND header.department_id != header.partner_id THEN -log.qty 
                    ELSE 0 END) AS total
                FROM audit_detail AS log
                RIGHT JOIN audit_header AS header 
                ON header.id = log.audit_id 
                WHERE log.flag_del = 0
                AND log.part_id = $item_id
                AND (
                    (header.type IN ('S','T','M') AND header.department_id = $depart_id) 
                    OR (header.type IN ('R','T') AND header.partner_id = $depart_id)
                )
                AND header.status = 1
                AND header.flag_del = 0
                GROUP BY log.part_id")
                ->queryOne();
        } else {
            $modelStock = Yii::$app->db->createCommand("SELECT 
                SUM(
                    CASE WHEN header.type = 'S' THEN log.qty 
                    WHEN header.type = 'R' THEN -log.qty
                    WHEN header.type = 'M' THEN log.qty
                    ELSE 0 END) AS total
                FROM audit_detail AS log
                RIGHT JOIN audit_header AS header 
                ON header.id = log.audit_id 
                WHERE log.flag_del = 0
                AND log.part_id = $item_id
                AND header.type IN ('S','R','M')
                AND header.status = 1
                AND header.flag_del = 0
                GROUP BY log.part_id")
                ->queryOne();
        }

        if ($modelStock) {
            return $modelStock['total'];
        }

        return 0;
    }
}
