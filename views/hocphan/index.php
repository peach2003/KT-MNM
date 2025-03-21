<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách học phần</title>
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
                        <a class="nav-link" href="index.php?controller=hocphan&action=index">Học Phần</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=hocphan&action=danhSachDangKy">Đăng Kí (2)</a>
                    </li>
                </ul>
                <?php if (isset($_SESSION['MaSV'])): ?>
                    <div class="d-flex">
                        <a href="index.php?controller=hocphan&action=logout" class="btn btn-outline-light">Đăng xuất</a>
                    </div>
                <?php else: ?>
                    <div class="d-flex">
                        <a href="index.php?controller=hocphan&action=login" class="btn btn-outline-light">Đăng nhập</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Danh sách học phần</h1>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Mã học phần</th>
                    <th>Tên học phần</th>
                    <th>Số tín chỉ</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['MaHP']); ?></td>
                        <td><?php echo htmlspecialchars($row['TenHP']); ?></td>
                        <td><?php echo htmlspecialchars($row['SoTinChi']); ?></td>
                        <td>
                            <?php if (isset($_SESSION['MaSV'])): ?>
                                <a href="index.php?controller=hocphan&action=dangKy&id=<?php echo $row['MaHP']; ?>"
                                    class="btn btn-success btn-sm">Đăng ký</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>