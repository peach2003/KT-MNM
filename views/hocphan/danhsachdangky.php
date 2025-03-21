<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách học phần đã đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php?controller=sinhvien&action=index">Quản lý học phần</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=sinhvien&action=index">Sinh viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=hocphan&action=index">Học phần</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=hocphan&action=danhSachDangKy">Đăng Kí (2)</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <span class="text-light me-3">Xin chào,
                        <?php echo htmlspecialchars($_SESSION['HoTen'] ?? ''); ?></span>
                    <a href="index.php?controller=hocphan&action=logout" class="btn btn-outline-light">Đăng xuất</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin sinh viên</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Mã sinh viên:</strong> <?php echo htmlspecialchars($_SESSION['MaSV']); ?></p>
                        <p><strong>Họ tên:</strong>
                            <?php echo htmlspecialchars($_SESSION['HoTen'] ?? 'Chưa cập nhật'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Ngành:</strong> <?php echo htmlspecialchars($_SESSION['Nganh'] ?? 'CNTT'); ?></p>
                        <p><strong>Ngày đăng ký:</strong> <?php echo date('d/m/Y'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Danh sách học phần đã chọn</h5>
            </div>
            <div class="card-body">
                <?php if ($result && $result->rowCount() > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã HP</th>
                            <th>Tên học phần</th>
                            <th>Số tín chỉ</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $stt = 1;
                            $tongTinChi = 0;
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)):
                                $tongTinChi += $row['SoTinChi'];
                                ?>
                        <tr>
                            <td><?php echo $stt++; ?></td>
                            <td><?php echo htmlspecialchars($row['MaHP']); ?></td>
                            <td><?php echo htmlspecialchars($row['TenHP']); ?></td>
                            <td><?php echo htmlspecialchars($row['SoTinChi']); ?></td>
                            <td>
                                <a href="index.php?controller=hocphan&action=huyDangKy&id=<?php echo $row['MaHP']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn hủy học phần này?')">
                                    Hủy
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-info">
                            <td colspan="3" class="text-end">
                                <strong>Tổng số tín chỉ:</strong>
                            </td>
                            <td colspan="2">
                                <strong><?php echo $tongTinChi; ?></strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="text-center mt-4">
                    <a href="index.php?controller=hocphan&action=index" class="btn btn-secondary me-2">
                        Chọn thêm học phần
                    </a>
                    <a href="index.php?controller=hocphan&action=luuDangKy" class="btn btn-primary"
                        onclick="return confirm('Bạn có chắc chắn muốn xác nhận đăng ký các học phần này?')">
                        Xác nhận đăng ký
                    </a>
                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    Bạn chưa chọn học phần nào.
                    <a href="index.php?controller=hocphan&action=index">Nhấn vào đây để chọn học phần</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>