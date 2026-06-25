<?php
// Folder: C:\xampp\htdocs\portal-otp\config.php

define('TELEGRAM_TOKEN', '8791910437:AAF5-pwMuLVeMxxOo4PVeyLiFDG_-HTAaW0'); // <-- MASUKKAN TOKEN BOT TELEGRAM ANDA DI SINI

function sendTelegramMessage($chatId, $message) {
    $url = "https://api.telegram.org/bot" . TELEGRAM_TOKEN . "/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'Markdown'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
}
?>
