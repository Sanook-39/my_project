<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    if (isset($_POST['edit'])) {
        // ไปยังหน้าแก้ไขข้อมูล
        header("Location: edit_user.php?id=" . $user_id);
        exit();
    }

    if (isset($_POST['delete'])) {
        // ลบข้อมูลพนักงาน
        $sql = "DELETE FROM users WHERE id = '".$_POST['user_id']."'";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute()) {
            echo "<script>alert('ลบข้อมูลสำเร็จ'); window.location.href='add_user.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล');</script>";
        }

        // $stmt->close();
    }
}
?>
