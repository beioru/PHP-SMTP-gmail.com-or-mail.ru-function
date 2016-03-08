<?
// part of material-design.ru please set a simple link on my site =) <a href="http://material-design.ru">material-design.ru</a>
// =morfiysss=


function mail_now($smtp_mail_config, $mailto, $theme, $mail_text, $header=''){
if(!($ret=smtp_mail($smtp_mail_config, $mailto, $theme, $mail_text, $header))) $ret=mail($mailto, $theme, $mail_text, $header);
return  $ret;
}
function smtp_mail($smtp_mail_config, $mail_to, $subject, $message, $headers='') {
    $smtp_mail_config['smtp_debug']   = false; //debug mode
    $SEND = "Date: ".date("D, d M Y H:i:s") . " UT\r\n";
    $SEND .=    'Subject: =?'.$smtp_mail_config['smtp_charset'].'?B?'.base64_encode($subject)."=?=\r\n";
    if ($headers) $SEND .= $headers."\r\n\r\n";
    else
    {
            $SEND .= $smtp_mail_config['smtp_username']?"Reply-To: ".$smtp_mail_config['smtp_username']."\r\n":'';
            $SEND .= "MIME-Version: 1.0\r\n";
            $SEND .= $smtp_mail_config['smtp_charset']?"Content-type: text/html; charset=\"".$smtp_mail_config['smtp_charset']."\"\r\n":'';
            $SEND .= "Content-Transfer-Encoding: 8bit\r\n";
            $SEND .= $smtp_mail_config['smtp_from']?"From: \"".$smtp_mail_config['smtp_from']."\" <".$smtp_mail_config['smtp_username'].">\r\n":'';
            $SEND .= $mail_to?"To: $mail_to <$mail_to>\r\n":'';
            $SEND .= "X-Priority: 3\r\n\r\n";
    }
    $SEND .=  $message."\r\n";
    switch ($smtp_mail_config['service_name']) {
        case 'mail.ru':
            $service['smtp_host']='tls://smtp.mail.ru';
            $service['smtp_port']='465';
            $service['hello']='EHLO mail';

        break;
        case 'gmail.com':
            $service['smtp_host']='ssl://smtp.gmail.com';
            $service['smtp_port']='465';
            $service['hello']='HELO google';

        break;
        
        default:
            # code...
            break;
    }
     if( !$socket = fsockopen($service['smtp_host'], $service['smtp_port'], $errno, $errstr,30) ) {
        if ($smtp_mail_config['smtp_debug']) echo $errno."<br>".$errstr;
        return false;
     }
 
    if (!server_parse($smtp_mail_config, $socket, "220", __LINE__)) return false;
 
    fputs($socket, $service['hello']."\r\n");
    if (!server_parse($smtp_mail_config, $socket, "250", __LINE__)) {
        if ($smtp_mail_config['smtp_debug']) echo '<p>Cannot send HELO!</p>';
        fclose($socket);
        return false;
    }
    fputs($socket, "AUTH LOGIN\r\n");
    if (!server_parse($smtp_mail_config, $socket, "334", __LINE__)) {
        if ($smtp_mail_config['smtp_debug']) echo '<p>Can not auth.</p>';
        fclose($socket);
        return false;
    }
    fputs($socket, base64_encode($smtp_mail_config['smtp_username']) . "\r\n");
    if (!server_parse($smtp_mail_config, $socket, "334", __LINE__)) {
        if ($smtp_mail_config['smtp_debug']) echo '<p>Wrong login</p>';
        fclose($socket);
        return false;
    }
    fputs($socket, base64_encode($smtp_mail_config['smtp_password']) . "\r\n");
    if (!server_parse($smtp_mail_config, $socket, "235", __LINE__)) {
        if ($smtp_mail_config['smtp_debug']) echo '<p>Wrong pass</p>';
        fclose($socket);
        return false;
    }
    fputs($socket, "MAIL FROM: <".$smtp_mail_config['smtp_username'].">\r\n");
    if (!server_parse($smtp_mail_config, $socket, "250", __LINE__)) {
        if ($smtp_mail_config['smtp_debug']) echo '<p>Can not send MAIL FROM: </p>';
        fclose($socket);
        return false;
    }
    fputs($socket, "RCPT TO: <" . $mail_to . ">\r\n");
 
    if (!server_parse($smtp_mail_config, $socket, "250", __LINE__)) {
        if ($smtp_mail_config['smtp_debug']) echo '<p>Can not send  RCPT TO: </p>';
        fclose($socket);
        return false;
    }
    fputs($socket, "DATA\r\n");
 
    if (!server_parse($smtp_mail_config, $socket, "354", __LINE__)) {
        if ($smtp_mail_config['smtp_debug']) echo '<p>Can not send  DATA</p>';
        fclose($socket);
        return false;
    }
    fputs($socket, $SEND."\r\n.\r\n");
 
    if (!server_parse($smtp_mail_config, $socket, "250", __LINE__)) {
        if ($smtp_mail_config['smtp_debug']) echo '<p>Can not send text</p>';
        fclose($socket);
        return false;
    }
    fputs($socket, "QUIT\r\n");
    fclose($socket);
    return TRUE;
}
 
function server_parse($smtp_mail_config,$socket, $response, $line = __LINE__) {
    while (@substr($server_response, 3, 1) != ' ') {
        if (!($server_response = fgets($socket, 256))) {
            if ($smtp_mail_config['smtp_debug']) echo "<p>Problem.</p>$response<br>$line<br>";
            return false;
        }
    }
    if (!(substr($server_response, 0, 3) == $response)) {
        if ($smtp_mail_config['smtp_debug']) echo "<p>Problem.!</p>$response<br>$line<br>";
        return false;
    }
    return true;
}
?>
