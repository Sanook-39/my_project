<?php
session_start();
require 'config.php'; // เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $lname = trim($_POST['lname']);
    $sex = trim($_POST['sex']);
    $birth = trim($_POST['birth']);

    try {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        // ค้นหาผู้ใช้ในฐานข้อมูล
        $stmt = $pdo->prepare("insert into users (username,password,name,lname,sex,birth,position_id)values('".$_POST['username']."','".$_POST['password']."','".$_POST['name']."','".$_POST['lname']."','".$_POST['sex']."','".$_POST['birth']."','".$_POST['position_id']."')");
    
        if ($stmt->execute()) {
            echo "<script>alert('เพิ่มข้อมูลสำเร็จ!'); window.location.href='add_user.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาด โปรดลองอีกครั้ง'); window.location.href='add_user.php';</script>";
        }
    } catch (PDOException $e) {
        // echo "Error: " . $e->getMessage();
        echo "<script>alert('เกิดข้อผิดพลาด Username ซ้ำ'); window.location.href='add_user.php';</script>";
    }
}
?>