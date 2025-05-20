<?php
$token = "7832708369:AAGMy2LBEuxMnmC2nhBrszL0FhYr2Hp_nxQ";

$update = json_decode(file_get_contents("php://input"), true);
$chat_id = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];

$reply = "Ketik: id <nomor>\nContoh: id 123";

if (preg_match('/^id (\d+)/i', $message, $match)) {
    $id = $match[1];

    // Ganti data koneksi sesuai database kamu
    $conn = new mysqli("sql12.freesqldatabase.com
", "sql12779950", "IuvmKW82Y7", "sql12779950");

    if ($conn->connect_error) {
        $reply = "Gagal koneksi ke database.";
    } else {
        $result = $conn->query("SELECT * FROM pemenang WHERE id_pemenang = $id");

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $reply = "ID: " . $data["id_pemenang"] . "\nNama: " . $data["nama"] . "\nEmail: " . $data["nik"];
        } else {
            $reply = "Data tidak ditemukan.";
        }
        $conn->close();
    }
}

file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($reply));
?>
