<?php
// Folder: C:\xampp\htdocs\portal-otp\index.php
session_start();
require_once 'config.php';
require_once 'users.php'; 

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = trim($_POST['phone']);

    if (array_key_exists($phone, $user_database)) {
        // Generate 6 digit angka OTP
        $otp_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Mengambil data spesifik user dari users.php
        $chat_id    = $user_database[$phone]['chat_id'];
        $target_olt = $user_database[$phone]['target']; 

        // Set session data
        $_SESSION['otp_auth']   = $otp_code;
        $_SESSION['user_phone'] = $phone;
        $_SESSION['otp_expiry'] = time() + 300; // Berlaku 5 Menit
        $_SESSION['target_url'] = $target_olt; 

        // Susun pesan Telegram
        $pesan = "🔒 *KODE VERIFIKASI PORTAL OTP*\n\n";
        $pesan .= "Kode OTP Anda adalah: `{$otp_code}`\n";
        $pesan .= "Kode ini berlaku selama 5 menit untuk membuka akses gembok OLT Anda.\n\n";
        $pesan .= "⚠️ *Jangan memberikan kode ini kepada siapapun!*";

        // Kirim via Telegram
        $send = sendTelegramMessage($chat_id, $pesan);
        
        // 🔥 PERBAIKAN: Memastikan Telegram benar-benar sukses mengirim pesan ("ok":true)
        if ($send && strpos($send, '"ok":true') !== false) {
            header("Location: verify.php");
            exit();
        } else {
            // Jika token salah atau chat_id salah, error asli dari Telegram akan muncul di sini
            $error = "Telegram API Error! Pastikan TOKEN di config.php sudah diganti dengan token asli dari @BotFather.";
        }
    } else {
        $error = "Nomor Handphone tidak terdaftar dalam sistem internal.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Akses OLT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        <h3 class="text-center fw-bold text-primary mb-2">GATEWAY OTP</h3>
                        <p class="text-muted text-center small mb-4">Masukkan nomor HP terdaftar untuk memvalidasi akses</p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger small text-center"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-secondary">NOMOR HANDPHONE</label>
                                <input type="text" class="form-control form-control-lg text-center fs-5" name="phone" placeholder="08xxxxxxxxxx" required autocomplete="off">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 btn-lg fs-6">Kirim Kode OTP via Telegram</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
