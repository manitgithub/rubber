-- โครงสร้างตาราง receipt_books สำหรับจัดการเล่มใบเสร็จ
-- สร้างวันที่: 8 สิงหาคม 2025

CREATE TABLE `receipt_books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_no` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'เลขที่เล่มใบเสร็จ',
  `start_number` int(11) NOT NULL COMMENT 'เลขเริ่มต้นของใบเสร็จ',
  `end_number` int(11) NOT NULL COMMENT 'เลขสิ้นสุดของใบเสร็จ',
  `current_number` int(11) NOT NULL DEFAULT 1 COMMENT 'เลขปัจจุบันที่จะใช้ต่อไป',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'สถานะการใช้งาน (1=ใช้งาน, 0=ไม่ใช้งาน)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่สร้าง',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่แก้ไขล่าสุด',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_book_no` (`book_no`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางจัดการเล่มใบเสร็จ';

-- เพิ่มข้อมูลตัวอย่าง
INSERT INTO `receipt_books` (`book_no`, `start_number`, `end_number`, `current_number`, `is_active`) VALUES
('A001', 1, 1000, 1, 1),
('A002', 1001, 2000, 1001, 0),
('B001', 1, 500, 1, 0);

-- สร้าง Trigger เพื่อป้องกันการมีเล่มที่ active มากกว่า 1 เล่ม
DELIMITER $$
CREATE TRIGGER `receipt_books_single_active` 
BEFORE INSERT ON `receipt_books` 
FOR EACH ROW 
BEGIN
    IF NEW.is_active = 1 THEN
        UPDATE receipt_books SET is_active = 0 WHERE is_active = 1;
    END IF;
END$$

CREATE TRIGGER `receipt_books_single_active_update` 
BEFORE UPDATE ON `receipt_books` 
FOR EACH ROW 
BEGIN
    IF NEW.is_active = 1 AND OLD.is_active = 0 THEN
        UPDATE receipt_books SET is_active = 0 WHERE is_active = 1 AND id != NEW.id;
    END IF;
END$$
DELIMITER ;

-- สร้าง View สำหรับดูสถิติการใช้งาน
CREATE VIEW `receipt_books_stats` AS
SELECT 
    rb.*,
    (rb.end_number - rb.start_number + 1) AS total_receipts,
    (rb.current_number - rb.start_number) AS used_receipts,
    (rb.end_number - rb.current_number + 1) AS remaining_receipts,
    ROUND(((rb.current_number - rb.start_number) / (rb.end_number - rb.start_number + 1)) * 100, 2) AS usage_percentage,
    CASE 
        WHEN rb.current_number > rb.end_number THEN 'หมดแล้ว'
        WHEN rb.is_active = 1 THEN 'ใช้งานอยู่'
        ELSE 'ไม่ใช้งาน'
    END AS status_text,
    CASE 
        WHEN rb.current_number > rb.end_number THEN 1
        ELSE 0
    END AS is_finished
FROM `receipt_books` rb;

-- สร้าง Stored Procedure สำหรับดึงเลขใบเสร็จถัดไป
DELIMITER $$
CREATE PROCEDURE `GetNextReceiptNumber`(
    IN book_id INT,
    OUT receipt_number VARCHAR(20),
    OUT success BOOLEAN
)
BEGIN
    DECLARE current_num INT;
    DECLARE end_num INT;
    DECLARE book_prefix VARCHAR(10);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        SET success = FALSE;
        ROLLBACK;
    END;
    
    START TRANSACTION;
    
    -- ดึงข้อมูลเล่มปัจจุบัน
    SELECT current_number, end_number, book_no 
    INTO current_num, end_num, book_prefix
    FROM receipt_books 
    WHERE id = book_id AND is_active = 1
    FOR UPDATE;
    
    -- ตรวจสอบว่าเล่มหมดแล้วหรือยัง
    IF current_num > end_num THEN
        SET receipt_number = NULL;
        SET success = FALSE;
    ELSE
        -- สร้างเลขใบเสร็จ
        SET receipt_number = CONCAT(book_prefix, LPAD(current_num, 6, '0'));
        
        -- อัพเดทเลขปัจจุบัน
        UPDATE receipt_books 
        SET current_number = current_number + 1,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = book_id;
        
        SET success = TRUE;
    END IF;
    
    COMMIT;
