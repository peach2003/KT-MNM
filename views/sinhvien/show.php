<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">Thông tin chi tiết sinh viên</h1>

        <?php $student = $result->fetch(PDO::FETCH_ASSOC); ?>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <?php if ($student['Hinh']): ?>
                            <img src="<?php echo htmlspecialchars($student['Hinh']); ?>" alt="Hình sinh viên"
                                class="img-fluid mb-3">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <table class="table">
                            <tr>
                                <th>Mã sinh viên:</th>
                                <td><?php echo htmlspecialchars($student['MaSV']); ?></td>
                            </tr>
                            <tr>
                                <th>Họ tên:</th>
                                <td><?php echo htmlspecialchars($student['HoTen']); ?></td>
                            </tr>
                            <tr>
                                <th>Giới tính:</th>
                                <td><?php echo htmlspecialchars($student['GioiTinh']); ?></td>
                            </tr>
                            <tr>
                                <th>Ngày sinh:</th>
                                <td><?php echo htmlspecialchars($student['NgaySinh']); ?></td>
                            </tr>
                            <tr>
                                <th>Ngành học:</th>
                                <td><?php echo htmlspecialchars($student['TenNganh']); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="index.php?action=edit&id=<?php echo $student['MaSV']; ?>" class="btn btn-warning">Sửa thông tin</a>
            <a href="index.php?action=index" class="btn btn-secondary">Quay lại danh sách</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>