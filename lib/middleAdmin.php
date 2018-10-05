<center>
<?php
require_once("Security.php");
require_once("GUI.php");

$q = new Question();
$i = 0;
foreach($q->getAnswers() as $a)
{
	if($a->isHidden){
		GUI::formButton($a->answer, "answer", $i);
	}
	++$i;
}
?>
</center>
