<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/SinhVien.php';

class SinhVienController
{
    private $db;
    private $sinhVien;

    public function __construct()
    {
        try {
            $database = new Database();
            $this->db = $database->getConnection();
            if (!$this->db) {
                throw new Exception("Không thể kết nối đến cơ sở dữ liệu");
            }
            $this->sinhVien = new SinhVien($this->db);
        } catch (Exception $e) {
            die("Lỗi khởi tạo controller: " . $e->getMessage());
        }
    }

    // Hiển thị danh sách sinh viên
    public function index()
    {
        try {
            $result = $this->sinhVien->getAll();
            if (!$result) {
                throw new Exception("Không thể lấy danh sách sinh viên");
            }
            include __DIR__ . '/../views/sinhvien/index.php';
        } catch (Exception $e) {
            die("Lỗi hiển thị danh sách: " . $e->getMessage());
        }
    }

    // Hiển thị form thêm sinh viên
    public function create()
    {
        include __DIR__ . '/../views/sinhvien/create.php';
    }

    // Xử lý thêm sinh viên
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $this->sinhVien->MaSV = $_POST['MaSV'];
                $this->sinhVien->HoTen = $_POST['HoTen'];
                $this->sinhVien->GioiTinh = $_POST['GioiTinh'];
                $this->sinhVien->NgaySinh = $_POST['NgaySinh'];

                // Xử lý upload hình
                if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../uploads/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $uploadFile = $uploadDir . basename($_FILES['Hinh']['name']);
                    if (move_uploaded_file($_FILES['Hinh']['tmp_name'], $uploadFile)) {
                        $this->sinhVien->Hinh = 'uploads/' . basename($_FILES['Hinh']['name']);
                    }
                }

                $this->sinhVien->MaNganh = $_POST['MaNganh'];

                if ($this->sinhVien->create()) {
                    header('Location: index.php?action=index');
                } else {
                    throw new Exception("Không thể thêm sinh viên");
                }
            } catch (Exception $e) {
                die("Lỗi thêm sinh viên: " . $e->getMessage());
            }
        }
    }

    // Hiển thị form sửa sinh viên
    public function edit()
    {
        if (isset($_GET['id'])) {
            try {
                $this->sinhVien->MaSV = $_GET['id'];
                $result = $this->sinhVien->getOne();
                if (!$result) {
                    throw new Exception("Không tìm thấy sinh viên");
                }
                include __DIR__ . '/../views/sinhvien/edit.php';
            } catch (Exception $e) {
                die("Lỗi hiển thị form sửa: " . $e->getMessage());
            }
        }
    }

    // Xử lý cập nhật sinh viên
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $this->sinhVien->MaSV = $_POST['MaSV'];
                $this->sinhVien->HoTen = $_POST['HoTen'];
                $this->sinhVien->GioiTinh = $_POST['GioiTinh'];
                $this->sinhVien->NgaySinh = $_POST['NgaySinh'];

                // Xử lý upload hình
                if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../uploads/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $uploadFile = $uploadDir . basename($_FILES['Hinh']['name']);
                    if (move_uploaded_file($_FILES['Hinh']['tmp_name'], $uploadFile)) {
                        $this->sinhVien->Hinh = 'uploads/' . basename($_FILES['Hinh']['name']);
                    }
                } else {
                    $this->sinhVien->Hinh = $_POST['HinhCu'];
                }

                $this->sinhVien->MaNganh = $_POST['MaNganh'];

                if ($this->sinhVien->update()) {
                    header('Location: index.php?action=index');
                } else {
                    throw new Exception("Không thể cập nhật sinh viên");
                }
            } catch (Exception $e) {
                die("Lỗi cập nhật sinh viên: " . $e->getMessage());
            }
        }
    }

    // Xử lý xóa sinh viên
    public function delete()
    {
        if (isset($_GET['id'])) {
            try {
                $this->sinhVien->MaSV = $_GET['id'];
                if ($this->sinhVien->delete()) {
                    header('Location: index.php?action=index');
                } else {
                    throw new Exception("Không thể xóa sinh viên");
                }
            } catch (Exception $e) {
                die("Lỗi xóa sinh viên: " . $e->getMessage());
            }
        }
    }

    // Hiển thị chi tiết sinh viên
    public function show()
    {
        if (isset($_GET['id'])) {
            try {
                $this->sinhVien->MaSV = $_GET['id'];
                $result = $this->sinhVien->getOne();
                if (!$result) {
                    throw new Exception("Không tìm thấy sinh viên");
                }
                include __DIR__ . '/../views/sinhvien/show.php';
            } catch (Exception $e) {
                die("Lỗi hiển thị chi tiết: " . $e->getMessage());
            }
        }
    }
}
?>