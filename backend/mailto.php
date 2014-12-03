<?php



$fac = $_GET['fac'];
$nombre = $_GET['name'];
$surname = $_GET['surmane'];
$importance = $_GET['imp'];
$audience = $_GET['audi'];
$msg = $_GET['msg'];


$datos = mysqli_connect('mysql.nixiweb.com', "u315988979_eque", "MoNoCeRoS", "u315988979_eque");

$query = mysqli_query($datos, "SELECT USR_MAIL FROM USER WHERE (USR_NAME = '" . $nombre . "' AND USR_SURNAME = '" . $surname . "' AND USR_FACILITY = " . $fac . ")");
$res_query = mysqli_fetch_assoc($query);
$email  = $res_query['USR_MAIL'];


// To email address
$email = $email;
$email_name = "Contactos";

// From email address
$from = "informativo_non_reply_eque@eque-e.cl";
$from_name = "Eque-e Sistemas";

// The message
$subject = "Has recibido Un nuevo requerimiento! cod:";


/***********************************************/
/* No need to modify anything down here */
/* Note that these are needed to send the mail */
/***********************************************/
// Generate text + html version
$random_hash = md5(date("r", time()));

$mailmessage = "
--PHP-alt-".$random_hash."
Content-Type: text/plain; charset=\"iso-8859-1\"
Content-Transfer-Encoding: 7bit

$message

--PHP-alt-".$random_hash."
Content-Type: text/html; charset=\"iso-8859-1\"
Content-Transfer-Encoding: 7bit

$message_html

--PHP-alt-".$random_hash."--
";

// Headers
// To send HTML mail, the Content-type header must be set
$headers = "From: ".$from_name." <".$from.">" . "\r\n";
$headers .= "Reply-To: ".$from_name." <".$from.">" . "\r\n";
$headers .= "Date: ".date("r") . "\r\n";

// Additional headers
$headers .= "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-Type: text/html; boundary=\"PHP-alt-" . $random_hash . "\"\r\n";
$headers .= "Message-Id: <" . md5(uniqid(microtime())) . "@" . $_SERVER["SERVER_NAME"] . ">\r\n";

// Send the mail
mail($email, $subject, $msg, $headers);

echo "Mail Enviado!";


?>