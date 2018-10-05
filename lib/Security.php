<?php
session_start();

class Security
{
	const hash = "d34875bb4c2040abc195b393eaf14d56dae4b1640e387bae5752713209a24e6f";
	
	static function Verify() : bool
	{
		if(isset($_SESSION['hash'])){
			return $_SESSION['hash'] === Security::hash;
		}
		return false;
	}
	
	static function Login() : bool
	{
		if(isset($_POST['password'])){
			$_SESSION['hash'] = hash('sha256', $_POST['password']);
			return Security::Verify();
		}
		
		die("<center><form method=\"POST\"><input name=\"password\" type=\"password\" required autofocus><button type=\"submit\">Verify</button></form></center>");
	}
}

if(!Security::Verify()){
	if(!Security::Login()){
		die("<center><font color=\"red\" size=\"7\"><b>YOU AIN'T AUTHORAIZED TO ACCESS THIS PAGE</b></font></center>");
	}
}

