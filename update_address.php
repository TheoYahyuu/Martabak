<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
};

if(isset($_POST['submit'])){

   $address = $_POST['jalan'] .', '.$_POST['blok'].', '.$_POST['kecamatan'].', '.$_POST['kota'] .', '. $_POST['prov'] .', '. $_POST['kode_pos'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

   $update_address = $conn->prepare("UPDATE `users` set address = ? WHERE id = ?");
   $update_address->execute([$address, $user_id]);

   $message[] = 'alamat disimpan!';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update address</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php' ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Alamat Saya</h3>
      <input type="text" class="box" placeholder="Nama Jalan,No.rumah" required maxlength="50" name="jalan">
      <input type="text" class="box" placeholder="Detail Lainya(Cth:Blok/Unit No.,Patokan" required maxlength="50" name="blok">
      <input type="text" class="box" placeholder="Kecamatan" required maxlength="50" name="kec">
      <input type="text" class="box" placeholder="Kota/kab" required maxlength="50" name="kota">
      <input type="text" class="box" placeholder="Provinsi" required maxlength="50" name="prov">
      <input type="number" class="box" placeholder="kode pos" required max="999999" min="0" maxlength="6" name="kode_pos">
      <input type="submit" value="simpan alamat" name="submit" class="btn">
   </form>

</section>










<?php include 'components/footer.php' ?>







<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>