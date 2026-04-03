<?php
session_start();
$may_chu       = "localhost";
$ten_dang_nhap = "root";
$mat_khau      = "";
$ten_csdl      = "ql_thuvien";
$ket_noi = mysqli_connect($may_chu, $ten_dang_nhap, $mat_khau, $ten_csdl);
if (!$ket_noi) {
    die("Kết nối cơ sở dữ liệu thất bại! Lỗi: " . mysqli_connect_error());
}
mysqli_set_charset($ket_noi, "utf8mb4");
// hàm chung
function isLoggedIn()   { return isset($_SESSION['user_id']); }
function hasRole($role) { return ($_SESSION['vai_tro'] ?? '') === $role; }
function redirect($url) { header("Location: $url"); exit(); }
?>
