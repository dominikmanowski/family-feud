<?php
require_once("lib/Security.php");
require_once("lib/finalBoard.php");
require_once("lib/finalAdministration.php");
require_once("lib/GUI.php");

if(isset($_POST['command'])){
	if(isset($_POST['argument'])){
		finalAdministration::{$_POST['command']}($_POST['argument']);
	}
	else{
		finalAdministration::{$_POST['command']}();
	}
}

$b = new finalBoard();
if($b->getQuestionID() === finalBoard::getNumberOfQuestions()){
	die("<script>window.location.href=\"/gameOver.php\"</script>");
}
?>
<center>
<?php
GUI::formButton("Aktualizuj tablicę", "updateBoard");
?>
	<h1>
		Active player: <?php $active = $b->getActivePlayer(); if($active === 1) print "right"; else print "left";?>
	</h1>
	<h1>
		Question
		<br/>
		<b>
			<?php print $b->getQuestionID()+1;?>/<?php print finalBoard::getNumberOfQuestions();?>
		</b>
	</h1>
	<?php include "lib/finalInput.php";?>
</center>
