<?php
session_start();
include 'components/connect.php'; // Koneksi ke database
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'send_otp') {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $otp = rand(100000, 999999);

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mamangbeno77@gmail.com';
            $mail->Password = 'fszjuqgmemiuxggc';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('mamangbeno77@gmail.com', 'Martabak Lezat');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Kode OTP Lupa Password';
            $mail->Body = 'Kode OTP Anda adalah: ' . $otp;

            $mail->send();

            // Simpan OTP dan email di database
            $otp_expiry = time() + 120; // 2 menit
            $insert_otp = $conn->prepare("INSERT INTO `otp_lupa_password` (email, otp, otp_expiry) VALUES (?, ?, ?)");
            $insert_otp->execute([$email, $otp, $otp_expiry]);

            $_SESSION['forget_email'] = $email;
            echo json_encode(['status' => 'success', 'message' => 'Kode OTP telah dikirim ke email Anda.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim email: ' . $e->getMessage()]);
        }
    }

    if ($_POST['action'] == 'verify_otp') {
        $email = $_SESSION['forget_email'];
        $otp = $_POST['otp'];

        // Periksa OTP di database
        $select_otp = $conn->prepare("SELECT * FROM `otp_lupa_password` WHERE email = ? AND otp = ?");
        $select_otp->execute([$email, $otp]);

        if ($select_otp->rowCount() > 0) {
            $fetch_otp = $select_otp->fetch(PDO::FETCH_ASSOC);

            if (time() > $fetch_otp['otp_expiry']) {
                echo json_encode(['status' => 'error', 'message' => 'Kode OTP kadaluarsa!']);
            } else {
                // Hapus OTP dari database
                $delete_otp = $conn->prepare("DELETE FROM `otp_lupa_password` WHERE email = ?");
                $delete_otp->execute([$email]);

                $_SESSION['otp_verified'] = true;
                echo json_encode(['status' => 'success', 'message' => 'Kode OTP berhasil diverifikasi.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Kode OTP salah!']);
        }
    }

    if ($_POST['action'] == 'update_password') {
        if (!isset($_SESSION['otp_verified']) || !$_SESSION['otp_verified']) {
            echo json_encode(['status' => 'error', 'message' => 'OTP belum diverifikasi!']);
            exit;
        }
    
        $email = $_SESSION['forget_email'];
        $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
        $cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);
    
        // Periksa apakah email ada di database
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select_user->execute([$email]);
    
        if ($select_user->rowCount() > 0) {
            if ($pass != $cpass) {
                echo json_encode(['status' => 'error', 'message' => 'Konfirmasi password tidak cocok!']);
            } else {
                // Hash password menggunakan SHA-1
                $hashed_password = sha1($pass);
    
                // Update password di database
                $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE email = ?");
                $update_pass->execute([$hashed_password, $email]);
    
                echo json_encode(['status' => 'success', 'message' => 'Password berhasil diubah!']);
                session_destroy(); // Hapus session setelah selesai
                header("Location: login.php");
            exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Email tidak terdaftar!']);
        }
    }    

    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
         .otp-container {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .otp-input {
            flex: 1;
        }
        
        .verify-btn {
            padding: 10px 20px;
            background-color: #fed330;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .verify-btn:hover {
            background-color: #f39c12; /* Change hover color */
        }
        
        .verified {
            color: #fed330;
            margin-left: 10px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include 'components/user_header.php'; ?>
<section class="form-container">
     <!-- Form Kirim Email -->
     <form action="" method="post">
     <h2>Forget Password</h2>
     <div id="step1" class="otp-container">
        <input type="email" id="email" required placeholder="Enter your email" class="box">
        <button id="sendOtpButton"class="verify-btn">Send OTP</button>
    </div>

    <!-- Form Verifikasi OTP -->
    <div id="step2" style="display:none;"class="otp-container">
        <input type="text" id="otp" required="verifikasi otp" class="box otp-input">
        <button id="verifyOtpButton" class="verify-btn">Verifikasi OTP</button>
    </div>

    <!-- Form Reset Password -->
    <div id="step3" style="display:none;">
        <input type="password" id="pass" required placeholder="New Password" class="box otp-input">
        <input type="password" id="cpass" required placeholder="Confirm Password" class="box otp-input">
        <button id="updatePasswordButton" class="verify-btn">Update Password</button>
    </div>
    </form>
</section>
   
    <script>
    $(document).ready(function() {
        $('#sendOtpButton').on('click', function() {
            $.post('forget_password.php', { action: 'send_otp', email: $('#email').val() }, function(response) {
                const res = JSON.parse(response);
                alert(res.message);
                if (res.status === "success") $('#step2').show();
            });
        });

        $('#verifyOtpButton').on('click', function() {
            $.post('forget_password.php', { action: 'verify_otp', otp: $('#otp').val() }, function(response) {
                const res = JSON.parse(response);
                alert(res.message);
                if (res.status === "success") $('#step3').show();
            });
        });

        $('#updatePasswordButton').on('click', function() {
            $.post('forget_password.php', { action: 'update_password', pass: $('#pass').val(), cpass: $('#cpass').val() }, function(response) {
                const res = JSON.parse(response);
                alert(res.message);
                if (res.status === "success") location.reload();
            });
        });
    });
    </script>
</body>
</html>
