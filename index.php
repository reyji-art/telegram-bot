<?php
// Konfigurasi database
$host = "sql12.freesqldatabase.com";
$dbname = "sql12779950";
$username = "sql12779950";
$password = "IuvmKW82Y7";

// Koneksi database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

// Ambil input dari Telegram webhook
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
    exit;
}

$chat_id = $update['message']['chat']['id'] ?? null;
$text = trim($update['message']['text'] ?? '');

if ($chat_id && $text) {
    // Cek apakah text adalah angka (ID)
    if (ctype_digit($text)) {
        // Query data berdasarkan ID
        $stmt = $pdo->prepare("SELECT * FROM pemenang WHERE id_pemenang = ?");
        $stmt->execute([$text]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $response_text = "Data untuk ID {$user['id_pemenang']}:\n";
            $response_text .= "Nama: " . $user['nama'] . "\n";
            $response_text .= "Info: " . $user['nik'];
        } else {
            $response_text = "Data dengan ID $text tidak ditemukan.";
        }
    } else {
        $response_text = "Silakan masukkan ID berupa angka untuk mencari data.";
    }

    // Kirim pesan balik ke Telegram
    $bot_token = "7832708369:AAGMy2LBEuxMnmC2nhBrszL0FhYr2Hp_nxQ";

    $url = "https://api.telegram.org/bot$bot_token/sendMessage";

    $post_fields = [
        'chat_id' => $chat_id,
        'text' => $response_text,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

?>
