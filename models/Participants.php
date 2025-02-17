<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "participants".
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $gender
 * @property string|null $participant_telephone
 * @property string|null $birthDate
 * @property string|null $age_category
 * @property string|null $bib_number
 * @property string|null $health_issues
 * @property string|null $emergency_contact
 * @property string|null $emergency_contact_relationship
 * @property string|null $emergency_contact_phone
 * @property string|null $province
 * @property string|null $nationalId
 * @property string|null $shirt
 * @property string|null $shirt_type
 * @property string|null $reg_deliver_option
 * @property string|null $registration_type
 * @property string|null $ticket_type
 * @property string|null $race
 * @property string|null $price
 * @property string|null $user_code
 * @property string|null $start_date
 * @property string|null $ticket_code
 * @property string|null $picktime
 * @property int|null $runningid
 */
class Participants extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'participants';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['picktime'], 'safe'],
            [['runningid', 'status','pickup_by','updateid'], 'integer'],
            [['first_name', 'last_name', 'emergency_contact', 'province'], 'string', 'max' => 100],
            [['email', 'health_issues'], 'string', 'max' => 255],
            [['gender', 'participant_telephone', 'birthDate', 'emergency_contact_phone', 'price', 'start_date'], 'string', 'max' => 20],
            [['age_category', 'bib_number', 'emergency_contact_relationship', 'nationalId', 'shirt_type', 'reg_deliver_option', 'registration_type', 'ticket_type', 'race', 'user_code', 'ticket_code'], 'string', 'max' => 200],
            [['shirt'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'gender' => 'Gender',
            'participant_telephone' => 'Participant Telephone',
            'birthDate' => 'Birth Date',
            'age_category' => 'Age Category',
            'bib_number' => 'Bib Number',
            'health_issues' => 'Health Issues',
            'emergency_contact' => 'Emergency Contact',
            'emergency_contact_relationship' => 'Emergency Contact Relationship',
            'emergency_contact_phone' => 'Emergency Contact Phone',
            'province' => 'Province',
            'nationalId' => 'National ID',
            'shirt' => 'Shirt',
            'shirt_type' => 'Shirt Type',
            'reg_deliver_option' => 'Reg Deliver Option',
            'registration_type' => 'Registration Type',
            'ticket_type' => 'Ticket Type',
            'race' => 'Race',
            'price' => 'Price',
            'user_code' => 'User Code',
            'start_date' => 'Start Date',
            'ticket_code' => 'Ticket Code',
            'picktime' => 'Picktime',
            'runningid' => 'Runningid',
            'status' => 'Status',
        ];
    }

    public function getRunning()
    {
        return $this->hasOne(Running::className(), ['id' => 'runningid']);
    }

    public function getPickupByUser()
    {
        return $this->hasOne(User::className(), ['id' => 'pickup_by']);
    }
}
