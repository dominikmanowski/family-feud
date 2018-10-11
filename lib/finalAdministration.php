<?php
require_once("finalBoard.php");

class finalAdministration
{
	static function updateBoard()
	{
		file_put_contents("db/apiFinalHash.json", json_encode([hash('sha512', " ")]));
	}
	
	static function answer($answer)
	{
		$answer = strtolower($answer);
		$b = new finalBoard();
		if($b->isNewAnswer($answer)){
			$b->assignAnswer($answer);
		}
		else{
			$b->makeSound();
		}
		$b->save();
	}
}
