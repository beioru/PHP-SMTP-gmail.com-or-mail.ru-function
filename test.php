<?php
include 'mail.php';

function mail_now($mailo, $theme, $mail_text, $header=''){
$smtp_mail_config['service_name']  = 'google'; //mail or google
$smtp_mail_config['smtp_username'] = "";       //username
$smtp_mail_config['smtp_from']     = '';       //Site domen or name
$smtp_mail_config['smtp_password'] = '';       //password
$smtp_mail_config['smtp_charset']  = 'utf-8';  //encoding
return cmail_now($smtp_mail_config, $smtp_mail_config, $mailo, $theme, $mail_text, $header);
}

mail_now('admin@site.com','Hello','I send email');

?>
