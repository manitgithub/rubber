<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_uom".
 *
 * @property int $id
 * @property string|null $uom_code
 * @property string $uom_name
 * @property string|null $description
 * @property string|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_user
 * @property int|null $updated_user
 * @property int|null $flag_del
 */
class ItemUom extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_uom';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uom_name'], 'required'],
            [['id', 'created_user', 'updated_user', 'flag_del'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['uom_code'], 'string', 'max' => 20],
            [['uom_name'], 'string', 'max' => 500],
            [['description'], 'string', 'max' => 3000],
            [['status'], 'string', 'max' => 2],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uom_code' => 'Uom Code',
            'uom_name' => 'หน่วยนับ',
            'description' => 'Description',
            'status' => 'Status',
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
}
