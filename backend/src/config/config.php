<?php
date_default_timezone_set('Asia/Bangkok');

class Connect extends PDO {
    public function __construct() {
        // 🌟 ใส่ค่าตรงๆ ตามที่คุณเห็นในหน้า Railway Variables เลยครับ
        $host = getenv('MYSQLHOST') ?: 'localhost'; 
            $port = getenv('MYSQLPORT') ?: '3306';
            $dbname = getenv('MYSQLDATABASE') ?: 'railway'; // ปกติ Railway ตั้งชื่อฐานข้อมูลว่า railway
            $username = getenv('MYSQLUSER') ?: 'root'; 
            $password = getenv('MYSQLPASSWORD') ?: ''; // ใส่รหัสผ่าน local ของคุณไว้ตรงนี้
        
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

        try {
            parent::__construct($dsn, $username, $password);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->exec("SET time_zone = '+07:00'");
            $this->exec("set names utf8mb4");
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["status" => "error", "message" => "DB Connection Error: " . $e->getMessage()]);
            exit;
        }
    }
}

// ตั้งค่า Google Auth (ถ้าไม่ได้ใช้ ให้ปล่อยว่างไว้แบบนี้ครับ)
define('GOOGLE_CLIENT_ID', '');
define('GOOGLE_CLIENT_SECRET', '');
define('GOOGLE_REDIRECT_URI', '');
?>