END$$
DELIMITER ;

-- สร้าง Function สำหรับตรวจสอบว่าเล่มหมดแล้วหรือยัง
DELIMITER $$
CREATE FUNCTION `IsReceiptBookFinished`(book_id INT) 
RETURNS BOOLEAN
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE current_num INT;
    DECLARE end_num INT;
    
    SELECT current_number, end_number 
    INTO current_num, end_num
    FROM receipt_books 
    WHERE id = book_id;
    
    RETURN current_num > end_num;
END$$
DELIMITER ;

-- อัพเดทตาราง purchases เพื่อเชื่อมโยงกับ receipt_books (ถ้ามี)
-- ALTER TABLE `purchases` ADD COLUMN `receipt_book_id` INT(11) NULL AFTER `receipt_id`;
-- ALTER TABLE `purchases` ADD CONSTRAINT `fk_purchases_receipt_book` 
--     FOREIGN KEY (`receipt_book_id`) REFERENCES `receipt_books`(`id`) ON DELETE SET NULL;

-- สร้าง Index เพิ่มเติมเพื่อเพิ่มประสิทธิภาพ
ALTER TABLE `receipt_books` ADD INDEX `idx_book_no_active` (`book_no`, `is_active`);
ALTER TABLE `receipt_books` ADD INDEX `idx_current_end_number` (`current_number`, `end_number`);

-- สร้างข้อมูล Audit Log (ถ้าต้องการ)
CREATE TABLE `receipt_books_audit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receipt_book_id` int(11) NOT NULL,
  `action` varchar(20) NOT NULL COMMENT 'CREATE, UPDATE, DELETE, ACTIVATE, DEACTIVATE',
  `old_values` json NULL COMMENT 'ข้อมูลเก่า',
  `new_values` json NULL COMMENT 'ข้อมูลใหม่',
  `user_id` int(11) NULL COMMENT 'ผู้ใช้ที่ทำการเปลี่ยนแปลง',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_receipt_book_id` (`receipt_book_id`),
  KEY `idx_action` (`action`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='บันทึกการเปลี่ยนแปลงเล่มใบเสร็จ';

-- สร้าง Trigger สำหรับ Audit Log
DELIMITER $$
CREATE TRIGGER `receipt_books_audit_insert` 
AFTER INSERT ON `receipt_books` 
FOR EACH ROW 
BEGIN
    INSERT INTO receipt_books_audit (receipt_book_id, action, new_values)
    VALUES (NEW.id, 'CREATE', JSON_OBJECT(
        'book_no', NEW.book_no,
        'start_number', NEW.start_number,
        'end_number', NEW.end_number,
        'current_number', NEW.current_number,
        'is_active', NEW.is_active
    ));
END$$

CREATE TRIGGER `receipt_books_audit_update` 
AFTER UPDATE ON `receipt_books` 
FOR EACH ROW 
BEGIN
    INSERT INTO receipt_books_audit (receipt_book_id, action, old_values, new_values)
    VALUES (NEW.id, 'UPDATE', 
        JSON_OBJECT(
            'book_no', OLD.book_no,
            'start_number', OLD.start_number,
            'end_number', OLD.end_number,
            'current_number', OLD.current_number,
            'is_active', OLD.is_active
        ),
        JSON_OBJECT(
            'book_no', NEW.book_no,
            'start_number', NEW.start_number,
            'end_number', NEW.end_number,
            'current_number', NEW.current_number,
            'is_active', NEW.is_active
        )
    );
END$$

CREATE TRIGGER `receipt_books_audit_delete` 
AFTER DELETE ON `receipt_books` 
FOR EACH ROW 
BEGIN
    INSERT INTO receipt_books_audit (receipt_book_id, action, old_values)
    VALUES (OLD.id, 'DELETE', JSON_OBJECT(
        'book_no', OLD.book_no,
        'start_number', OLD.start_number,
        'end_number', OLD.end_number,
        'current_number', OLD.current_number,
        'is_active', OLD.is_active
    ));
END$$
DELIMITER ;
