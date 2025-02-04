<?php
session_start();
include 'components/connect.php'; // Include your database connection

$error_message = ""; // Variabel untuk menyimpan pesan kesalahan

if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass = $_POST['pass']; // Ubah 'password' menjadi 'pass'

    // Hash password menggunakan SHA-1
    $hashed_password = sha1($pass);

    // Query untuk mencocokkan email dan hashed password
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
    $select_user->execute([$email, $hashed_password]);

    if ($select_user->rowCount() > 0) {
        $row = $select_user->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $row['id'];
        header('location:index.php'); // Redirect ke halaman utama
        exit;
    } else {
        $error_message = "Username atau password salah!"; // Set pesan kesalahan
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- Header section starts -->
<?php include 'components/user_header.php'; ?>
<!-- Header section ends -->

<section class="form-container">

   <form action="" method="post">
      <h3>Login Now</h3>
      <input type="email" name="email" required placeholder="Enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Login Now" name="submit" class="btn">
      <button class="btn">
         <a href="forget_password.php" style="color: black;">Forget Password</a>
      </button>
      <p>Don't have an account? <a href="register.php">Register now</a></p>

      <?php if (!empty($error_message)): ?>
         <div class="error-message"><?php echo $error_message; ?></div> <!-- Menampilkan pesan kesalahan -->
      <?php endif; ?>
   </form>

</section>

<?php include 'components/footer.php'; ?>

<!-- Custom JS file link -->
<script src="js/script.js"></script>

<style>
.error-message {
    color: red;
    margin-top: 10px;
}
</style>

</body>
</html>
