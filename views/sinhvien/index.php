<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">Danh sách sinh viên</h1>
        <a href="index.php?action=create" class="btn btn-primary mb-3">Thêm sinh viên</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Mã SV</th>
                    <th>Họ Tên</th>
                    <th>Giới Tính</th>
                    <th>Ngày Sinh</th>
                    <th>Hình</th>
                    <th>Mã Ngành</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['MaSV']); ?></td>
                    <td><?php echo htmlspecialchars($row['HoTen']); ?></td>
                    <td><?php echo htmlspecialchars($row['GioiTinh']); ?></td>
                    <td><?php echo htmlspecialchars($row['NgaySinh']); ?></td>
                    <td>
                        <?php if ($row['Hinh']): ?>
                        <img src="<?php echo htmlspecialchars($row['Hinh']); ?>" alt="Hình sinh viên"
                            style="max-width: 50px;">
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['TenNganh']); ?></td>
                    <td>
                        <a href="index.php?action=show&id=<?php echo $row['MaSV']; ?>" class="btn btn-info btn-sm">Chi
                            tiết</a>
                        <a href="index.php?action=edit&id=<?php echo $row['MaSV']; ?>"
                            class="btn btn-warning btn-sm">Sửa</a>
                        <a href="index.php?action=delete&id=<?php echo $row['MaSV']; ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>