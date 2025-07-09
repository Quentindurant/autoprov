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

$xml = "<YealinkIPPhoneTextMenu   Beep=\"yes\" Timeout=\"0\">\n";
$xml .= "<Title wrap=\"yes\">TextMenu This interface is just used to Test extMenu Tex!</Title>\n";
$xml .= "<MenuItem>
<Prompt>Show the areagf</Prompt>
<URI>http://10.2.9.7:8080/url3/TestDirectory.xml</URI>
<Dial>220</Dial>
<Selection>0&amp;menu_pos=1</Selection>
</MenuItem>\n";
$xml .= "<SoftKey index=\"3\">
	<Label>Select</Label>
	<URI>SoftKey:Select</URI>
</SoftKey>
<SoftKey index=\"4\">
	<Label>exit</Label>
	<URI>SoftKey:Exit</URI>
	<Selection>0&amp;menu_pos=1</Selection>
</SoftKey>
<SoftKey index=\"1\">
	<Label>uri</Label>
	<URI>http://10.3.5.5:9/xmlBrowser/TestInput.xml</URI>
	<Selection>0&amp;menu_pos=1</Selection>
</SoftKey>\n";
$xml .= "</YealinkIPPhoneTextMenu >\n";

push2phone("10.3.5.5","10.3.12.3",$xml);
push2phone("10.3.5.5","10.3.5.219",$xml);
push2phone("10.3.5.5","10.3.5.183",$xml);


#replace 10.1.3.8 with your Apache ip address
#replace 10.1.3.180 with your phone ip address
?>
