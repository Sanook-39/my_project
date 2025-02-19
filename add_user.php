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
        <form action="saveuser.php" method="POST">
        <div class="row mb-3">
            <div class="col-md-6">
                <h2>จัดการข้อมูลพนักงาน</h2>
            </div>
            <div class="col-md-6" style="text-align: right;">
                <a href="logout.php" style="color:red">ออกจากระบบ</a>
            </div>
        </div>
        <!-- Row 1 -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">ตำแหน่ง</label>
                <select name="position_id" class="form-select" required>
                <option value="">-- เลือกตำแหน่ง --</option>
                <?php foreach ($positions as $position): ?>
                    <option value="<?= $position['id']; ?>"><?= $position['position_name']; ?></option>
                <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">ชื่อ</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">นามสกุล</label>
                <input type="text" name="lname" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">เพศ</label>
                <select name="sex" class="form-select" required>
                    <option value="">เลือกเพศ</option>
                    <option value="ชาย">ชาย</option>
                    <option value="หญิง">หญิง</option>
                    <option value="อื่นๆ">อื่นๆ</option>
                </select>
            </div>
        </div>
        
        <!-- Row 3 -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">วันเกิด</label>
                <input type="date" name="birth" class="form-control" required>
            </div>
            
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-50">บันทึก</button>
            </div>
        </div>
        </div>  
       
     </form>
<div class="container mt-5">
     <h2 class="mb-4">รายชื่อพนักงาน</h2>
     <form method="GET" action="">
        <div class="row mb-3">
        <div class="col-md-7"></div>
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="ค้นหาพนักงาน..." required>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100"><span style="color:white">ค้นหา</span></button>
        </div>
        <div class="col-md-1">
            <a href="add_user.php" class="btn btn-danger w-100">ยกเลิก</a>
        </div>
        </div>
    </form>
<?php
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        $stmt = $pdo->query("SELECT users.*,position_name FROM users left join position on position.id =  users.position_id  WHERE name LIKE '%".$_GET['search']."%' OR lname LIKE '%".$_GET['search']."%' order by users.id DESC");
        $searchrdata = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
?>
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
            <?php foreach ($searchrdata as $searchrdata): ?>
            <tr>
                <td><?= $searchrdata['name']; ?></td>
                <td><?= $searchrdata['lname']; ?></td>
                <td><?= $searchrdata['sex']; ?></td>
                <td><?= $searchrdata['birth']; ?></td>
                <td><?= $searchrdata['position_name']; ?></td>
                <td style="text-align: center;">
                    <form action="uses_action.php" method="POST">
                        <input type="hidden" name="user_id" value="<?= $searchrdata['id']; ?>">
                        <button type="submit" name="edit" class="btn btn-success btn-sm">แก้ไขข้อมูล</button>
                        <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?');">ลบข้อมูล</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php }else{ ?>
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
<?php } ?>
</div>
    
    
</body>

</html>