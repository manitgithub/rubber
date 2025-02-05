<?php

namespace app\models;

use app\models\ItemCategory;

use Yii;

/**
 * This is the model class for table "item_subcategory".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $category_id
 */
class ItemSubcategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_subcategory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id'], 'integer'],
            [['id', 'created_user', 'updated_user', 'flag_del'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'หมวดหมู่หลัก',
            'category_id' => 'หมวดหมู่',
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
    public function getCategory()
    {
        return $this->hasOne(ItemCategory::className(), ['id' => 'category_id']);
    }
}
