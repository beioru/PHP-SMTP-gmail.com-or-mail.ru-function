<?php
// Part of material-design.ru please set a simple link on my site =) <a href="http://material-design.ru">material-design.ru</a>
// =morfiysss=
// First usage
include 'amail.php';

function amail($mailto, $theme, $mail_text, $header=''){
  $smtp_mail_config['service_name']  = 'gmail.com'; //mail.ru or gmail.com
  $smtp_mail_config['smtp_username'] = "";          //username
  $smtp_mail_config['smtp_from']     = '';          //Site domen or name
  $smtp_mail_config['smtp_password'] = '';          //password
  $smtp_mail_config['smtp_charset']  = 'utf-8';     //encoding
  return mail_now($smtp_mail_config, $smtp_mail_config, $mailto, $theme, $mail_text, $header);
}
//then use like mail('admin@site.com', 'Hello', 'I send email', $header);
amail('admin@site.com', 'Hello', 'I send email');

?>
