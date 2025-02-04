<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $verify_token = $conn->prepare("SELECT * FROM `users` WHERE token = ?");
    $verify_token->execute([$token]);

    if ($verify_token->rowCount() > 0) {
        if (isset($_POST['submit'])) {
            $new_pass = sha1($_POST['new_pass']);
            $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
            $confirm_pass = sha1($_POST['confirm_pass']);
            $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

            if ($new_pass != $confirm_pass) {
                $message[] = 'konfirmasi password tidak cocok!';
            } else {
                $update_pass = $conn->prepare("UPDATE `users` SET password = ?, token = '' WHERE token = ?");
                $update_pass->execute([$confirm_pass, $token]);
                $message[] = 'password berhasil diubah!';
                header('location:login.php');
            }
        }
    } else {
        $message[] = 'token tidak valid!';
    }
} else {
    $message[] = 'token dibutuhkan!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update password</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="form-container">

        <form action="" method="post">
            <h3>ubah password</h3>
            <input type="password" name="new_pass" placeholder="masukan password baru" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" required>
            <input type="password" name="confirm_pass" placeholder="konfirmasi password baru" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" required>
            <input type="submit" value="ubah sekarang" class="btn" name="submit">
        </form>

    </section>

    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>