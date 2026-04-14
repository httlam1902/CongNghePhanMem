<?php
require_once 'config.php';
require_once 'functions.php';
yeu_cau_dang_nhap();

//Gửi yêu cầu mượn
if (isset($_POST['muon'])) {
    yeu_cau_quyen('reader');

    $user_id = $_SESSION['user_id'];
    $ma_sach = (int)$_POST['ma_sach'];

    // Kiểm tra sách còn không
    $sach = mysqli_fetch_assoc(mysqli_query($conn, "SELECT con_lai FROM sach WHERE id = $ma_sach"));
    if (!$sach || $sach['con_lai'] < 1) {
        thong_bao('error', 'Sách đã hết, vui lòng chọn sách khác!');
        header('Location: ../frontend/public/dashboard.php'); exit;
    }

    // Kiểm tra đang mượn cuốn này chưa
    $stmt = mysqli_prepare($conn,
        "SELECT id FROM muon_sach WHERE ma_nguoi_dung=? AND ma_sach=? AND trang_thai != 'da_tra'");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $ma_sach);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        thong_bao('error', 'Bạn đang mượn cuốn sách này rồi!');
        header('Location: ../frontend/public/dashboard.php'); exit;
    }

    // Tạo yêu cầu + giảm con_lai
    $stmt = mysqli_prepare($conn,
        "INSERT INTO muon_sach (ma_nguoi_dung, ma_sach, trang_thai) VALUES (?, ?, 'cho_duyet')");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $ma_sach);
    mysqli_stmt_execute($stmt);

    mysqli_query($conn, "UPDATE sach SET con_lai = con_lai - 1 WHERE id = $ma_sach");

    thong_bao('success', 'Đã gửi yêu cầu mượn, bạn hãy chờ thủ thư xác nhận!');
    header('Location: ../frontend/public/dashboard.php'); exit;
}

//Duyệt mượn
if (isset($_GET['duyet'])) {
    yeu_cau_quyen(['admin', 'librarian']);

    $id       = (int)$_GET['duyet'];
    $so_ngay  = SO_NGAY_MUON;

    $stmt = mysqli_prepare($conn,
        "UPDATE muon_sach SET trang_thai='dang_muon', ngay_muon=CURDATE(),
         han_tra=DATE_ADD(CURDATE(), INTERVAL ? DAY) WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ii", $so_ngay, $id);
    mysqli_stmt_execute($stmt);

    thong_bao('success', 'Đã duyệt yêu cầu mượn!');
    header('Location: ../frontend/public/dashboard.php'); exit;
}

//Từ chối mượn
if (isset($_GET['tu_choi'])) {
    yeu_cau_quyen(['admin', 'librarian']);

    $id = (int)$_GET['tu_choi'];

    // Hoàn lại con_lai
    $phieu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ma_sach FROM muon_sach WHERE id = $id"));
    if ($phieu) {
        mysqli_query($conn, "UPDATE sach SET con_lai = con_lai + 1 WHERE id = {$phieu['ma_sach']}");
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM muon_sach WHERE id=? AND trang_thai='cho_duyet'");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    thong_bao('success', 'Đã từ chối yêu cầu mượn!');
    header('Location: ../frontend/public/dashboard.php'); exit;
}

//Thu sách / Trả
if (isset($_GET['tra'])) {
    yeu_cau_quyen(['admin', 'librarian']);

    $id = (int)$_GET['tra'];

    $phieu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM muon_sach WHERE id = $id"));
    $phi_phat = tinh_phi_phat($phieu['han_tra'], $phieu['trang_thai']);

    $stmt = mysqli_prepare($conn,
        "UPDATE muon_sach SET trang_thai='da_tra', ngay_tra=CURDATE(), phi_phat=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "di", $phi_phat, $id);
    mysqli_stmt_execute($stmt);

    // Tăng lại con_lai
    mysqli_query($conn, "UPDATE sach SET con_lai = con_lai + 1 WHERE id = {$phieu['ma_sach']}");

    $tb = $phi_phat > 0
        ? "Trả sách thành công! Phí phạt: " . dinh_dang_tien($phi_phat)
        : "Trả sách thành công, không có phí phạt!";
    thong_bao('success', $tb);
    header('Location: ../frontend/public/dashboard.php'); exit;
}

