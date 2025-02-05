<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "running".
 *
 * @property int $id
 * @property string $name
 * @property string|null $date
 * @property string $owner
 * @property string|null $detail
 * @property int|null $flag_del
 * @property int|null $created_id Created ID
 * @property int|null $updated_id Updated ID
 * @property string|null $created_at Created At
 * @property string|null $updated_at Updated At
 * @property string|null $img
 */
class Running extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'running';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['flag_del', 'created_id', 'updated_id'], 'integer'],
            [['name', 'owner', 'detail', 'img', 'date'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ชื่อกิจกรรม',
            'date' => 'วันเวลาที่จัดงาน',
            'owner' => 'ผู้จัดงาน',
            'detail' => 'รายละเอียด',
            'flag_del' => 'Flag Del',
            'created_id' => 'Created ID',
            'updated_id' => 'Updated ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'img' => 'ภาพประกอบ',
        ];
    }
}
