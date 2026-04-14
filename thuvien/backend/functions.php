<?php
require_once __DIR__ . '/config.php';

// Mật khẩu
function ma_hoa_mk($mk)          { return password_hash($mk, PASSWORD_BCRYPT); }
function kiem_tra_mk($mk, $hash) { return password_verify($mk, $hash); }

// Kiểm tra đăng nhập / quyền
function da_dang_nhap()  { return !empty($_SESSION['user_id']); }
function la_admin()      { return ($_SESSION['vai_tro'] ?? '') === 'admin'; }
function la_thu_thu()    { return ($_SESSION['vai_tro'] ?? '') === 'librarian'; }
function la_doc_gia()    { return ($_SESSION['vai_tro'] ?? '') === 'reader'; }
function co_quyen_ql()   { return la_admin() || la_thu_thu(); }

function yeu_cau_dang_nhap() {
    if (!da_dang_nhap()) { header('Location: index.php'); exit; }
}
function yeu_cau_quyen($vai_tro) {
    yeu_cau_dang_nhap();
    if (!in_array($_SESSION['vai_tro'], (array)$vai_tro)) {
        header('Location: dashboard.php'); exit;
    }
}

// Ngày tháng 
function hom_nay()              { return date('Y-m-d'); }
function cong_ngay($ngay, $n)   { return date('Y-m-d', strtotime("$ngay +$n days")); }
function dinh_dang_ngay($ngay)  { return $ngay ? date('d/m/Y', strtotime($ngay)) : '—'; }
function so_ngay_lech($tu, $den){ return (int)((strtotime($den) - strtotime($tu)) / 86400); }

// Phí phạt 
function tinh_phi_phat($han_tra, $trang_thai) {
    if ($trang_thai !== 'dang_muon' || hom_nay() <= $han_tra) return 0;
    return so_ngay_lech($han_tra, hom_nay()) * PHI_TRE_MOI_NGAY;
}

// Định dạng tiền 
function dinh_dang_tien($so_tien) { return number_format($so_tien, 0, ',', '.') . 'đ'; }

// Flash message
function thong_bao($loai, $noi_dung) { $_SESSION['flash'] = ['type' => $loai, 'msg' => $noi_dung]; }
function lay_thong_bao() {
    if (!empty($_SESSION['flash'])) {
        $tb = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $tb;
    }
    return null;
}

// Badge HTML
function badge_vai_tro($vai_tro) {
    $ds = ['admin' => ['do','Admin'], 'librarian' => ['xanh','Thủ thư'], 'reader' => ['xanh-la','Độc giả']];
    [$mau, $ten] = $ds[$vai_tro] ?? ['xanh','?'];
    return "<span class='badge badge-$mau'>$ten</span>";
}
function badge_trang_thai($trang_thai) {
    $ds = ['cho_duyet' => ['vang','⏳ Chờ duyệt'], 'dang_muon' => ['xanh','📖 Đang mượn'], 'da_tra' => ['xanh-la','✅ Đã trả']];
    [$mau, $ten] = $ds[$trang_thai] ?? ['xanh','?'];
    return "<span class='badge badge-$mau'>$ten</span>";
}
function badge_so_luong($con_lai) {
    if ($con_lai <= 0) return "<span class='badge badge-do'>Hết sách</span>";
    if ($con_lai <= 2) return "<span class='badge badge-vang'>Còn $con_lai</span>";
    return "<span class='badge badge-xanh-la'>Còn $con_lai</span>";
}

//Token reset mật khẩu
function tao_token() { return bin2hex(random_bytes(32)); }

