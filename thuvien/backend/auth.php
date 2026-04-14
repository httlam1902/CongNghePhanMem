<?php
require_once 'config.php';
require_once 'functions.php';

//Đăng nhập
if (isset($_POST['login'])) {
    $ten_dn   = trim($_POST['ten_dang_nhap']);
    $mat_khau = $_POST['mat_khau'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM nguoi_dung WHERE ten_dang_nhap = ?");
    mysqli_stmt_bind_param($stmt, "s", $ten_dn);
    mysqli_stmt_execute($stmt);
    $nguoi_dung = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$nguoi_dung || !kiem_tra_mk($mat_khau, $nguoi_dung['mat_khau'])) {
        thong_bao('error', 'Tên đăng nhập hoặc mật khẩu không trùng khớp!');
        header('Location: ../frontend/public/index.php'); exit;
    }
    if (!$nguoi_dung['trang_thai']) {
        thong_bao('error', 'Tài khoản của bạn đã bị khóa!');
        header('Location: ../frontend/public/index.php'); exit;
    }

    $_SESSION['user_id']       = $nguoi_dung['id'];
    $_SESSION['ho_ten']        = $nguoi_dung['ho_ten'];
    $_SESSION['ten_dang_nhap'] = $nguoi_dung['ten_dang_nhap'];
    $_SESSION['vai_tro']       = $nguoi_dung['vai_tro'];

    header('Location: ../frontend/public/dashboard.php'); exit;
}

//Đăng ký
if (isset($_POST['register'])) {
    $ten_dn   = trim($_POST['ten_dang_nhap']);
    $ho_ten   = trim($_POST['ho_ten']);
    $email    = trim($_POST['email']);
    $mat_khau = $_POST['mat_khau'];
    $xac_nhan = $_POST['xac_nhan'];

    if ($mat_khau !== $xac_nhan) {
        thong_bao('error', 'Mật khẩu xác nhận không trùng khớp!');
        header('Location: ../frontend/public/index.php?tab=register'); exit;
    }

    //Kiểm tra bị trùng
    $stmt = mysqli_prepare($conn, "SELECT id FROM nguoi_dung WHERE ten_dang_nhap = ? OR email = ?");
    mysqli_stmt_bind_param($stmt, "ss", $ten_dn, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        thong_bao('error', 'Tên đăng nhập hoặc email đã tồn tại!');
        header('Location: ../frontend/public/index.php?tab=register'); exit;
    }

    $mk_hash = ma_hoa_mk($mat_khau);
    $stmt = mysqli_prepare($conn,
        "INSERT INTO nguoi_dung (ten_dang_nhap, mat_khau, ho_ten, email, vai_tro) VALUES (?,?,?,?,'reader')");
    mysqli_stmt_bind_param($stmt, "ssss", $ten_dn, $mk_hash, $ho_ten, $email);
    mysqli_stmt_execute($stmt);

    thong_bao('success', 'Đăng ký tài khoản thành công! Vui lòng đăng nhập!');
    header('Location: ../frontend/public/index.php'); exit;
}

//Đăng xuất
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../frontend/public/index.php'); exit;
}

