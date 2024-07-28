<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 5.6
 * @ Decoder version: 1.0.4
 * @ Release: 02/06/2020
 *
 * @ ZendGuard Decoder PHP 5.6
 */

// Decoded file for php version 53.
class emailModel
{
    public function get_email_config($id)
    {
        $query = "select * from email_smtp where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0];
    }
    public function send_email($email_info)
    {
        $email_config = self::get_email_config($email_info["email_config_id"]);
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_OFF;
            $mail->Host = $email_config["hostname"];
            $mail->Port = $email_config["port"];
            $mail->SMTPSecure = $email_config["smtp_secure"];
            $mail->SMTPAuth = true;
            $mail->Username = $email_config["username"];
            $mail->Password = $email_config["password"];
            $mail->setFrom($email_config["email_send_from"], $email_config["email_send_from_title"]);
            $mail->CharSet = "UTF-8";
            if ($email_config["email_reply_to"] != NULL && 0 < strlen($email_config["email_reply_to"])) {
                $mail->addReplyTo($email_config["email_reply_to"], $email_config["email_send_from_title"]);
            }
            $mail->addAddress($email_info["send_to"], $email_info["send_to_name"]);
            $mail->Subject = $email_config["subject"];
            if ($email_config["msg_html"] == NULL || !isset($email_config["msg_html"])) {
                $email_config["msg_html"] = "";
            }
            $mail->msgHTML($email_config["msg_html"], __DIR__);
            $mail->AddAttachment($email_info["main_dir"] . "/data/invoice_" . $email_info["invoice_id"] . ".pdf", "invoice_" . $email_info["invoice_id"] . ".pdf");
            if (!$mail->send()) {
                return false;
            }
            return true;
        } catch (phpmailerException $e) {
            $result = $e->errorMessage();
            return NULL;
        } catch (PHPMailer\PHPMailer\Exception $e) {
            $result = $e->getMessage();
        }
    }
}

?>