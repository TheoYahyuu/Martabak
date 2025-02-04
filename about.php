<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>Tentang Kami</h3>
   <p><a href="index.php">Home</a> <span> / about</span></p>
</div>

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/rangmar.jpg" alt="">
      </div>

      <div class="content">
         <h3>Mengapa Memilih Kami ?</h3>
         <p>Selamat datang di Master Martabak, di mana kami menghadirkan cita rasa yang lezat di depan pintu rumah anda. Temukan pilihan keju pilihan, produk organik, makanan ringan lezat, dan masih banyak lagi-semuanya bersumber dari kualitas dan rasa.

Kami percaya bahwa makanan yang lezat dapat diakses dengan mudah. Bermitra dengan pengrajin lokal dan pemasok tepercaya, kami memastikan kesegaran dan keberlanjutan dalam setiap produk. Platform kami yang mudah digunakan memudahkan anda untuk mencari, memesan, dan menikmati makanan favorit anda dengan mudah.

Bergabunglah bersama kami di Martabukz dan jelajahi dunia kuliner yang nikmat. Kami hadir untuk membuat pengalaman berbelanja makanan anda menyenangkan dan nyaman.</p>
         <a href="menu.php" class="btn">Menu Kami</a>
      </div>

   </div>

</section>

<!-- about section ends -->

<!-- steps section starts  -->

<section class="steps">

   <h1 class="title">Langkah Sederhana</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/step-1.png" alt="">
         <h3>Pilih pesanan</h3>
         <p>Pilih apa pun yang Anda suka, apa pun yang Anda inginkan</p>
      </div>

      <div class="box">
         <img src="images/step-2.png" alt="">
         <h3>Pengiriman cepat</h3>
         <p>Kami berusaha menjadi lebih cepat dari flash</p>
      </div>

      <div class="box">
         <img src="images/step-3.png" alt="">
         <h3>Nikmati Makanan Anda</h3>
         <p> ♥</p>
      </div>

   </div>

</section>

<!-- steps section ends -->

<!-- reviews section starts  -->

<section class="reviews">

   <h1 class="title">Tanggapan Pelanggan</h1>

   <div class="swiper reviews-slider">

      <div class="swiper-wrapper">

         <div class="swiper-slide slide">
            <img src="images/hakim.png" alt="">
            <p>Aplikasinya mudah digunakan banyak pilihan toko martabaknya</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Rido Rifki Hakim</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/naufal.jpeg" alt="">
            <p>Tampilan aplikasinya simple dan mudah dipahami</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Aulia Naufal Nurhaqiqi</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/png 1.jpg" alt="">
            <p>Makasih pembuat aplikasi, aku dapet banyak diskon dan gak usah keluar rumah</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Lulu Choirunnisa</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/farhan.jpg" alt="">
            <p>Bagus aplikasinya ada fitur rekomendasi tempat terdekat jadi gak perlu antri</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Muhammad Farhan Ruswandi</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/Syifaa.jpg" alt="">
            <p>Selain dapet diskon dan voucher kita bisa menemukan martabak sesuai selera kita</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Syifa Aulia Morica</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/Prabowo.jpeg" alt="">
            <p>Aplikasi yang mudah digunakan dan banyak mitranya. Selamat kepada kalian telah menjadi contoh anak bangsa yang membanggakan!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Prabowo Subianto</h3>
         </div>

      </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

<!-- reviews section ends -->



















<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->=






<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".reviews-slider", {
   loop:true,
   grabCursor: true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
      slidesPerView: 1,
      },
      700: {
      slidesPerView: 2,
      },
      1024: {
      slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>