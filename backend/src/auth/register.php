<?php
require_once './config/config.php';

// 1. เช็คว่าข้อมูลมาจริงไหม
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "ไม่มีข้อมูล JSON ส่งมา", "raw" => $rawData]);
    exit;
}

// รับค่าจาก Axios
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$full_name = $data['full_name'] ?? '';
$gender = $data['gender'] ?? '';
$age = $data['age'] ?? null;
$weight_kg = $data['weight_kg'] ?? null;
$height_cm = $data['height_cm'] ?? null;
$role = $data['user_role'] ?? null;

if(empty($email) || empty($password) || empty($full_name)) {
    echo json_encode(["status" => "error", "message" => "กรุณากรอกข้อมูลให้ครบ", "received" => $data]);
    exit;
}

try {
    $db = new Connect(); // ตัวนี้จะใช้ config.php ที่คุณตั้งค่า getenv ไว้
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (full_name, email, password_hash, gender, age, weight_kg, height_cm, user_role) 
            VALUES (:name, :email, :pw, :gender, :age, :weight, :height, :role)";

    $stmt = $db->prepare($sql);
    
    // 2. เช็คการ Execute
    $result = $stmt->execute([
        ':name' => $full_name,
        ':email' => $email,
        ':pw' => $password_hash,
        ':gender' => $gender,
        ':age' => $age,
        ':weight' => $weight_kg,
        ':height' => $height_cm,
        ':role' => $role
    ]);

    if ($result) {
        echo json_encode(["status" => "success", "message" => "ลงทะเบียนสำเร็จ!", "db_name" => getenv('MYSQLDATABASE')]);
    } else {
        echo json_encode(["status" => "error", "message" => "Execute failed แต่ไม่มี Exception"]);
    }

} catch (PDOException $e) {
    // 3. พ่น Error แบบจัดเต็ม
    echo json_encode([
        "status" => "error", 
        "message" => "PDO Error: " . $e->getMessage(),
        "code" => $e->getCode()
    ]);
}
