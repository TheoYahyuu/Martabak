<?php

include 'components/connect.php';
require 'vendor/autoload.php'; // Include Snap's PHP SDK

// Set up Snap's API configuration
\Midtrans\Config::$serverKey = 'SB-Mid-server-ulk40IGeKLcUHP7xSYfGMaq7';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

session_start();
//ountetikasi pengguna
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  $user_id = '';
  header('location:index.php');
}

// Inisialisasi variabel $product_list
$product_list = "";

// Periksa apakah pembayaran berhasil
$payment_success = isset($_GET['status']) && $_GET['status'] == 'success';

if (isset($_POST['submit'])) {

  $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
  $number = preg_replace('/[^0-9]/', '', $_POST['number']);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');

  $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
  $check_cart->execute([$user_id]);

  if ($check_cart->rowCount() > 0) {
    if ($address == '') {
      $message[] = 'masukan alamat!';
    } else {
      // Ambil item dari keranjang untuk disimpan di tabel orders
      $cart_items = array();
      $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart_items->execute([$user_id]);
      $grand_total = 0;

      while ($fetch_cart_item = $select_cart_items->fetch(PDO::FETCH_ASSOC)) {
        $cart_items[] = array(
          'id' => uniqid(),
          'price' => $fetch_cart_item['price'],
          'quantity' => $fetch_cart_item['quantity'],
          'name' => $fetch_cart_item['name']
        );
        $product_list .= $fetch_cart_item['name'] . ' (' . $fetch_cart_item['price'] . ' x ' . $fetch_cart_item['quantity'] . ') - ';
        $grand_total += ($fetch_cart_item['price'] * $fetch_cart_item['quantity']);
      }

      // Create Snap transaction
      $transaction_details = array(
        'order_id' => uniqid(),
        'gross_amount' => $grand_total,
      );
      $customer_details = array(
        'first_name' => $name,
        'phone' => $number,
        'email' => $email,
        'shipping_address' => $address
      );
      $transaction = array(
        'transaction_details' => $transaction_details,
        'item_details' => $cart_items,
        'customer_details' => $customer_details
      );
      $snapToken = \Midtrans\Snap::getSnapToken($transaction);

      echo "<script src='https://app.sandbox.midtrans.com/snap/snap.js' data-client-key='SB-Mid-client-fJRvlcl_ER0Ko8ar'></script>";
      echo "<script type='text/javascript'>
                  snap.pay('$snapToken', {
                     onSuccess: function(result){
                        window.location.href = 'checkout.php?status=success';
                     },
                     onPending: function(result){
                        window.location.href = 'checkout.php?status=pending';
                     },
                     onError: function(result){
                        window.location.href = 'checkout.php?status=error';
                     }
                  });
               </script>";
      exit(); // Stop further execution
    }
  } else {
    $message[] = 'kranjang masih kosong';
  }
}

// Periksa apakah tombol "Simpan Pesanan" telah ditekan
if (isset($_POST['save_order'])) {
  // Ambil data pesanan dari tabel cart
  $cart_items = array();
  $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
  $select_cart_items->execute([$user_id]);
  $grand_total = 0;

  while ($fetch_cart_item = $select_cart_items->fetch(PDO::FETCH_ASSOC)) {
    $cart_items[] = array(
      'name' => $fetch_cart_item['name'],
      'price' => $fetch_cart_item['price'],
      'quantity' => $fetch_cart_item['quantity']
    );
    $grand_total += ($fetch_cart_item['price'] * $fetch_cart_item['quantity']);
  }

  // Simpan pesanan ke tabel orders
  $order_id = uniqid();
  // Ambil data dari input hidden
  $name = $_POST['name'];
  $email = $_POST['email'];
  $number = $_POST['number'];
  $address = $_POST['address'];
  // $product_list sudah didefinisikan di awal dan diisi di blok kode sebelumnya

  // Sesuaikan nama kolom dengan tabel orders
  $insert_order = $conn->prepare("INSERT INTO `orders` (user_id, order_id, total_price, name, email, number, address, total_products) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $insert_order->execute([$user_id, $order_id, $grand_total, $name, $email, $number, $address, $product_list]);

  // Hapus item dari keranjang setelah pesanan disimpan
  $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
  $delete_cart->execute([$user_id]);
  $message[] = 'Pesanan berhasil disimpan!';

  // Arahkan pengguna ke halaman orders.php
  header('location:orders.php');
  exit(); // Pastikan untuk menghentikan eksekusi skrip setelah pengalihan
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>checkout</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">

</head>

<body>

  <?php include 'components/user_header.php'; ?>

  <div class="heading">
    <h3>checkout</h3>
    <p><a href="index.php">home</a> <span> / checkout</span></p>
  </div>

  <section class="checkout">

    <h1 class="title">Detail pembayaran</h1>

    <form action="" method="post">

      <div class="cart-items">
        <h3>Item keseluruhan</h3>

        <?php
        // Jika pembayaran berhasil, tampilkan pesan sukses dan tombol simpan
        if ($payment_success) {
          if (!empty($message)) {
            echo '<p class="success" style="color: green;">' . $message[0] . '</p>';
          } else {
            echo '<p class="success" style="color: green;">Pembayaran berhasil!</p>';
          }

          // --- Kode untuk mengisi $product_list ---
          $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
          $select_cart_items->execute([$user_id]);
          while ($fetch_cart_item = $select_cart_items->fetch(PDO::FETCH_ASSOC)) {
            $product_list .= $fetch_cart_item['name'] . ' (' . $fetch_cart_item['price'] . ' x ' . $fetch_cart_item['quantity'] . ') - ';
          }
          // --- Akhir kode untuk mengisi $product_list ---

          // Tombol untuk menyimpan item pembayaran
          echo '<button type="submit" name="save_order" class="btn">Simpan Pesanan</button>';
        } else { // Jika tidak, tampilkan item belanjaan
          $grand_total = 0;
          $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
          $select_cart->execute([$user_id]);
          if ($select_cart->rowCount() > 0) {
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
              echo '<p><span class="name">' . $fetch_cart['name'] . '</span><span class="price">Rp' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . '</span></p>';
              $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
            }
          } else {
            echo '<p class="empty">lihat keranjang!</p>';
          }
        ?>
          <p class="grand-total"><span class="name">jumlah total :</span><span class="price">Rp<?= $grand_total; ?></span></p>
          <a href="cart.php" class="btn">lihat keranjang</a>
        <?php } ?>

      </div>

      <input type="hidden" name="total_products" value="<?= $product_list; ?>">
      <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
      <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
      <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
      <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
      <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>">

      <div class="user-info">
        <h3>info saya</h3>
        <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
        <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?></span></p>
        <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
        <a href="update_profile.php" class=" btn">update info</a>
        <h3>Alamat pengiriman</h3>
        <p><i class="fas fa-map-marker-alt"></i><span><?php if ($fetch_profile['address'] == '') {
                                                          echo 'please enter your address';
                                                        } else {
                                                          echo $fetch_profile['address'];
                                                        } ?></span></p>

        <a href="update_address.php" class="btn">ubah alamat</a>

        <?php if (!$payment_success) { ?>
          <input type="submit" value="buat pesanan" class="btn <?php if ($fetch_profile['address'] == '') {
                                                                  echo 'disabled';
                                                                } ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit">
        <?php } ?>
      </div>

    </form>

  </section>

  <?php include 'components/footer.php'; ?>
  <script src="js/script.js"></script>

</body>

</html>