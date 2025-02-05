<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "audit_department".
 *
 * @property int $id
 * @property string|null $department
 */
class AuditDepartment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'audit_department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['department'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'department' => 'แผนก',
        ];
    }
}
