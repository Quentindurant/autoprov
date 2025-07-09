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

$xml = "<YealinkIPPhoneDirectory 
		defaultIndex=\"1\"
		next=\"http://10.3.5.5:9/xmlBrowser/TestExecute.xml\" 
		previous=\"http://10.3.5.5:9/xmlBrowser/Testmenu.xml\" 
		Beep=\"yes\" 
		cancelAction=\"http://10.3.5.5:9/xmlBrowser/Testmenu.xml\" 
		Timeout=\"50\" 
		LockIn=\"no\">\n";
		
#	<!--<Title wrap="yes">Remote Directory This interface is used to test 23423 4324524 hght dfsdf dsdfsdf asda </Title>-->

	
$xml .= "<MenuItem> 
		<Prompt>John</Prompt>
		<URI> 10.3.5.219</URI>
	</MenuItem>

	<MenuItem>
		<Prompt>Doe</Prompt>
		<URI>4326</URI>
	</MenuItem>

	<MenuItem>
		<Prompt>Wayne</Prompt>
		<URI>9982691234</URI>
	</MenuItem>
	
	<MenuItem>
		<Prompt>test</Prompt>
		<URI>9982691234</URI>
	</MenuItem>
	
	<MenuItem> 
		<Prompt>Mike</Prompt>
		<URI> 10.50.10.49</URI>
	</MenuItem>

	<MenuItem>
		<Prompt>Thomas</Prompt>
		<URI>4326</URI>
	</MenuItem>

	<MenuItem>
		<Prompt>Yang</Prompt>
		<URI>9982691234</URI>
	</MenuItem>\n";
  
#<!--
#	<SoftKey index="1">
#		<Label>Dial</Label>
#		<URI>SoftKey:Dial</URI>
#	</SoftKey>
#
#	<SoftKey index="4">
#		<Label>exit</Label>
#		<URI>SoftKey:Exit</URI>
#	</SoftKey>
#-->
$xml .= "</YealinkIPPhoneDirectory>\n";
push2phone("10.3.5.5","10.3.12.3",$xml);
push2phone("10.3.5.5","10.3.5.219",$xml);
push2phone("10.3.5.5","10.3.5.183",$xml);

?>