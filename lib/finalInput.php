<?php
require_once("Security.php");
require_once("finalBoard.php");

$b = new finalBoard();
?>

<script src="lib/jquery-3.3.1.min.js"></script>
<script src="lib/jquery-ui.js"></script>
<script>
$( function() {
	var possibleAnswers = [
		<?php
		$possible = finalBoard::getAwardedAnswersKeys($b->getQuestionID());
		if($b->getActivePlayer() === 1){
			array_push($possible, $b->getAnswers()[0][$b->getQuestionID()]->text);
		}
		$s = "";
		foreach($possible as $x)
		{
			$s .= "\"$x\",";
		}
		print substr($s, 0, strlen($s)-1);
		?>
	];
	$("#answer").autocomplete({
		source: possibleAnswers
	});
} );
</script>

<center>
	<form method="POST">
		<input type="hidden" name="command" value="answer"/>
		<input type="text" autofocus required id="answer" name="argument"/>
		<button type="submit">Zgłoś Odpowiedź</button>
	</form>
</center>
