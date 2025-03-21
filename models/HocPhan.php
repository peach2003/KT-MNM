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

    // Hủy đăng ký học phần
    public function huyDangKy($maSV, $maHP)
    {
        try {
            // Tìm MaDK từ MaSV
            $queryDangKy = "SELECT dk.MaDK 
                           FROM DangKy dk 
                           JOIN ChiTietDangKy ct ON dk.MaDK = ct.MaDK 
                           WHERE dk.MaSV = ? AND ct.MaHP = ?";
            $stmtDangKy = $this->conn->prepare($queryDangKy);
            $stmtDangKy->execute([$maSV, $maHP]);
            $dangKy = $stmtDangKy->fetch(PDO::FETCH_ASSOC);

            if ($dangKy) {
                // Xóa chi tiết đăng ký
                $queryDelete = "DELETE FROM ChiTietDangKy WHERE MaDK = ? AND MaHP = ?";
                $stmtDelete = $this->conn->prepare($queryDelete);
                $stmtDelete->execute([$dangKy['MaDK'], $maHP]);

                // Kiểm tra nếu không còn học phần nào thì xóa luôn đăng ký
                $queryCheck = "SELECT COUNT(*) as count FROM ChiTietDangKy WHERE MaDK = ?";
                $stmtCheck = $this->conn->prepare($queryCheck);
                $stmtCheck->execute([$dangKy['MaDK']]);
                $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if ($result['count'] == 0) {
                    $queryDeleteDK = "DELETE FROM DangKy WHERE MaDK = ?";
                    $stmtDeleteDK = $this->conn->prepare($queryDeleteDK);
                    $stmtDeleteDK->execute([$dangKy['MaDK']]);
                }

                return true;
            }
            return false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Thêm phương thức mới để hủy tất cả đăng ký
    public function huyTatCaDangKy($maSV)
    {
        try {
            // Bắt đầu transaction
            $this->conn->beginTransaction();

            // Lấy tất cả MaDK của sinh viên
            $queryDangKy = "SELECT MaDK FROM DangKy WHERE MaSV = ?";
            $stmtDangKy = $this->conn->prepare($queryDangKy);
            $stmtDangKy->execute([$maSV]);
            $danhSachDangKy = $stmtDangKy->fetchAll(PDO::FETCH_ASSOC);

            foreach ($danhSachDangKy as $dangKy) {
                // Xóa tất cả chi tiết đăng ký
                $queryDeleteChiTiet = "DELETE FROM ChiTietDangKy WHERE MaDK = ?";
                $stmtDeleteChiTiet = $this->conn->prepare($queryDeleteChiTiet);
                $stmtDeleteChiTiet->execute([$dangKy['MaDK']]);

                // Xóa đăng ký
                $queryDeleteDangKy = "DELETE FROM DangKy WHERE MaDK = ?";
                $stmtDeleteDangKy = $this->conn->prepare($queryDeleteDangKy);
                $stmtDeleteDangKy->execute([$dangKy['MaDK']]);
            }

            // Commit transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Rollback nếu có lỗi
            $this->conn->rollBack();
            throw $e;
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

            // Thêm vào bảng ChiTietDangKy
            $sql = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            foreach ($danhSachHP as $maHP) {
                $stmt->execute([$maDK, $maHP]);
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>