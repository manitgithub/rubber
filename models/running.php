<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

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

    public $upload_foler = 'uploads';

    public function upload($model, $attribute)
    {
        $photo  = UploadedFile::getInstance($model, $attribute);
        $path = $this->getUploadPath();
        if ($this->validate() && $photo !== null) {
            $fileName = md5($photo->baseName . time()) . '.' . $photo->extension;
            //$fileName = $photo->baseName . '.' . $photo->extension;
            if ($photo->saveAs($path . $fileName)) {
                return $fileName;
            }
        }
        return $model->isNewRecord ? false : $model->getOldAttribute($attribute);
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
            $this->updated_at = date('Y-m-d H:i:s');
            $this->flag_del = 0;
        } else {
            $this->updated_at = date('Y-m-d H:i:s');
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
        return empty($this->img) ? Yii::getAlias('@web') . '/temp.202302181351/img/800x300.png' : $this->getUploadUrl() . $this->img;
    }
}
