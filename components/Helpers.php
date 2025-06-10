<?php

namespace app\components;

use app\models\AuditHeader;
use yii\base\Component;
use Yii;
use app\models\Leave;

class Helpers extends Component
{

    public function dateENtoTH($date)
    {
        if (empty($date)) {
            return null;
        }

        $arr = explode("-", $date);
        $dateTh = $arr[2] . "-" . $arr[1] . "-" . ($arr[0] + 543);
        return $dateTh;
    }
    public function dateTHtoEN($date)
    {
        if (empty($date)) {
            return null;
        }

        $arr = explode("-", $date);
        $dateTh = ($arr[2] - 543) . "-" . $arr[1] . "-" . $arr[0];
        return $dateTh;
    }
    public function timeDiff($time1, $time2)
    {
        //แสดงเป็น ชม นาที วินาที
        $time1 = strtotime($time1);
        $time2 = strtotime($time2);
        $diff = $time2 - $time1;
        $hours = floor($diff / (60 * 60));
        $minutes = floor(($diff - $hours * (60 * 60)) / 60);
        $seconds = $diff - $hours * (60 * 60) - $minutes * 60;

        if ($hours > 0) {
            return $hours . " ชม. " . $minutes . " นาที " . $seconds . " วินาที";
        } else {
            return $minutes . " นาที " . $seconds . " วินาที";
        }
    }

    public function dateCompare($date) // ฟังก์ชั่นเปรียบเทียบเวลากับปัจจุบัน
    {
        $datetime = date_create($date);
        $dateNow = date_create('now');

        $interval = date_diff($datetime, $dateNow);
        $compareSec = $interval->format('%s');
        $compareMin = $interval->format('%i');
        $compareHour = $interval->format('%h');
        $compareDay = $interval->format('%a');

        $days = " วันที่แล้ว";
        $hours = " ชั่วโมงที่แล้ว";
        $mins = " นาทีที่แล้ว";
        $secs = " วินาทีที่แล้ว";

        if ($compareDay > 7) {
            return $date;
        } else if ($compareDay > 1) {
            return $compareDay . $days;
        } else if ($compareHour > 1) {
            return $compareHour . $hours;
        } else if ($compareMin > 1) {
            return $compareMin . $mins;
        } else {
            return $compareSec . $secs;
        }
    }

    public function LineNotify($xLineMsg)
    {
        if ($_SERVER['HTTP_HOST'] == 'localhost:8080') {
            return true;
        }
        $xToken = 'XBLoT4EZn4LHx8ANdrsyikqY0U6V7zSthUiDVvUsP8h';
        $yResult = 1;
        if ($xToken != null) {
            $chOne = curl_init();
            curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
            curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($chOne, CURLOPT_POST, 1);
            curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . $xLineMsg . "\n| " . date('d/m/Y H:i:s'));
            curl_setopt($chOne, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Bearer ' . $xToken
            ]);
            curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($chOne);
            $result2 = json_decode($result, true);
            if ($result2['status'] == 401) {
                $yResult = 1; //เกิดข้อผิดพลาด
            } else {
                $yResult = 0; //สำเร็จ
            }

            curl_close($chOne);
        }
        return $yResult;
    }
    public function DateThai($date)
    {
        $date = strtotime($date);
        $thai_month_arr = array(
            "0" => "",
            "1" => "มกราคม",
            "2" => "กุมภาพันธ์",
            "3" => "มีนาคม",
            "4" => "เมษายน",
            "5" => "พฤษภาคม",
            "6" => "มิถุนายน",
            "7" => "กรกฎาคม",
            "8" => "สิงหาคม",
            "9" => "กันยายน",
            "10" => "ตุลาคม",
            "11" => "พฤศจิกายน",
            "12" => "ธันวาคม"
        );
        $thai_date_return = date("j", $date) . " " . $thai_month_arr[date("n", $date)] . " " . (date("Y", $date) + 543);
        return $thai_date_return;
    }

    public function DatetimeThai($date)
    {
        $date = strtotime($date);
        $thai_month_arr = array(
            "0" => "",
            "1" => "มกราคม",
            "2" => "กุมภาพันธ์",
            "3" => "มีนาคม",
            "4" => "เมษายน",
            "5" => "พฤษภาคม",
            "6" => "มิถุนายน",
            "7" => "กรกฎาคม",
            "8" => "สิงหาคม",
            "9" => "กันยายน",
            "10" => "ตุลาคม",
            "11" => "พฤศจิกายน",
            "12" => "ธันวาคม"
        );
        $thai_date_return = date("j", $date) . " " . $thai_month_arr[date("n", $date)] . " " . (date("Y", $date) + 543) . " " . date("H", $date) . ":" . date("i", $date);
        return $thai_date_return;
    }

    public function leaveCount($userid, $type, $year, $status)
    {
        $sum = 0;
        $leave = Leave::find()->where(['userid' => $userid, 'type' => $type, 'status' => $status])->andWhere(['like', 'startdate', $year])->all();
        foreach ($leave as $leave) {
            $sum += date_diff(date_create($leave->startdate), date_create($leave->enddate))->format('%a') + 1;
        }
        return $sum;
    }

public function ReadNumber($number)
{
    $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
    $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
    $number = $number + 0;
    $ret = "";
    if ($number == 0) return $ret;
    if ($number > 1000000)
    {
        $ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
        $number = intval(fmod($number, 1000000));
    }
    
    $divider = 100000;
    $pos = 0;
    while($number > 0)
    {
        $d = intval($number / $divider);
        $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" : 
            ((($divider == 10) && ($d == 1)) ? "" :
            ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
        $ret .= ($d ? $position_call[$pos] : "");
        $number = $number % $divider;
        $divider = $divider / 10;
        $pos++;
    }




    return $ret;
}

public function Convert($amount_number)
{
    $amount_number = number_format($amount_number, 2, ".","");
    $pt = strpos($amount_number , ".");
    $number = $fraction = "";
    if ($pt === false) 
        $number = $amount_number;
    else
    {
        $number = substr($amount_number, 0, $pt);
        $fraction = substr($amount_number, $pt + 1);
    }
    
    $ret = "";
    $baht = $this->ReadNumber($number);
    if ($baht != "")
        $ret .= $baht . "บาท";
    
    $satang = $this->ReadNumber($fraction);
    if ($satang != "")
        $ret .=  $satang . "สตางค์";
    else 
        $ret .= "ถ้วน";
    return $ret;
}


}
