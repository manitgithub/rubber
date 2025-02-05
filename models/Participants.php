<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "participants".
 *
 * @property int $_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $gender
 * @property string|null $participant_telephone
 * @property string|null $birthDate
 * @property string|null $age_category
 * @property int|null $bib_number
 * @property string|null $health_issues
 * @property string|null $emergency_contact
 * @property string|null $emergency_contact_relationship
 * @property string|null $emergency_contact_phone
 * @property string|null $province
 * @property string|null $nationalId
 * @property string|null $shirt
 * @property string|null $shirt_type
 * @property string|null $reg_deliver_option
 * @property string|null $reg_deliver_to_street
 * @property string|null $reg_deliver_to_locality
 * @property string|null $reg_deliver_to_city
 * @property string|null $reg_deliver_to_recipient
 * @property string|null $reg_deliver_to_telephone
 * @property string|null $reg_deliver_to_postalCode
 * @property string|null $reg_deliver_to_address
 * @property string|null $reg_deliver_to_province
 * @property string|null $remarks
 * @property string|null $delivery
 * @property string|null $deliver_to_recipient
 * @property string|null $deliver_to_address
 * @property string|null $reg_status
 * @property string|null $group_name
 * @property string|null $registration_date
 * @property string|null $registration_type
 * @property string|null $ticket_type
 * @property string|null $race
 * @property float|null $price
 * @property string|null $user_code
 * @property string|null $payment_method
 * @property float|null $payment_amount
 * @property string|null $payment_date
 * @property string|null $payment_reference
 * @property string|null $registration_id
 * @property string|null $participant_id
 * @property string|null $payment_id
 * @property string|null $codes
 * @property string|null $tracking_code
 * @property string|null $registration_code
 * @property string|null $start_date
 * @property string|null $ticket_code
 * @property string|null $image_other_url
 * @property int|null $pickup_status
 * @property int|null $pickup_id
 * @property string|null $pickup_at
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
            [['gender', 'health_issues', 'reg_deliver_to_address', 'remarks', 'deliver_to_address', 'codes'], 'string'],
            [['birthDate', 'registration_date', 'payment_date', 'start_date', 'pickup_at'], 'safe'],
            [['bib_number', 'pickup_status', 'pickup_id'], 'integer'],
            [['price', 'payment_amount'], 'number'],
            [['first_name', 'last_name', 'email', 'emergency_contact', 'reg_deliver_to_street', 'reg_deliver_to_locality', 'reg_deliver_to_recipient', 'deliver_to_recipient', 'group_name', 'payment_reference', 'image_other_url'], 'string', 'max' => 255],
            [['participant_telephone', 'emergency_contact_phone', 'nationalId', 'reg_deliver_to_telephone'], 'string', 'max' => 20],
            [['age_category', 'shirt', 'shirt_type', 'delivery', 'reg_status', 'payment_method'], 'string', 'max' => 50],
            [['emergency_contact_relationship', 'province', 'reg_deliver_option', 'reg_deliver_to_city', 'reg_deliver_to_province', 'registration_type', 'ticket_type', 'race', 'user_code', 'registration_id', 'participant_id', 'payment_id', 'tracking_code', 'registration_code', 'ticket_code'], 'string', 'max' => 100],
            [['reg_deliver_to_postalCode'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'Id',
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
            'reg_deliver_to_street' => 'Reg Deliver To Street',
            'reg_deliver_to_locality' => 'Reg Deliver To Locality',
            'reg_deliver_to_city' => 'Reg Deliver To City',
            'reg_deliver_to_recipient' => 'Reg Deliver To Recipient',
            'reg_deliver_to_telephone' => 'Reg Deliver To Telephone',
            'reg_deliver_to_postalCode' => 'Reg Deliver To Postal Code',
            'reg_deliver_to_address' => 'Reg Deliver To Address',
            'reg_deliver_to_province' => 'Reg Deliver To Province',
            'remarks' => 'Remarks',
            'delivery' => 'Delivery',
            'deliver_to_recipient' => 'Deliver To Recipient',
            'deliver_to_address' => 'Deliver To Address',
            'reg_status' => 'Reg Status',
            'group_name' => 'Group Name',
            'registration_date' => 'Registration Date',
            'registration_type' => 'Registration Type',
            'ticket_type' => 'Ticket Type',
            'race' => 'Race',
            'price' => 'Price',
            'user_code' => 'User Code',
            'payment_method' => 'Payment Method',
            'payment_amount' => 'Payment Amount',
            'payment_date' => 'Payment Date',
            'payment_reference' => 'Payment Reference',
            'registration_id' => 'Registration ID',
            'participant_id' => 'Participant ID',
            'payment_id' => 'Payment ID',
            'codes' => 'Codes',
            'tracking_code' => 'Tracking Code',
            'registration_code' => 'Registration Code',
            'start_date' => 'Start Date',
            'ticket_code' => 'Ticket Code',
            'image_other_url' => 'Image Other Url',
            'pickup_status' => 'Pickup Status',
            'pickup_id' => 'Pickup ID',
            'pickup_at' => 'Pickup At',
        ];
    }
}
