<?php
require_once 'config.php';
require_once 'functions.php';
yeu_cau_dang_nhap();

//Thêm sách
if (isset($_POST['add_book'])) {
    yeu_cau_quyen(['admin', 'librarian']);

    $ten_sach    = trim($_POST['ten_sach']);
    $tac_gia     = trim($_POST['tac_gia']);
    $the_loai    = trim($_POST['the_loai']);
    $so_luong    = (int)$_POST['so_luong'];
    $nam_xb      = (int)$_POST['nam_xuat_ban'];
    $mo_ta       = trim($_POST['mo_ta'] ?? '');

    $stmt = mysqli_prepare($conn,
        "INSERT INTO sach (ten_sach, tac_gia, the_loai, so_luong, con_lai, nam_xuat_ban, mo_ta)
         VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssiiis", $ten_sach, $tac_gia, $the_loai, $so_luong, $so_luong, $nam_xb, $mo_ta);
    mysqli_stmt_execute($stmt);

    thong_bao('success', 'Thêm sách thành công!');
    header('Location: ../frontend/public/dashboard.php'); exit;
}

//Sửa sách
if (isset($_POST['update_book'])) {
    yeu_cau_quyen(['admin', 'librarian']);

    $id       = (int)$_POST['id'];
    $ten_sach = trim($_POST['ten_sach']);
    $tac_gia  = trim($_POST['tac_gia']);
    $the_loai = trim($_POST['the_loai']);
    $so_luong = (int)$_POST['so_luong'];
    $nam_xb   = (int)$_POST['nam_xuat_ban'];
    $mo_ta    = trim($_POST['mo_ta'] ?? '');

    // Tính lại số sách dựa trên chênh lệch số lượng
    $sach_cu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT so_luong, con_lai FROM sach WHERE id = $id"));
    $con_lai = $sach_cu['con_lai'] + ($so_luong - $sach_cu['so_luong']);
    $con_lai = max(0, $con_lai);

    $stmt = mysqli_prepare($conn,
        "UPDATE sach SET ten_sach=?, tac_gia=?, the_loai=?, so_luong=?, con_lai=?, nam_xuat_ban=?, mo_ta=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "sssiiisi", $ten_sach, $tac_gia, $the_loai, $so_luong, $con_lai, $nam_xb, $mo_ta, $id);
    mysqli_stmt_execute($stmt);

    thong_bao('success', 'Cập nhật sách thành công!');
    header('Location: ../frontend/public/dashboard.php'); exit;
}

//Xóa sách
if (isset($_GET['xoa'])) {
    yeu_cau_quyen(['admin', 'librarian']);

    $id = (int)$_GET['xoa'];
    $stmt = mysqli_prepare($conn, "DELETE FROM sach WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    thong_bao('success', 'Đã xóa sách thành công!');
    header('Location: ../frontend/public/dashboard.php'); exit;
}

