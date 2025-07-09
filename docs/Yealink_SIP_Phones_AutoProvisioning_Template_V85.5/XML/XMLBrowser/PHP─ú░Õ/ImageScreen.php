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


$xml = "<YealinkIPPhoneImageScreen 
		doneAction=\"http://10.1.0.105/menu.php\" 
		Beep=\"yes\"
		Timeout=\"60\" 
		LockIn=\"yes\"
		mode=\"regular\">\n";	
#T2X
#$xml .= "<Image verticalAlign="bottom" horizontalAlign="middle" height="12" width="8">00555500005555000000000000aaaa0000aaaa0000ffff0000ffff0000ffff00fffffffff0ffff0f00ffff0000f00f00</Image>\n";
$xml .= "<Image horizontalAlign=\"middle\" verticalAlign=\"middle\" >http://10.3.5.5:9/400_240.JPG</Image>\n";
$xml .= "</YealinkIPPhoneImageScreen>\n";
push2phone("10.3.5.5","10.3.12.3",$xml);
push2phone("10.3.5.5","10.3.5.219",$xml);
push2phone("10.3.5.5","10.3.5.183",$xml);


#replace 10.1.3.8 with your Apache ip address
#replace 10.1.3.180 with your phone ip address
?>














