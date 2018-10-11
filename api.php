<?php
require_once("lib/Board.php");
require_once("lib/Question.php");

$b = new Board();
$q = new Question();

if(!isset($_POST['Qid'])){
	die("[]");
}

$lastHash = json_decode(file_get_contents("db/apiHash.json"), true)[0];
$hash = hash('sha512', $b->getTeam("A")->lives."|".$b->getTeam("A")->score."|".$b->getTeam("B")->lives."|".$b->getTeam("B")->score."|".$b->getBufor()."|".$b->getActiveTeam()."|".$b->getQuestionID());

if($lastHash === "startFinal" || $b->getRound() === "final"){
	if($b->getTeam("A")->score > $b->getTeam("B")->score){
		$winner = 1;
	}
	else{
		$winner = 0;
	}
	$data = [
		"action" => "startFinal",
		"winner" => $winner
	];
}
else if($_POST['Qid'] != $b->getQuestionID()){	//clean
	$data = [
		"action" => "clean",
		"answersAmount" => count($q->getAnswers()),
		"scoreA" => $b->getTeam("A")->score,
		"scoreB" => $b->getTeam("B")->score,
		"Qid" => $b->getQuestionID()
	];
}
else if($lastHash === $hash){	//wait
	$data = ["action" => "wait"];
}
else{	//update
	$data = [
		"action" => "update",
		"activePlayer" => $b->getActiveTeam(),
		"answersAmount" => count($q->getAnswers()),
		"scoreA" => $b->getTeam("A")->score,
		"scoreB" => $b->getTeam("B")->score,
		"roundScore" => $b->getBufor(),
		"livesA" => $b->getTeam("A")->lives,
		"livesB" => $b->getTeam("B")->lives,
		"answers" => $q->getAnswers(),
		"Qid" => $b->getQuestionID()
	];
}

file_put_contents("db/apiHash.json", json_encode([$hash]));
print json_encode($data);
