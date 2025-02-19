<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html"); // ถ้าไม่มี session ให้กลับไปหน้า login
    exit();
}
    $stmt = $pdo->query("SELECT id, position_name FROM position");
    $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT users.id,name,lname,sex,birth,position_name FROM users left join position on position.id =  users.position_id order by users.id DESC");
    $userdata = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmtedit = $pdo->query("SELECT users.id,username,password,name,lname,sex,birth,position_id FROM users left join position on position.id =  users.position_id where users.id =  '". $_GET['id']."' ");
    $stmtedit->execute();
    $editdata = $stmtedit->fetch(PDO::FETCH_ASSOC);

    // เมื่อกดปุ่มบันทึก
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $lname = $_POST['lname'];
    $sex = $_POST['sex'];
    $birth = $_POST['birth'];
    $position_id = $_POST['position_id'];

    $update_sql = "UPDATE users SET name='".$_POST['name']."', lname='".$_POST['lname']."', sex='".$_POST['sex']."', birth='".$_POST['birth']."', position_id='".$_POST['position_id']."' WHERE id='".$_GET['id']."' ";
    $stmt = $pdo->prepare($update_sql);

    if ($stmt->execute()) {
        echo "<script>alert('แก้ไขข้อมูลสำเร็จ'); window.location.href='add_user.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข้อมูลพนักงาน</title>
    <link href="bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <form  method="POST">
        <div class="row mb-3">
            <div class="col-md-6">
                <h2>แก้ไขข้อมูลพนักงาน</h2>
            </div>
            <div class="col-md-6" style="text-align: right;">
                <a href="logout.php" style="color:red">ออกจากระบบ</a>
            </div>
        </div>

    

        <!-- Row 2 -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">ชื่อ</label>
                <input type="text" name="name" class="form-control" value="<?= $editdata['name']; ?>" >
            </div>
            <div class="col-md-4">
                <label class="form-label">นามสกุล</label>
                <input type="text" name="lname" class="form-control" value="<?= $editdata['lname']; ?>" >
            </div>
            <div class="col-md-4">
                <label class="form-label">เพศ</label>
                <select name="sex" class="form-select" >
                    <option value="">เลือกเพศ</option>
                    <option value="ชาย" <?= ($editdata['sex'] == 'ชาย') ? 'selected' : ''; ?>>ชาย</option>
                    <option value="หญิง" <?= ($editdata['sex'] == 'หญิง') ? 'selected' : ''; ?>>หญิง</option>
                    <option value="อื่น ๆ" <?= ($editdata['sex'] == 'อื่น ๆ') ? 'selected' : ''; ?>>อื่นๆ</option>
                </select>
            </div>
        </div>
          <!-- Row 1 -->
          <div class="row mb-3">
          <div class="col-md-4">
                <label class="form-label">วันเกิด</label>
                <input type="date" name="birth" class="form-control" value="<?= $editdata['birth']; ?>" >
            </div>
            <div class="col-md-4">
                <label class="form-label">ตำแหน่ง</label>
                <select name="position_id" class="form-select" >
                <?php foreach ($positions as $position): ?>
                    <option value="<?= $position['id']; ?>" <?= ($editdata['position_id'] == $position['id']) ? 'selected' : ''; ?>>
                        <?= $position['position_name']; ?>
                    </option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">บันทึก</button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="add_user.php" class="btn btn-danger w-100">ยกเลิก</a>
            </div>
        </div>
        </div>  
       
     </form>
<div class="container mt-5">
     <h2 class="mb-4">รายชื่อพนักงาน</h2>
     <table class="table table-bordered">
        <thead class="table-primary" style="text-align: center;">
            <tr>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
                <th>เพศ</th>
                <th>วันเกิด</th>
                <th>ตำแหน่ง</th>
                <th>จัดการข้อมูล</th>
            </tr>
    </thead>
        <tbody>
            <?php foreach ($userdata as $user): ?>
            <tr>
                <td><?= $user['name']; ?></td>
                <td><?= $user['lname']; ?></td>
                <td><?= $user['sex']; ?></td>
                <td><?= $user['birth']; ?></td>
                <td><?= $user['position_name']; ?></td>
                <td style="text-align: center;">
                    <form action="uses_action.php" method="POST">
                        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                        <button type="submit" name="edit" class="btn btn-success btn-sm">แก้ไขข้อมูล</button>
                        <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?');">ลบข้อมูล</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
    
    
</body>

</html>