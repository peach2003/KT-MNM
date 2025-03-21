<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/HocPhan.php';
require_once __DIR__ . '/../models/SinhVien.php';

class HocPhanController
{
    private $conn;
    private $hocPhan;
    private $sinhVien;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
        $this->hocPhan = new HocPhan($this->conn);
        $this->sinhVien = new SinhVien($this->conn);
    }

    // Hiển thị danh sách học phần
    public function index()
    {
        try {
            $stmt = $this->hocPhan->getAll();
            if (!$stmt) {
                throw new Exception("Không thể lấy danh sách học phần");
            }
            $result = $stmt;
            include __DIR__ . '/../views/hocphan/index.php';
        } catch (Exception $e) {
            die("Lỗi hiển thị danh sách: " . $e->getMessage());
        }
    }

    // Hiển thị form đăng nhập
    public function login()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $maSV = $_POST['MaSV'] ?? '';

                // Kiểm tra thông tin đăng nhập từ model SinhVien
                $sinhVien = $this->sinhVien->getSinhVienByMaSV($maSV);

                if ($sinhVien) {
                    $_SESSION['MaSV'] = $sinhVien['MaSV'];
                    $_SESSION['HoTen'] = $sinhVien['HoTen'];
                    $_SESSION['Nganh'] = $sinhVien['Nganh'];
                    header('Location: index.php?controller=hocphan&action=index');
                    exit;
                } else {
                    throw new Exception("Mã sinh viên không tồn tại");
                }
            }
            include __DIR__ . '/../views/hocphan/login.php';
        } catch (Exception $e) {
            die("Lỗi đăng nhập: " . $e->getMessage());
        }
    }

    // Hiển thị danh sách học phần đã đăng ký
    public function danhSachDangKy()
    {
        try {
            if (!isset($_SESSION['MaSV'])) {
                header('Location: index.php?controller=hocphan&action=login');
                exit;
            }

            // Lấy danh sách học phần đã chọn từ session
            if (isset($_SESSION['hocphan_dangky']) && !empty($_SESSION['hocphan_dangky'])) {
                $result = $this->hocPhan->getHocPhanByIds($_SESSION['hocphan_dangky']);
            } else {
                $result = null;
            }

            include __DIR__ . '/../views/hocphan/danhsachdangky.php';
        } catch (Exception $e) {
            die("Lỗi hiển thị danh sách đăng ký: " . $e->getMessage());
        }
    }

    // Hiển thị form đăng ký học phần mới
    public function formDangKy()
    {
        try {
            if (!isset($_SESSION['MaSV'])) {
                header('Location: index.php?controller=hocphan&action=login');
                exit;
            }

            $stmt = $this->hocPhan->getAll();
            if (!$stmt) {
                throw new Exception("Không thể lấy danh sách học phần");
            }
            $result = $stmt;
            include __DIR__ . '/../views/hocphan/dangky.php';
        } catch (Exception $e) {
            die("Lỗi hiển thị form đăng ký: " . $e->getMessage());
        }
    }

    // Xử lý đăng ký học phần
    public function dangKy()
    {
        try {
            if (!isset($_SESSION['MaSV']) || !isset($_GET['id'])) {
                throw new Exception("Thông tin không hợp lệ");
            }

            // Thêm học phần vào session
            if (!isset($_SESSION['hocphan_dangky'])) {
                $_SESSION['hocphan_dangky'] = [];
            }

            // Kiểm tra nếu học phần chưa được chọn
            if (!in_array($_GET['id'], $_SESSION['hocphan_dangky'])) {
                $_SESSION['hocphan_dangky'][] = $_GET['id'];
            }

            // Chuyển đến trang danh sách đăng ký
            header('Location: index.php?controller=hocphan&action=danhSachDangKy');
            exit;
        } catch (Exception $e) {
            die("Lỗi đăng ký học phần: " . $e->getMessage());
        }
    }

    // Xử lý hủy đăng ký học phần
    public function huyDangKy()
    {
        try {
            if (!isset($_SESSION['MaSV'])) {
                throw new Exception("Vui lòng đăng nhập để thực hiện chức năng này");
            }

            if (isset($_GET['id']) && isset($_SESSION['hocphan_dangky'])) {
                // Tìm và xóa học phần khỏi session
                $key = array_search($_GET['id'], $_SESSION['hocphan_dangky']);
                if ($key !== false) {
                    unset($_SESSION['hocphan_dangky'][$key]);
                    $_SESSION['hocphan_dangky'] = array_values($_SESSION['hocphan_dangky']); // Reindex array
                }
            }

            header('Location: index.php?controller=hocphan&action=danhSachDangKy');
            exit;
        } catch (Exception $e) {
            die("Lỗi hủy đăng ký học phần: " . $e->getMessage());
        }
    }

    // Xử lý đăng xuất
    public function logout()
    {
        unset($_SESSION['MaSV']);
        header('Location: index.php?controller=hocphan&action=login');
    }

    // Xử lý lưu thông tin đăng ký học phần
    public function luuDangKy()
    {
        try {
            if (!isset($_SESSION['MaSV'])) {
                throw new Exception("Vui lòng đăng nhập để thực hiện chức năng này");
            }

            if (!isset($_SESSION['hocphan_dangky']) || empty($_SESSION['hocphan_dangky'])) {
                throw new Exception("Chưa có học phần nào được chọn");
            }

            $maSV = $_SESSION['MaSV'];
            $ngayDK = date('Y-m-d H:i:s');
            $danhSachHP = $_SESSION['hocphan_dangky'];

            // Lưu thông tin đăng ký vào CSDL
            if ($this->hocPhan->luuDangKy($maSV, $ngayDK, $danhSachHP)) {
                // Xóa danh sách học phần đã chọn khỏi session
                unset($_SESSION['hocphan_dangky']);

                // Chuyển hướng đến trang thông báo thành công
                header('Location: index.php?controller=hocphan&action=thongBaoThanhCong');
                exit;
            } else {
                throw new Exception("Không thể lưu thông tin đăng ký");
            }
        } catch (Exception $e) {
            die("Lỗi lưu đăng ký học phần: " . $e->getMessage());
        }
    }

    // Hiển thị thông báo đăng ký thành công
    public function thongBaoThanhCong()
    {
        include __DIR__ . '/../views/hocphan/thongbaothanhcong.php';
    }
}
?>