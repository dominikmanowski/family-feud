<?php
require_once("lib/finalBoard.php");

$b = new finalBoard();

if(!isset($_POST['Qid'])){
	die("[]");
}

$lastHash = json_decode(file_get_contents("db/apiFinalHash.json"), true)[0];
$hash = hash('sha512', $b->getAnswersAmounts()[0]."|".$b->getAnswersAmounts()[1]."|".$b->getQuestionID()."|".$b->getMakeSound(false));

if($lastHash === $hash){
	$data = [
		"action" => "wait"
	];
}
else{//update
	$data = [
		"action" => "update",
		"answersAmount" => $b->getAnswersAmounts(),
		"answers" => $b->getAnswers(),
		"Qid" => $b->getQuestionID(),
		"makeSound" => $b->getMakeSound(true)
	];
}

file_put_contents("db/apiFinalHash.json", json_encode([$hash]));
print json_encode($data);
