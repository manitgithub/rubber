<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "members".
 *
 * @property int $id
 * @property string $memberid
 * @property string|null $idcard
 * @property string|null $pername
 * @property string $name
 * @property string|null $surname
 * @property string|null $stdate
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $membertype
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $share
 * @property string|null $homenum
 * @property string|null $moo
 * @property string|null $tumbon
 * @property string|null $amper
 * @property string|null $chawat
 * @property int|null $farm
 * @property string|null $work
 */
class Members extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'members';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['memberid', 'name'], 'required'],
            [['stdate', 'created_at', 'updated_at'], 'safe'],
            [['address'], 'string'],
            [['share', 'farm'], 'integer'],
            [['memberid'], 'string', 'max' => 50],
            [['idcard'], 'string', 'max' => 13],
            [['pername', 'homenum'], 'string', 'max' => 30],
            [['name', 'email', 'work'], 'string', 'max' => 255],
            [['surname', 'tumbon', 'amper', 'chawat'], 'string', 'max' => 100],
            [['phone', 'membertype'], 'string', 'max' => 20],
            [['moo'], 'string', 'max' => 3],
            [['memberid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'memberid' => 'รหัสสมาชิก',
            'idcard' => 'รหัสบัตรประชาชน',
            'pername' => 'คำนำหน้า',
            'name' => 'ชื่อ',
            'surname' => 'นามสกุล',
            'stdate' => 'วันที่สมัคร',
            'address' => 'ที่อยู่',
            'phone' => 'เบอร์โทรศัพท์',
            'email' => 'อีเมล',
            'membertype' => 'ประเภทสมาชิก',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'share' => 'หุ้น',
            'homenum' => 'บ้านเลขที่',
            'moo' => 'หมู่ที่',
            'tumbon' => 'ตำบล',
            'amper' => 'อำเภอ',
            'chawat' => 'จังหวัด',
            'farm' => 'จำนวนไร่',
            'work' => 'งาน-วา',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            $this->updated_at = date('Y-m-d H:i:s');
            return true;
        } else {
            return false;
        }
    }
    public function getFullname()
    {
        return $this->memberid . ':' . $this->pername . ' ' . $this->name . ' ' . $this->surname;
    }

}
