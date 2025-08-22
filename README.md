# ระบบจัดการสหกรณ์สวนยาง (Rubber Management System)

## รายละเอียดโปรเจค
ระบบจัดการสหกรณ์สวนยางฉลองน้ำขาวพัฒนา จำกัด สำหรับการบันทึกการซื้อขายน้ำยาง การจัดการสมาชิก และการออกใบเสร็จ

## เทคโนโลยีที่ใช้
- **Framework**: Yii2 Framework (PHP)
- **ฐานข้อมูล**: MySQL
- **Frontend**: Bootstrap 5, jQuery, Select2
- **CSS**: Custom CSS พร้อม responsive design
- **JavaScript**: ES6+ สำหรับ interaction และ AJAX

## คุณสมบัติหลัก

### 📝 การจัดการการซื้อน้ำยาง
- บันทึกข้อมูลการซื้อน้ำยางรายวัน
- คำนวณน้ำหนักแห้งอัตโนมัติ (น้ำหนัก × เปอร์เซ็นต์)
- ระบบเตือนซ้ำเมื่อสมาชิกมีรายการในวันเดียวกัน
- แสดงสถิติรายวัน (จำนวนรายการ, น้ำหนักรวม, ยอดเงินรวม)

### 👥 การจัดการสมาชิก
- ข้อมูลสมาชิกครบถ้วน (ชื่อ-นามสกุล, ที่อยู่, เบอร์โทร)
- รหัสสมาชิกแบบ 3 หลัก (เติม 0 ข้างหน้า)
- ระบบค้นหาและกรองข้อมูลสมาชิก

### 💰 การจัดการราคา
- กำหนดราคาน้ำยางรายวัน
- ระบบดึงราคาล่าสุดมาใช้อัตโนมัติ
- ประวัติการเปลี่ยนแปลงราคา

### 🧾 ระบบใบเสร็จ
- รันเลขใบเสร็จแบบอัตโนมัติ
- เลือกช่วงวันที่สำหรับการออกใบเสร็จ
- พิมพ์ใบเสร็จรายบุคคลหรือทั้งหมด
- เรียงลำดับตาม member_id แบบ natural sort (005 มาก่อน 005/1)

### 📊 รายงานและสถิติ
- รายงานการซื้อขายรายวัน
- สถิติน้ำหนักและยอดเงินรวม
- ราคาเฉลี่ยต่อวัน

## โครงสร้างโปรเจค

```
rubber/
├── assets/               # Asset bundles
├── commands/            # Console commands
├── components/          # Helper components
├── config/              # Configuration files
├── controllers/         # Controller classes
│   ├── EmployeeController.php
│   ├── MembersController.php
│   ├── PricesController.php
│   ├── PurchasesController.php
│   ├── ReportController.php
│   └── SiteController.php
├── models/              # Model classes
│   ├── Employee.php
│   ├── Members.php
│   ├── Prices.php
│   ├── Purchases.php
│   ├── Receipt.php
│   └── User.php
├── views/               # View templates
│   ├── layouts/         # Layout files
│   ├── members/         # Member views
│   ├── prices/          # Price views
│   ├── purchases/       # Purchase views
│   └── site/            # Site views
├── web/                 # Web accessible files
└── composer.json        # Dependencies
```

## การติดตั้งและใช้งาน

### ข้อกำหนดระบบ
- PHP 8.0+
- MySQL 5.7+
- Apache/Nginx
- Composer

### ขั้นตอนการติดตั้ง
1. Clone repository
```bash
git clone [repository-url]
cd rubber
```

2. ติดตั้ง dependencies
```bash
composer install
```

3. สร้างฐานข้อมูล
```sql
CREATE DATABASE rubber_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

4. ตั้งค่าฐานข้อมูลใน `config/db.php`
```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=rubber_db',
    'username' => 'your_username',
    'password' => 'your_password',
    'charset' => 'utf8mb4',
];
```

5. Run migrations (หากมี)
```bash
php yii migrate
```

6. ตั้งค่า web server ให้ point ไปที่ `web/` directory

## การใช้งานหลัก

### 1. บันทึกการซื้อน้ำยาง
- ไปที่ เมนู "บันทึกการซื้อน้ำยาง"
- เลือกวันที่, สมาชิก, ใส่น้ำหนักและเปอร์เซ็นต์
- ระบบจะคำนวณน้ำหนักแห้งและยอดเงินอัตโนมัติ
- กดบันทึกข้อมูล

### 2. รันเลขใบเสร็จ
- ไปที่ เมนู "รันเลขใบเสร็จ"
- เลือกช่วงวันที่ที่ต้องการ
- เลือกวันที่ใบเสร็จ
- กด "รันเลขใบเสร็จทั้งหมด"

### 3. พิมพ์ใบเสร็จ
- ไปที่ เมนู "ใบเสร็จ"
- เลือกกรองตามวันที่หรือเลขที่
- กดพิมพ์รายการที่ต้องการ

## ฟีเจอร์พิเศษ

### ระบบเตือนซ้ำ
- เมื่อเลือกสมาชิกที่มีรายการในวันเดียวกัน จะแสดงคำเตือน
- ป้องกันการบันทึกข้อมูลซ้ำโดยไม่ได้ตั้งใจ

### การคำนวณอัตโนมัติ
- น้ำหนักแห้ง = น้ำหนัก × เปอร์เซ็นต์ ÷ 100 (ตัดทศนิยม)
- ยอดเงิน = น้ำหนักแห้ง × ราคา

### เรียงลำดับ Natural Sort
- รหัสสมาชิก 005 จะมาก่อน 005/1
- รองรับรหัสสมาชิกแบบมี sub-member

## การบำรุงรักษา

### Backup ฐานข้อมูล
```bash
mysqldump -u username -p rubber_db > backup_$(date +%Y%m%d).sql
```

### การอัปเดต
1. Backup ฐานข้อมูลก่อนอัปเดต
2. Pull code ใหม่
3. Run composer update (หากจำเป็น)
4. Run migrations (หากมี)

## ปัญหาที่พบบ่อย

### ไม่สามารถบันทึกข้อมูลได้
- ตรวจสอบ permission ของ `runtime/` folder
- ตรวจสอบการเชื่อมต่อฐานข้อมูล

### การพิมพ์ใบเสร็จไม่ถูกต้อง
- ตรวจสอบ CSS สำหรับ @media print
- ตรวจสอบขนาดกระดาษในเบราว์เซอร์

## ข้อมูลติดต่อ
- **องค์กร**: สหกรณ์กองทุนสวนยางฉลองน้ำขาวพัฒนา จำกัด
- **ที่อยู่**: 92 หมู่ 5 ตำบลฉลอง อำเภอสิชล จังหวัดนครศรีธรรมราช

## License
This project is developed for internal use of the rubber cooperative.

---
**หมายเหตุ**: ระบบนี้พัฒนาขึ้นเพื่อใช้งานภายในสหกรณ์ หากมีข้อสงสัยหรือต้องการปรับปรุง กรุณาติดต่อผู้พัฒนาระบบ 
