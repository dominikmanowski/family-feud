<?php

class GUI
{
	static function formButton(string $text, string $command, $argument=NULL)
	{
		print "<form method=\"POST\" id=\"".hash('sha256', $text.$command.$argument)."\">";
		print "<input name=\"command\" value=\"$command\" type=\"hidden\"></input>";
		if($argument !== NULL){
			print "<input name=\"argument\" value=\"$argument\" type=\"hidden\"></input>";
		}
		print "<button type=\"submit\">$text</button></form>";
	}
	
	static function alert(string $text)
	{
		print "<script> window.alert(\"$text\"); </script>";
	}
}
