<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký học phần thành công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Quản lý học phần</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=sinhvien&action=index">Sinh viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=hocphan&action=index">Học phần</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="index.php?controller=hocphan&action=logout" class="btn btn-outline-light">Đăng xuất</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Đăng ký học phần thành công!</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <h5>Thông tin đăng ký:</h5>
                    <p><strong>Mã sinh viên:</strong> <?php echo htmlspecialchars($_SESSION['MaSV']); ?></p>
                    <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($_SESSION['HoTen'] ?? 'Chưa cập nhật'); ?>
                    </p>
                    <p><strong>Ngày đăng ký:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
                </div>

                <div class="text-center mt-4">
                    <a href="index.php?controller=hocphan&action=index" class="btn btn-primary">
                        Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>