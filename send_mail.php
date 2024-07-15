<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Form verilerini doğrulama
    $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    $phonePattern = '/^05\d{9}$/';
    $phoneLength = (strlen($phone) === 11);

    $errors = [];

    if (!preg_match($emailPattern, $email)) {
        $errors[] = 'Geçerli bir e-posta adresi giriniz.';
    }

    if (!preg_match($phonePattern, $phone) || !$phoneLength) {
        $errors[] = 'Geçerli bir telefon numarası giriniz. Numara 05 ile başlamalı ve 11 haneli olmalıdır.';
    }

    if (!empty($errors)) {
        http_response_code(400);
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
        exit;
    }

    // E-posta gönderme işlemi
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'palaelif07@gmail.com';
        $mail->Password = 'neriman1966';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('palaelif07@gmail.com', 'İletişim Formu');
        $mail->addAddress('vatansms.elifpala@gmail.com');

        $mail->CharSet = 'UTF-8';
        
        $mail->isHTML(true);
        $mail->Subject = 'Yeni İletişim Formu Mesajı';
        $mail->Body    = "
            <html>
            <head>
            <title>Yeni İletişim Formu Mesajı</title>
            </head>
            <body>
            <p><strong>İsim Soyisim:</strong> $name</p>
            <p><strong>Telefon:</strong> $phone</p>
            <p><strong>E-posta:</strong> $email</p>
            </body>
            </html>
        ";

        $mail->send();
        http_response_code(200);
        echo json_encode(array('message' => 'Mesajınız başarıyla gönderildi.'));
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array('message' => 'E-posta gönderirken bir hata oluştu: ' . $mail->ErrorInfo));
    }
} else {
    http_response_code(403);
    echo json_encode(array('message' => 'Geçersiz istek.'));
}
?>
