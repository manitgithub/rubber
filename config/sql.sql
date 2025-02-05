-- Discard the existing tablespace if the table already exists
ALTER TABLE `checkin` DISCARD TABLESPACE;

-- Create `checkin` table
CREATE TABLE `checkin` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `userid` INT NOT NULL COMMENT 'รหัสพนักงาน',
    `date` DATE NOT NULL COMMENT 'วันที่',
    `datetime` DATETIME NOT NULL COMMENT 'วันเวลา',
    `type` VARCHAR(50) NOT NULL COMMENT 'เข้า-ออก',
    `img` VARCHAR(255) DEFAULT NULL COMMENT 'Img',
    `status` TINYINT NOT NULL DEFAULT 0 COMMENT 'สถานะ',
    `appverid` INT DEFAULT NULL COMMENT 'ผู้อนุมัติ',
    `appverdate` DATE DEFAULT NULL COMMENT 'วันที่อนุมัติ',
    `note` TEXT DEFAULT NULL COMMENT 'หมายเหตุ',
    `gps` VARCHAR(255) DEFAULT NULL COMMENT 'พิกัด',
    `created_id` INT NOT NULL COMMENT 'Created ID',
    `updated_id` INT NOT NULL COMMENT 'Updated ID',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created At',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated At',
    `flag_del` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Flag Del'
);

-- Create `leave` table
CREATE TABLE `leave` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `type` VARCHAR(255) NOT NULL COMMENT 'ประเภทการลา',
    `startdate` DATE NOT NULL COMMENT 'วันที่ลา',
    `enddate` DATE NOT NULL COMMENT 'ถึงวันที่',
    `note` TEXT DEFAULT NULL COMMENT 'หมายเหตุ',
    `files` VARCHAR(255) DEFAULT NULL COMMENT 'เอกสารการแนบ',
    `bossid` INT NOT NULL COMMENT 'หัวหน้า',
    `status` TINYINT NOT NULL DEFAULT 0 COMMENT 'Status',
    `bossapp` TINYINT NOT NULL DEFAULT 0 COMMENT 'Bossapp',
    `bossnote` TEXT DEFAULT NULL COMMENT 'Bossnote',
    `bossappdate` DATETIME DEFAULT NULL COMMENT 'Bossappdate',
    `created_id` INT NOT NULL COMMENT 'Created ID',
    `updated_id` INT NOT NULL COMMENT 'Updated ID',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created At',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated At',
    `flag_del` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Flag Del'
);

-- Create `leavetype` table
CREATE TABLE `leavetype` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL COMMENT 'ประเภทการลา',
    `daybefore` INT NOT NULL COMMENT 'ลาก่อนกี่วัน',
    `daybelated` INT NOT NULL COMMENT 'ลาหลังกี่วัน',
    `daymax` INT NOT NULL COMMENT 'ลาได้สูงสุดกี่วันต่อปี',
    `qtymax` INT NOT NULL COMMENT 'ลาได้สูงสุดกี่ครั้งต่อปี',
    `created_id` INT NOT NULL COMMENT 'Created ID',
    `updated_id` INT NOT NULL COMMENT 'Updated ID',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created At',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated At',
    `flag_del` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Flag Del'
);

-- Create `position` table
CREATE TABLE `position` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL COMMENT 'ตำแหน่ง',
    `created_id` INT NOT NULL COMMENT 'Created ID',
    `updated_id` INT NOT NULL COMMENT 'Update ID',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created At',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated At',
    `flag_del` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Flag Del'
);

-- Create `department` table
CREATE TABLE `department` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL COMMENT 'แผนก',
    `created_id` INT NOT NULL COMMENT 'Created ID',
    `updated_id` INT NOT NULL COMMENT 'Update ID',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created At',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated At',
    `flag_del` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Flag Del'
);

-- Create `news` table
CREATE TABLE `news` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL COMMENT 'หัวข้อ',
    `detail` TEXT DEFAULT NULL COMMENT 'รายละเอียด',
    `type` VARCHAR(50) NOT NULL COMMENT 'ประเภท',
    `status` TINYINT NOT NULL DEFAULT 0 COMMENT 'สถานะ',
    `role` VARCHAR(255) DEFAULT NULL COMMENT 'ถึง',
    `read` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'อ่านแล้ว',
    `created_id` INT NOT NULL COMMENT 'Created ID',
    `updated_id` INT NOT NULL COMMENT 'Updated ID',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created At',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated At',
    `flag_del` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Flag Del'
);

-- Create `worktype` table
CREATE TABLE `worktype` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL COMMENT 'กะการทำงาน',
    `holiday` VARCHAR(255) NOT NULL COMMENT 'วันหยุด',
    `wordday` VARCHAR(255) NOT NULL COMMENT 'วันทำงาน',
    `timein` TIME NOT NULL COMMENT 'เวลาเข้างาน',
    `timeout` TIME NOT NULL COMMENT 'เวลาออกงาน',
    `created_id` INT NOT NULL COMMENT 'Created ID',
    `updated_id` INT NOT NULL COMMENT 'Updated ID',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created At',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated At',
    `flag_del` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Flag Del'
);
