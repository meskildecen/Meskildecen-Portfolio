<?php
// Verifica se o formulário foi submetido
if (isset($_POST['submit'])) {

  // Configurações de e-mail
  $to = 'meskildecen@gmail.com';
  $subject = 'Nova mensagem do formulário de contato';
  $headers = "From: ".$_POST['email'];

  // Dados do formulário
  $name = $_POST['full-name'];
  $email = $_POST['email'];
  $phone = $_POST['phone-number'];
  $message = $_POST['message'];

  // Anexo (opcional)
  if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    $file_name = $_FILES['file']['name'];
    $file_temp = $_FILES['file']['tmp_name'];
    $file_type = $_FILES['file']['type'];
    $file_size = $_FILES['file']['size'];

    $attachment = chunk_split(base64_encode(file_get_contents($file_temp)));
    $boundary = md5(date('r', time()));
    $headers .= "\r\nMIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"_1_$boundary\"";
    $message = "--_1_$boundary\r\nContent-Type: text/plain; charset=\"iso-8859-1\"\r\nContent-Transfer-Encoding: 7bit\r\n\r\n$message\r\n\r\n--_1_$boundary\r\nContent-Type: $file_type; name=\"$file_name\"\r\nContent-Disposition: attachment; filename=\"$file_name\"\r\nContent-Transfer-Encoding: base64\r\n\r\n$attachment\r\n\r\n--_1_$boundary--";
  }

  // Envia o e-mail
  if (mail($to, $subject, $message, $headers)) {
    echo '<div class="alert alert-success messenger-box-contact__msg" role="alert">
          Sua mensagem foi enviada com sucesso.
        </div>';
  } else {
    echo '<div class="alert alert-danger messenger-box-contact__msg" role="alert">
          Houve um problema ao enviar sua mensagem. Tente novamente mais tarde.
        </div>';
  }
}
?>