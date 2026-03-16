<?php
date_default_timezone_set('Asia/Bangkok');

class Connect extends PDO {
    public function __construct() {
        // ดึงค่าจาก Environment Variables ของ Railway
        $host     = getenv('MYSQLHOST') ?: 'localhost';
        $port     = getenv('MYSQLPORT') ?: '3306';
        $dbname   = getenv('MYSQLDATABASE') ?: 'railway';
        $username = getenv('MYSQLUSER') ?: 'root';
        // ใช้ MYSQL_ROOT_PASSWORD ตามที่เห็นในหน้า Variables ของคุณ
        $password = getenv('MYSQL_ROOT_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: '';
        
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

// ตั้งค่า Google Auth
define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?: '');
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: '');
define('GOOGLE_REDIRECT_URI', getenv('GOOGLE_REDIRECT_URI') ?: '');
