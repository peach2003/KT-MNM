<?php
class SinhVien
{
    private $conn;
    private $table_name = "SinhVien";

    public $MaSV;
    public $HoTen;
    public $GioiTinh;
    public $NgaySinh;
    public $Hinh;
    public $MaNganh;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy danh sách sinh viên
    public function getAll()
    {
        $query = "SELECT sv.*, nh.TenNganh 
                 FROM " . $this->table_name . " sv 
                 LEFT JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm sinh viên mới
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    MaSV = :MaSV,
                    HoTen = :HoTen,
                    GioiTinh = :GioiTinh,
                    NgaySinh = :NgaySinh,
                    Hinh = :Hinh,
                    MaNganh = :MaNganh";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaSV", $this->MaSV);
        $stmt->bindParam(":HoTen", $this->HoTen);
        $stmt->bindParam(":GioiTinh", $this->GioiTinh);
        $stmt->bindParam(":NgaySinh", $this->NgaySinh);
        $stmt->bindParam(":Hinh", $this->Hinh);
        $stmt->bindParam(":MaNganh", $this->MaNganh);

        return $stmt->execute();
    }

    // Cập nhật sinh viên
    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
                SET
                    HoTen = :HoTen,
                    GioiTinh = :GioiTinh,
                    NgaySinh = :NgaySinh,
                    Hinh = :Hinh,
                    MaNganh = :MaNganh
                WHERE MaSV = :MaSV";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaSV", $this->MaSV);
        $stmt->bindParam(":HoTen", $this->HoTen);
        $stmt->bindParam(":GioiTinh", $this->GioiTinh);
        $stmt->bindParam(":NgaySinh", $this->NgaySinh);
        $stmt->bindParam(":Hinh", $this->Hinh);
        $stmt->bindParam(":MaNganh", $this->MaNganh);

        return $stmt->execute();
    }

    // Xóa sinh viên
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaSV = :MaSV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaSV", $this->MaSV);
        return $stmt->execute();
    }

    // Lấy thông tin chi tiết sinh viên
    public function getOne()
    {
        $query = "SELECT sv.*, nh.TenNganh 
                 FROM " . $this->table_name . " sv 
                 LEFT JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh 
                 WHERE sv.MaSV = :MaSV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaSV", $this->MaSV);
        $stmt->execute();
        return $stmt;
    }

    public function getSinhVienByMaSV($maSV)
    {
        try {
            $sql = "SELECT * FROM SinhVien WHERE MaSV = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$maSV]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>