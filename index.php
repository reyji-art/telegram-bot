<?php
$token = "ISI_TOKEN_BOT_KAMU";

$update = json_decode(file_get_contents("php://input"), true);
$chat_id = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];

$reply = "Ketik: id <nomor>\nContoh: id 123";

if (preg_match('/^id (\d+)/i', $message, $match)) {
    $id = $match[1];

    // Ganti data koneksi sesuai database kamu
    $conn = new mysqli("HOST_DB", "USER_DB", "PASSWORD_DB", "NAMA_DB");

    if ($conn->connect_error) {
        $reply = "Gagal koneksi ke database.";
    } else {
        $result = $conn->query("SELECT * FROM users WHERE id = $id");

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $reply = "ID: " . $data["id"] . "\nNama: " . $data["name"] . "\nEmail: " . $data["email"];
        } else {
            $reply = "Data tidak ditemukan.";
        }
        $conn->close();
    }
}

file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($reply));
?>
