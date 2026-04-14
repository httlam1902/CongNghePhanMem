<?php
require_once 'config.php';
require_once 'functions.php';
yeu_cau_quyen('admin');

// ── Khóa / Mở khóa ──
if (isset($_GET['khoa'])) {
    $id = (int)$_GET['khoa'];
    if ($id !== $_SESSION['user_id']) { // Không tự khóa mình
        $stmt = mysqli_prepare($conn, "UPDATE nguoi_dung SET trang_thai = 1 - trang_thai WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        thong_bao('success', 'Đã cập nhật trạng thái tài khoản!');
    }
    header('Location: ../frontend/public/dashboard.php?trang=nguoi_dung'); exit;
}

//Đổi vai trò
if (isset($_POST['doi_quyen'])) {
    $id      = (int)$_POST['id'];
    $vai_tro = $_POST['vai_tro'];

    if (in_array($vai_tro, ['admin','librarian','reader']) && $id !== $_SESSION['user_id']) {
        $stmt = mysqli_prepare($conn, "UPDATE nguoi_dung SET vai_tro = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $vai_tro, $id);
        mysqli_stmt_execute($stmt);
        thong_bao('success', 'Đã cập nhật vai trò thành công!');
    }
    header('Location: ../frontend/public/dashboard.php?trang=nguoi_dung'); exit;
}

