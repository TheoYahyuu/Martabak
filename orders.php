<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <div class="heading">
        <h3>Riwayat Pesanan</h3>
        <p><a href="index.php">Home</a> <span> / Riwayat Pesanan</span></p>
    </div>

    <section class="orders">

        <h1 class="title">Pesanan Saya</h1>

        <div class="box-container">

            <?php
            // Ambil data pesanan dari tabel orders berdasarkan user_id
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY id DESC");
            $select_orders->execute([$user_id]);

            if ($select_orders->rowCount() > 0) {
                while ($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <div class="box">
                        <p> <span>Tanggal Pesanan:</span> <?= $fetch_order['placed_on']; ?> </p>
                        <p> <span>Nama:</span> <?= $fetch_order['name']; ?> </p>
                        <p> <span>Email:</span> <?= $fetch_order['email']; ?> </p>
                        <p> <span>Nomor Telepon:</span> <?= $fetch_order['number']; ?> </p>
                        <p> <span>Alamat:</span> <?= $fetch_order['address']; ?> </p>
                        <p> <span>Total Harga:</span> Rp<?= $fetch_order['total_price']; ?> </p>
                        <p> <span>Status Pembayaran:</span> <span style="color:<?php echo ($fetch_order['payment_status'] == 'gagal') ? 'red' : 'green'; ?>"><?= $fetch_order['payment_status']; ?></span> </p>
                        <p>Status Pengiriman:
                            <?php if ($fetch_order['payment_status'] == 'success') : ?>
                                <span style="color:<?php if ($fetch_order['delivery_status'] == 'dikemas' || $fetch_order['delivery_status'] == 'dikirim' || $fetch_order['delivery_status'] == 'selesai') {
                                                        echo 'green';
                                                    } else {
                                                        echo 'grey';
                                                    }; ?>">Dikemas</span> |
                                <span style="color:<?php if ($fetch_order['delivery_status'] == 'dikirim' || $fetch_order['delivery_status'] == 'selesai') {
                                                        echo 'green';
                                                    } else {
                                                        echo 'grey';
                                                    }; ?>">Dikirim</span> |
                                <span style="color:<?php if ($fetch_order['delivery_status'] == 'selesai') {
                                                        echo 'green';
                                                    } else {
                                                        echo 'grey';
                                                    }; ?>">Selesai</span>
                            <?php endif; ?>
                        </p>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">Belum ada pesanan yang dilakukan!</p>';
            }
            ?>

        </div>

    </section>

    <?php include 'components/footer.php'; ?>
    <script src="js/script.js"></script>

</body>

</html>