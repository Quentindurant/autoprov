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

$xml = "<YealinkIPPhoneTextScreen Beep=\"yes\">\n";
$xml .= "<Title>Push test</Title>\n";
$xml .= "<Text>This is a test for pushing text to a phone.</Text>\n";
$xml .= "</YealinkIPPhoneTextScreen>\n";
$xml .= "<SoftKey index=\"1\">
<Label>Dir2</Label>
<URI>http://10.3.3.4:9/update/xmlBrowser/TestDirectory1.xml</URI>
</SoftKey>
<SoftKey index=\"2\">
	<Label>exit</Label>
	<URI>SoftKey:Exit</URI>
</SoftKey>\n";

push2phone("10.3.5.5","10.3.12.3",$xml);
push2phone("10.3.5.5","10.3.5.219",$xml);
push2phone("10.3.5.5","10.3.5.183",$xml);

#replace 10.1.3.8 with your Apache ip address
#replace 10.1.3.180 with your phone ip address
?>
