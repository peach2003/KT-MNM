<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký học phần</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php?controller=sinhvien&action=index">Quản lý sinh viên</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=sinhvien&action=index">Sinh Viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=hocphan&action=index">Học Phần</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=hocphan&action=danhSachDangKy">Đăng Kí (2)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Đăng Kí học phần</h2>

        <div class="card mb-4">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>MaHP</th>
                            <th>Tên Học Phần</th>
                            <th>Số Chỉ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tongTinChi = 0;
                        $soHocPhan = 0;
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)):
                            $tongTinChi += $row['SoTinChi'];
                            $soHocPhan++;
                            ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['MaHP']); ?></td>
                            <td><?php echo htmlspecialchars($row['TenHP']); ?></td>
                            <td><?php echo htmlspecialchars($row['SoTinChi']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="text-end">
                    <p class="text-danger">Số lượng học phần: <?php echo $soHocPhan; ?></p>
                    <p class="text-danger">Tổng số tín chỉ: <?php echo $tongTinChi; ?></p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-4">Thông tin Đăng kí</h3>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Mã số sinh Viên:</label>
                            <span class="form-control"><?php echo htmlspecialchars($_SESSION['MaSV']); ?></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Họ Tên Sinh Viên:</label>
                            <span
                                class="form-control"><?php echo isset($_SESSION['HoTen']) ? htmlspecialchars($_SESSION['HoTen']) : 'Nguyễn Văn A'; ?></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ngành Học:</label>
                            <span class="form-control">CNTT</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ngày Đăng Kí:</label>
                            <span class="form-control"><?php echo date('d/m/Y'); ?></span>
                        </div>
                        <div class="text-center">
                            <a href="index.php?controller=hocphan&action=luuDangKy" class="btn btn-success">Xác Nhận</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>