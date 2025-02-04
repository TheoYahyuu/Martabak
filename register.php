<?php
include 'components/connect.php';
session_start();

// Cek apakah user sudah login
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

// Import PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Proses pengiriman OTP
if (isset($_POST['send_otp'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $otp = rand(1000, 9999); // Generate OTP

    // Kirim email OTP
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Ganti dengan host SMTP Anda
        $mail->SMTPAuth = true;
        $mail->Username = 'mamangbeno77@gmail.com'; // Ganti dengan username SMTP Anda
        $mail->Password = 'fszjuqgmemiuxggc'; // Ganti dengan password SMTP Anda
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('mamangbeno77@gmail.com', 'Your Name'); // Ganti dengan email dan nama Anda
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Kode OTP Verifikasi';
        $mail->Body = 'Kode OTP Anda adalah: ' . $otp;

        $mail->send();

        // Simpan OTP di session dan set expiry time
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;
        $_SESSION['otp_expiry'] = time() + 300; // OTP berlaku selama 5 menit

        echo json_encode(['status' => 'success', 'message' => 'Kode OTP telah dikirim ke email Anda!']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat mengirim email: ' . $e->getMessage()]);
    }
    exit; // Menghentikan eksekusi setelah mengirim respons
}

// Proses verifikasi OTP
if (isset($_POST['verify_otp'])) {
    if (!isset($_SESSION['otp'])) {
        echo json_encode(['status' => 'error', 'message' => 'Kode OTP tidak ditemukan!']);
        exit;
    }

    if (time() > $_SESSION['otp_expiry']) {
        echo json_encode(['status' => 'error', 'message' => 'Kode OTP telah kedaluwarsa!']);
        unset($_SESSION['otp']); // Hapus OTP setelah kedaluwarsa
        exit;
    }

    $input_otp = $_POST['otp'];

    if ($input_otp == $_SESSION['otp']) {
        $_SESSION['verified'] = true;
        echo json_encode(['status' => 'success', 'message' => 'Email berhasil diverifikasi!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Kode OTP tidak valid!']);
    }
    exit; // Menghentikan eksekusi setelah mengirim respons
}

// Proses pendaftaran
// Proses pendaftaran
if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = $_SESSION['email']; // Ambil email dari session
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $pass = sha1(filter_var($_POST['pass'], FILTER_SANITIZE_STRING));
    $cpass = sha1(filter_var($_POST['cpass'], FILTER_SANITIZE_STRING));

    // Verifikasi OTP
    if (!isset($_SESSION['verified']) || $_SESSION['verified'] !== true) {
        $message[] = 'Silakan verifikasi email Anda terlebih dahulu!';
    } elseif ($pass != $cpass) {
        // Notify if passwords do not match
        $message[] = 'Konfirmasi password tidak cocok!';
    } else {
        // Cek apakah email atau nomor sudah terdaftar
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select_user->execute([$email]);

        if ($select_user->rowCount() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email sudah terdaftar!']);
            exit;
        } else {
            // Simpan data pengguna ke database
            $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?, ?, ?, ?)");
            $insert_user->execute([$name, $email, $number, $pass]);

            // Clear OTP and verification status
            unset($_SESSION['otp']); 
            unset($_SESSION['verified']); 

            echo json_encode(['status' => 'success', 'message' => 'Pendaftaran berhasil!']);
            header('Location: login.php');
            exit();
        }
    }
}

?>

<?php if (isset($message)): ?>
    <div class="error-message">
        <?php foreach ($message as $msg): ?>
            <p><?php echo htmlspecialchars($msg); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    
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
</head>

<body>

<?php include 'components/user_header.php'; ?>

<section class="form-container">
    <form id="registrationForm" method="post">
        <h3>Register Now</h3>

        <?php if (isset($message)): ?>
            <div class="error-message">
                <?php foreach ($message as $msg): ?>
                    <p><?php echo htmlspecialchars($msg); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Input nama dan email -->
        <input type="text" name="name" required placeholder="Masukkan nama" class="box" maxlength="50">
        
        <!-- Email dengan tombol verifikasi -->
        <div class="otp-container">
            <input type="email" name="email" required placeholder="Masukkan email" class="box otp-input" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <button type="button" id="sendOtpBtn" class="verify-btn">Verifikasi email</button>
        </div>

        <!-- Tampilkan input OTP setelah email dikirim -->
        <div class="otp-container" id="otpContainer" style="display: none;">
            <input type="text" name="otp" required placeholder="Masukkan kode OTP" class="box otp-input" maxlength="4">
            <button type="button" id="verifyOtpBtn" class="verify-btn">Verifikasi OTP</button>
        </div>

        <!-- Tampilkan form lainnya setelah OTP terverifikasi -->
        <div id="registrationFields" style="display: none;">
            <input type="number" name="number" required placeholder="Masukkan no. telepon" class="box" min="0" max="9999999999" maxlength="13">
            <input type="password" name="pass" required placeholder="Masukkan password" class="box" maxlength="50">
            <input type="password" name="cpass" required placeholder="Konfirmasi password" class="box" maxlength="50">
            <input type="submit" value="Daftar Sekarang" name="submit" class="btn">
        </div>

        <p>Sudah punya akun? <a href="login.php">Login sekarang</a></p>
    </form>
</section>


<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#sendOtpBtn').click(function() {
        var email = $('input[name="email"]').val();
        
        if (!validateEmail(email)) {
            alert('Format email tidak valid!');
            return;
        }

        $.ajax({
            type: 'POST',
            url: '', // URL ke file PHP yang sama
            data: { send_otp: true, email: email },
            success: function(response) {
                var res = JSON.parse(response);
                alert(res.message);
                if (res.status === 'success') {
                    $('#otpContainer').show();
                }
            }
        });
    });

    $('#verifyOtpBtn').click(function() {
        var otp = $('input[name="otp"]').val();
        
        $.ajax({
            type: 'POST',
            url: '', // URL ke file PHP yang sama
            data: { verify_otp: true, otp: otp },
            success: function(response) {
                var res = JSON.parse(response);
                alert(res.message);
                if (res.status === 'success') {
                    $('#registrationFields').show();
                }
            }
        });
    });

    function validateEmail(email) {
      var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 
      return re.test(String(email).toLowerCase());
    }
});
</script>

</body>
</html>
