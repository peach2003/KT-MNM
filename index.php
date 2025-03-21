<?php
// Khởi tạo session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hiển thị tất cả lỗi PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Xác định controller
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'sinhvien';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Load controller tương ứng
switch ($controller) {
    case 'sinhvien':
        require_once 'controllers/SinhVienController.php';
        $controller = new SinhVienController();
        break;
    case 'hocphan':
        require_once 'controllers/HocPhanController.php';
        $controller = new HocPhanController();
        break;
    default:
        require_once 'controllers/SinhVienController.php';
        $controller = new SinhVienController();
}

// Gọi action tương ứng
try {
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        throw new Exception("Không tìm thấy action '$action'");
    }
} catch (Exception $e) {
    die("Lỗi: " . $e->getMessage());
}
?>