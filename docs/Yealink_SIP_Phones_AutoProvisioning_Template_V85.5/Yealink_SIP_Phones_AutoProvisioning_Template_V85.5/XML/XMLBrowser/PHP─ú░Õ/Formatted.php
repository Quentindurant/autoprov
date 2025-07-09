<?php
#
function push2phone($server,$phone,$data)
{
$xml = "xml=".$data;
$post = "POST /servlet?push=xml HTTP/1.1\r\n";
$post .= "Host: $phone\r\n";
$post .= "Referer: $server\r\n";
$post .= "Connection: Keep-Alive\r\n";
$post .= "Content-Type: text/xml\r\n";
$post .= "Content-Length: ".strlen($xml)."\r\n\r\n";
$fp = @fsockopen ( $phone, 80, $errno, $errstr, 5);
if($fp)
{
fputs($fp, $post.$xml);
flush();
fclose($fp);
}
}
##############################

$xml = "<YealinkIPPhoneFormattedTextScreen  doneAction=\"http://10.1.0.105/menu.php\" Beep=\"yes\" Timeout=\"10\" LockIn=\"no\" >\n";

$xml .= "<Line Size=\"large\" Align=\"center\">Header line1</Line>\n";
$xml .= "<Line Align=\"left\" Color=\"black\">Header line2</Line>\n";
$xml .= "<Line Size=\"small\" Align=\"right\" Color=\"white\">Header line3</Line>\n";

$xml .= "<Scroll>\n";
$xml .= "<Line Size=\"large\" Align=\"center\">Scroll line1</Line>\n";
$xml .= "<Line Align=\"left\" Color=\"black\">Scroll line2</Line>\n";
$xml .= "<Line Size=\"small\" Align=\"right\" Color=\"white\">Scroll line3</Line>\n";
$xml .= "</Scroll>\n";

$xml .= "<Line Size=\"large\" Align=\"right\" Color=\"red\">Footer line1</Line>\n";
$xml .= "<Line Size=\"double\" Color=\"green\">Footer line2</Line>\n";
$xml .= "<Line>Footer line3</Line>\n";

$xml .= "</YealinkIPPhoneFormattedTextScreen >\n";

push2phone("10.2.9.5","10.2.9.88",$xml);
push2phone("10.2.9.5","10.2.9.132",$xml);


#replace first ip with your Apache ip address
#replace second ip with your phone ip address
?>



