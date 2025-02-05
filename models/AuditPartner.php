<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "audit_partner".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $address
 * @property string|null $contact
 * @property string|null $tel
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_user
 * @property int|null $updated_user
 * @property int|null $flag_del
 */
class AuditPartner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'audit_partner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['created_user', 'updated_user', 'flag_del'], 'integer'],
            [['name', 'address', 'contact'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'คู่ค้า',
            'address' => 'ที่อยู่',
            'contact' => 'ผู้ติดต่อ',
            'tel' => 'โทรศัพท์',
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
