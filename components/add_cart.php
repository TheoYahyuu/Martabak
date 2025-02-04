<?php

if(isset($_POST['add_to_cart'])){

   if($user_id == ''){
      header('location:login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = (int)$pid; // Ubah tipe data menjadi integer

      $name = $_POST['name'];
      $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); // Gunakan htmlspecialchars

      $price = $_POST['price'];
      $price = (float)$price; // Ubah tipe data menjadi float

      $image = $_POST['image']; 
      $image = htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); // Gunakan htmlspecialchars

      $qty = $_POST['qty'];
      $qty = (int)$qty; // Ubah tipe data menjadi integer

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_cart_numbers->rowCount() > 0){
         $message[] = 'sudah masuk troli yuk buruan order!';
      }else{
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
         $message[] = 'ditambahakan ke troli!';
         
      }

   }

}

?>