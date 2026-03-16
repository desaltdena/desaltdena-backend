<?php
    date_default_timezone_set('Asia/Bangkok');

    class Connect extends PDO {
        public function __construct() {
            
            // ดึงค่าจาก Railway Variables
            $host     = getenv('MYSQLHOST') ?: 'localhost'; 
            $port     = getenv('MYSQLPORT') ?: '3306';
            $dbname   = getenv('MYSQLDATABASE') ?: 'railway';
            $username = getenv('MYSQLUSER') ?: 'root'; 
            
            // 🌟 แก้ไขจุดนี้: ใช้ MYSQL_ROOT_PASSWORD แทน MYSQLPASSWORD เพราะ user คือ root
            $password = getenv('MYSQL_ROOT_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: ''; 

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

            try {
                parent::__construct($dsn, $username, $password);

                $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

                $this->exec("SET time_zone = '+07:00'");
                $this->exec("set names utf8mb4");

            } catch (PDOException $e) {
                // ส่ง Error เป็น JSON เพื่อให้ Frontend อ่านง่าย
                header('Content-Type: application/json');
                echo json_encode([
                    "status" => "error", 
                    "message" => "Connection failed: " . $e->getMessage()
                ]);
                exit;
            }
        }
    }

    define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?: '');
    define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: '');
    define('GOOGLE_REDIRECT_URI', getenv('GOOGLE_REDIRECT_URI') ?: '');
?>
