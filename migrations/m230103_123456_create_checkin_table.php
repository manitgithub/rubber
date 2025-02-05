<?php

use yii\db\Migration;

class m230103_123456_create_checkin_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%checkin}}', [
            'id' => $this->primaryKey(),
            'userid' => $this->integer()->notNull()->comment('รหัสพนักงาน'),
            'date' => $this->date()->notNull()->comment('วันที่'),
            'datetime' => $this->dateTime()->notNull()->comment('วันเวลา'),
            'type' => $this->string(50)->notNull()->comment('เข้า-ออก'),
            'img' => $this->string()->comment('Img'),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('สถานะ'),
            'appverid' => $this->integer()->comment('ผู้อนุมัติ'),
            'appverdate' => $this->date()->comment('วันที่อนุมัติ'),
            'note' => $this->text()->comment('หมายเหตุ'),
            'gps' => $this->string()->comment('พิกัด'),
            'created_id' => $this->integer()->notNull()->comment('Created ID'),
            'updated_id' => $this->integer()->notNull()->comment('Updated ID'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Created At'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Updated At'),
            'flag_del' => $this->boolean()->notNull()->defaultValue(false)->comment('Flag Del'),
        ]);

        $this->createTable('{{%leave}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(255)->notNull()->comment('ประเภทการลา'),
            'startdate' => $this->date()->notNull()->comment('วันที่ลา'),
            'enddate' => $this->date()->notNull()->comment('ถึงวันที่'),
            'note' => $this->text()->comment('หมายเหตุ'),
            'files' => $this->string()->comment('เอกสารการแนบ'),
            'bossid' => $this->integer()->notNull()->comment('หัวหน้า'),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('Status'),
            'bossapp' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('Bossapp'),
            'bossnote' => $this->text()->comment('Bossnote'),
            'bossappdate' => $this->dateTime()->comment('Bossappdate'),
            'created_id' => $this->integer()->notNull()->comment('Created ID'),
            'updated_id' => $this->integer()->notNull()->comment('Updated ID'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Created At'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Updated At'),
            'flag_del' => $this->boolean()->notNull()->defaultValue(false)->comment('Flag Del'),
        ]);

        $this->createTable('{{%leavetype}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('ประเภทการลา'),
            'daybefore' => $this->integer()->notNull()->comment('ลาก่อนกี่วัน'),
            'daybelated' => $this->integer()->notNull()->comment('ลาหลังกี่วัน'),
            'daymax' => $this->integer()->notNull()->comment('ลาได้สูงสุดกี่วันต่อปี'),
            'qtymax' => $this->integer()->notNull()->comment('ลาได้สูงสุดกี่ครั้งต่อปี'),
            'created_id' => $this->integer()->notNull()->comment('Created ID'),
            'updated_id' => $this->integer()->notNull()->comment('Updated ID'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Created At'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Updated At'),
            'flag_del' => $this->boolean()->notNull()->defaultValue(false)->comment('Flag Del'),
        ]);

        $this->createTable('{{%position}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('ตำแหน่ง'),
            'created_id' => $this->integer()->notNull()->comment('Created ID'),
            'updated_id' => $this->integer()->notNull()->comment('Update ID'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Created At'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Updated At'),
            'flag_del' => $this->boolean()->notNull()->defaultValue(false)->comment('Flag Del'),
        ]);

        $this->createTable('{{%department}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('แผนก'),
            'created_id' => $this->integer()->notNull()->comment('Created ID'),
            'updated_id' => $this->integer()->notNull()->comment('Update ID'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Created At'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Updated At'),
            'flag_del' => $this->boolean()->notNull()->defaultValue(false)->comment('Flag Del'),
        ]);

        $this->createTable('{{%news}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('หัวข้อ'),
            'detail' => $this->text()->comment('รายละเอียด'),
            'type' => $this->string(50)->notNull()->comment('ประเภท'),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('สถานะ'),
            'role' => $this->string(255)->comment('ถึง'),
            'read' => $this->boolean()->notNull()->defaultValue(false)->comment('อ่านแล้ว'),
            'created_id' => $this->integer()->notNull()->comment('Created ID'),
            'updated_id' => $this->integer()->notNull()->comment('Updated ID'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Created At'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Updated At'),
            'flag_del' => $this->boolean()->notNull()->defaultValue(false)->comment('Flag Del'),
        ]);

        $this->createTable('{{%worktype}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('กะการทำงาน'),
            'holiday' => $this->string(255)->notNull()->comment('วันหยุด'),
            'wordday' => $this->string(255)->notNull()->comment('วันทำงาน'),
            'timein' => $this->time()->notNull()->comment('เวลาเข้างาน'),
            'timeout' => $this->time()->notNull()->comment('เวลาออกงาน'),
            'created_id' => $this->integer()->notNull()->comment('Created ID'),
            'updated_id' => $this->integer()->notNull()->comment('Updated ID'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Created At'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Updated At'),
            'flag_del' => $this->boolean()->notNull()->defaultValue(false)->comment('Flag Del'),
        ]);

        // Add indexes or foreign key constraints if needed
        $this->createIndex('idx-checkin-userid', '{{%checkin}}', 'userid');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%worktype}}');
        $this->dropTable('{{%news}}');
        $this->dropTable('{{%department}}');
        $this->dropTable('{{%position}}');
        $this->dropTable('{{%leavetype}}');
        $this->dropTable('{{%leave}}');
        $this->dropTable('{{%checkin}}');
    }
}
