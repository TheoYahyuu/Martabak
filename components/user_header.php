<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inisialisasi variabel message
$message = [];

// Menampilkan pesan jika ada
if (isset($message) && !empty($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . htmlspecialchars($msg) . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}

?>

<header class="header">
    <section class="flex">
        <a href="index.php" class="logo">
            <img src="images/martabukza1.png" alt="Logo Martabak">
        </a>

        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="menu.php">Menu</a>
            <a href="orders.php">Order</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
        </nav>

        <div class="icons">
            <?php
            // Pastikan $user_id didefinisikan
            if (isset($user_id)) {
                $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $count_cart_items->execute([$user_id]);
                $total_cart_items = $count_cart_items->rowCount();
            } else {
                $total_cart_items = 0; // Jika $user_id tidak ada, set jumlah keranjang ke 0
            }
            ?>
            <a href="search.php"><i class="fas fa-search"></i></a>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="menu-btn" class="fas fa-bars"></div>
        </div>

        <div class="profile">
            <?php
            // Pastikan $user_id didefinisikan sebelum digunakan
            if (isset($user_id)) {
                $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                $select_profile->execute([$user_id]);
                if ($select_profile->rowCount() > 0) {
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <p class="name"><?= htmlspecialchars($fetch_profile['name']); ?></p>
                    <div class="flex">
                        <a href="profile.php" class="btn">profile</a>
                        <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
                    </div>
                    <?php
                } else {
                    ?>
                    <p class="name">User tidak ditemukan!</p>
                    <a href="login.php" class="btn">login</a>
                    <?php
                }
            } else {
                ?>
                <p class="name">Please login!</p>
                <a href="login.php" class="btn">login</a>
                <?php
            }
            ?>
        </div>
    </section>
</header>