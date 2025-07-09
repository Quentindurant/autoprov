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

$xml = "<YealinkIPPhoneInputScreen    Beep=\"yes\">\n";
$xml .= "<Title wrap=\"yes\">TextMenu This interface is just used to Test extMenu This interface is just used to Test extMenu  This interface is just used to Test extMenu  Tex!</Title>\n";

$xml .= "<URL>http://10.2.9.7:8080/TestDirectory.xml</URL>\n";
$xml .=  "<InputField type = \"IP\" password = \"no\" editable = \"yes\">
		<Prompt>IP Address:</Prompt>
		<Parameter>IP</Parameter>
		<Selection>2</Selection>
		<Default>192ff138</Default>
	</InputField>
	
	<InputField type = \"String\" password = \"no\" editable = \"yes\">
		<Prompt>Name:</Prompt>
		<Parameter>user</Parameter>
		<Selection>3</Selection>
		<Default>jxz</Default>
			</InputField>";

$xml .= "</YealinkIPPhoneInputScreen >\n";

push2phone("10.3.5.5","10.3.5.219",$xml);
push2phone("10.3.5.5","10.3.12.3",$xml);


#replace 10.1.3.8 with your Apache ip address
#replace 10.1.3.180 with your phone ip address
?>
