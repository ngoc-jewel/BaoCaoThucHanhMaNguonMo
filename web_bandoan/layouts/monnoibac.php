<?php
include("includes/database.php");

// Lấy danh sách món nổi bật
$stmt = $conn->prepare("SELECT * FROM MonNoiBat ORDER BY created_at DESC LIMIT 6");
$stmt->execute();
$noibat = $stmt->fetchAll(PDO::FETCH_ASSOC);

$items = [];

foreach ($noibat as $row) {
    $loai = $row['loai'];
    $id   = $row['mon_id'];

    // Lấy dữ liệu theo loại
    if ($loai == 'MonNuoc') {
        $q = $conn->prepare("SELECT TenNuoc AS TenMon, Gia, HinhAnh, ChiTiet 
                             FROM MonNuoc WHERE id = ?");
    }
    elseif ($loai == 'KhuyenMai') {
        $q = $conn->prepare("SELECT ten_km AS TenMon, giam_gia AS Gia, NULL AS HinhAnh, mo_ta AS ChiTiet 
                             FROM khuyenmai WHERE id = ?");
    }
    elseif ($loai == 'Combo') {
        $q = $conn->prepare("SELECT TenCombo AS TenMon, GiaCuoi AS Gia, HinhAnh, ChiTiet 
                             FROM combo WHERE id = ?");
    }
    else { // MonAn
        $q = $conn->prepare("SELECT TenMon, Gia, HinhAnh, ChiTiet 
                             FROM MonAn WHERE id = ?");
    }

    $q->execute([$id]);
    $mon = $q->fetch(PDO::FETCH_ASSOC);

    if ($mon) {
        $items[] = [
            'TenMon'  => $mon['TenMon'],
            'Gia'     => $mon['Gia'],
            'HinhAnh' => $mon['HinhAnh'],
            'ChiTiet' => $mon['ChiTiet'],
            'Loai'    => $loai
        ];
    }
}
?>

<style>
.monnoibat-section {
    padding: 40px 20px;
    background: linear-gradient(135deg, #fff1d6, #ffd7b5);
    border-radius: 12px;
    margin: 20px auto;
    max-width: 1200px;
}

.monnoibat-section h2 {
    text-align: center;
    font-size: 28px;
    color: #d35400;
    margin-bottom: 25px;
    font-weight: bold;
}

.mon-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 25px;
}

.mon-item {
    background: #ffffffcc;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: 0.3s;
    text-align: center;
    backdrop-filter: blur(4px);
}

.mon-item:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

.mon-item img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
}

.mon-item h3 {
    margin: 12px 0 8px;
    font-size: 20px;
    color: #333;
}

.mon-item .price {
    color: #e60000;
    font-size: 18px;
    font-weight: bold;
}

.mon-item .km {
    color: #c0392b;
    font-weight: bold;
}

.mon-item p {
    font-size: 14px;
    color: #666;
}
</style>

<section class="monnoibat-section">
    <h2>✨ Món Nổi Bật</h2>

    <div class="mon-list">
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
                <div class="mon-item">

                    <!-- Ảnh -->
                    <?php if (!empty($item['HinhAnh'])): ?>
                        <img src="../assets/images/ htmlspecialchars($item['HinhAnh']) ?>" alt="">
                    <?php else: ?>
                        <img src="../assets/images/no-image.png" alt="">
                    <?php endif; ?>

                    <!-- Tên món -->
                    <h3><?= htmlspecialchars($item['TenMon']) ?></h3>

                    <!-- Giá -->
                    <?php if ($item['Loai'] == 'KhuyenMai'): ?>
                        <p class="km">Giảm <?= $item['Gia'] ?>%</p>
                    <?php else: ?>
                        <p class="price"><?= number_format($item['Gia'], 0, ',', '.') ?> VND</p>
                    <?php endif; ?>

                    <!-- Mô tả -->
                    <p><?= htmlspecialchars($item['ChiTiet']) ?></p>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;">Chưa có món nổi bật nào.</p>
        <?php endif; ?>
    </div>
</section>
