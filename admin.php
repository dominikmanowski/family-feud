<?php
require_once("lib/Security.php");
require_once("lib/Board.php");
require_once("lib/Question.php");
require_once("lib/Administration.php");

if(isset($_POST['command'])){
	if(isset($_POST['argument'])){
		Administration::{$_POST['command']}($_POST['argument']);
	}
	else{
		Administration::{$_POST['command']}();
	}
}

include "lib/topAdmin.php";
include "lib/middleAdmin.php";
include "lib/bottomAdmin.php";
