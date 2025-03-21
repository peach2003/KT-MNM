<?php
class HocPhan
{
    private $conn;
    private $table_name = "HocPhan";

    public $MaHP;
    public $TenHP;
    public $SoTinChi;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy danh sách học phần
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy thông tin một học phần
    public function getOne()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaHP = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->MaHP]);
        return $stmt;
    }

    // Đăng ký học phần cho sinh viên
    public function dangKyHocPhan($maSV)
    {
        try {
            // Bắt đầu transaction
            $this->conn->beginTransaction();

            // Tạo đăng ký mới
            $queryDangKy = "INSERT INTO DangKy (NgayDK, MaSV) VALUES (NOW(), ?)";
            $stmtDangKy = $this->conn->prepare($queryDangKy);
            $stmtDangKy->execute([$maSV]);

            // Lấy ID đăng ký vừa tạo
            $maDK = $this->conn->lastInsertId();

            // Thêm chi tiết đăng ký
            $queryChiTiet = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)";
            $stmtChiTiet = $this->conn->prepare($queryChiTiet);
            $stmtChiTiet->execute([$maDK, $this->MaHP]);

            // Commit transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Rollback nếu có lỗi
            $this->conn->rollBack();
            throw $e;
        }
    }

    // Lưu thông tin đăng ký học phần
    public function luuDangKy($maSV, $ngayDK, $danhSachHP)
    {
        try {
            $this->conn->beginTransaction();

            // Thêm vào bảng DangKy
            $sql = "INSERT INTO DangKy (NgayDK, MaSV) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$ngayDK, $maSV]);
            $maDK = $this->conn->lastInsertId();

            // Thêm vào bảng ChiTietDangKy và cập nhật số lượng
            $sqlChiTiet = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)";
            $sqlCapNhat = "UPDATE HocPhan SET SoLuong = SoLuong - 1 WHERE MaHP = ?";

            $stmtChiTiet = $this->conn->prepare($sqlChiTiet);
            $stmtCapNhat = $this->conn->prepare($sqlCapNhat);

            foreach ($danhSachHP as $maHP) {
                // Thêm chi tiết đăng ký
                $stmtChiTiet->execute([$maDK, $maHP]);

                // Cập nhật số lượng
                $stmtCapNhat->execute([$maHP]);
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Hủy đăng ký học phần và cập nhật số lượng
    public function huyDangKy($maSV, $maHP)
    {
        try {
            $this->conn->beginTransaction();

            // Lấy MaDK từ bảng DangKy
            $sqlMaDK = "SELECT dk.MaDK 
                       FROM DangKy dk 
                       WHERE dk.MaSV = ?";
            $stmtMaDK = $this->conn->prepare($sqlMaDK);
            $stmtMaDK->execute([$maSV]);
            $maDK = $stmtMaDK->fetch(PDO::FETCH_COLUMN);

            if ($maDK) {
                // Xóa từ bảng ChiTietDangKy
                $sqlXoaChiTiet = "DELETE FROM ChiTietDangKy WHERE MaDK = ? AND MaHP = ?";
                $stmtXoaChiTiet = $this->conn->prepare($sqlXoaChiTiet);
                $stmtXoaChiTiet->execute([$maDK, $maHP]);

                // Cập nhật số lượng
                $sqlCapNhat = "UPDATE HocPhan SET SoLuong = SoLuong + 1 WHERE MaHP = ?";
                $stmtCapNhat = $this->conn->prepare($sqlCapNhat);
                $stmtCapNhat->execute([$maHP]);

                // Kiểm tra nếu không còn học phần nào trong đăng ký thì xóa luôn đăng ký
                $sqlKiemTra = "SELECT COUNT(*) FROM ChiTietDangKy WHERE MaDK = ?";
                $stmtKiemTra = $this->conn->prepare($sqlKiemTra);
                $stmtKiemTra->execute([$maDK]);
                $soLuong = $stmtKiemTra->fetch(PDO::FETCH_COLUMN);

                if ($soLuong == 0) {
                    $sqlXoaDangKy = "DELETE FROM DangKy WHERE MaDK = ?";
                    $stmtXoaDangKy = $this->conn->prepare($sqlXoaDangKy);
                    $stmtXoaDangKy->execute([$maDK]);
                }
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Hủy tất cả đăng ký của sinh viên
    public function huyTatCaDangKy($maSV)
    {
        try {
            $this->conn->beginTransaction();

            // Lấy MaDK và danh sách học phần từ bảng DangKy và ChiTietDangKy
            $sqlDanhSach = "SELECT dk.MaDK, ct.MaHP 
                           FROM DangKy dk 
                           JOIN ChiTietDangKy ct ON dk.MaDK = ct.MaDK 
                           WHERE dk.MaSV = ?";
            $stmtDanhSach = $this->conn->prepare($sqlDanhSach);
            $stmtDanhSach->execute([$maSV]);
            $danhSach = $stmtDanhSach->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($danhSach)) {
                // Cập nhật số lượng cho từng học phần
                $sqlCapNhat = "UPDATE HocPhan SET SoLuong = SoLuong + 1 WHERE MaHP = ?";
                $stmtCapNhat = $this->conn->prepare($sqlCapNhat);

                foreach ($danhSach as $item) {
                    $stmtCapNhat->execute([$item['MaHP']]);
                }

                // Xóa từ bảng ChiTietDangKy
                $sqlXoaChiTiet = "DELETE ct FROM ChiTietDangKy ct 
                                 JOIN DangKy dk ON ct.MaDK = dk.MaDK 
                                 WHERE dk.MaSV = ?";
                $stmtXoaChiTiet = $this->conn->prepare($sqlXoaChiTiet);
                $stmtXoaChiTiet->execute([$maSV]);

                // Xóa từ bảng DangKy
                $sqlXoaDangKy = "DELETE FROM DangKy WHERE MaSV = ?";
                $stmtXoaDangKy = $this->conn->prepare($sqlXoaDangKy);
                $stmtXoaDangKy->execute([$maSV]);
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Lấy danh sách học phần đã đăng ký của sinh viên
    public function getHocPhanDaDangKy($maSV)
    {
        $query = "SELECT hp.*, ctdk.MaDK 
                 FROM " . $this->table_name . " hp
                 JOIN ChiTietDangKy ctdk ON hp.MaHP = ctdk.MaHP
                 JOIN DangKy dk ON ctdk.MaDK = dk.MaDK
                 WHERE dk.MaSV = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$maSV]);
        return $stmt;
    }

    // Lấy danh sách học phần theo mảng mã học phần
    public function getHocPhanByIds($maHPs)
    {
        try {
            $placeholders = str_repeat('?,', count($maHPs) - 1) . '?';
            $sql = "SELECT * FROM HocPhan WHERE MaHP IN ($placeholders)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($maHPs);
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>