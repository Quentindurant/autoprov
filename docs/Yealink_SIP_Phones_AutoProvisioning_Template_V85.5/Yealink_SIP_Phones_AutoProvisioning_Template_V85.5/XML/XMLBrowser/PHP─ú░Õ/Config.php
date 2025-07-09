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

$xml = "<YealinkIPPhoneConfiguration   Beep=\"yes\">\n";
$xml .= "<Title wrap=\"yes\">TextMenu This interface is just used to Test extMenu This interface is just used to Test extMenu  This interface is just used to Test extMenu  Tex!</Title>\n";
$xml .= "<Path config=\"MemKey\">
		<Item>memory1.DKtype=GroupListen</Item>
		<Item>memory1.Label=Groupqqq</Item>
		<Item>memory1.Line=0</Item>
		<Item>memory1.Value=123456789</Item>
		<Item>memory1.PickupValue=1</Item>
	</Path>\n";
$xml .= "</YealinkIPPhoneConfiguration >\n";

push2phone("10.2.9.5","10.2.9.132",$xml);
push2phone("10.2.9.5","10.2.9.83",$xml);


#replace 10.1.3.8 with your Apache ip address
#replace 10.1.3.180 with your phone ip address
?>


