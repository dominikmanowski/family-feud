<?php
require_once("Board.php");
require_once("Question.php");
require_once("GUI.php");
class Administration
{
	
	static function changeTeam()
	{
		$b = new Board();
		$b->changeActiveTeam();
		$b->save();
	}
	
	static function nextQuestion()
	{
		$b = new Board();
		$b->incrementQuestionID();
		$max = count(json_decode(file_get_contents("db/questions/".$b->getRound().".json"), true));
		if($b->getQuestionID() === $max){
			if($b->getRound() === "final"){
				return;
			}
			else{
				$b->startFinal();
				file_put_contents("db/question.json", json_encode(json_decode(file_get_contents("db/questions/final.json"), true)[0]));
			}
		}
		else{
			file_put_contents("db/question.json", json_encode(json_decode(file_get_contents("db/questions/".$b->getRound().".json"), true)[$b->getQuestionID()]));
		}
		$b->save();
	}
	
	static function wrongAnswer()
	{
		$b = new Board();
		if($b->getActiveTeam() === "A")
			$opponent = "B";
		else
			$opponent = "A";
		if(!($b->takeLife())){ //jeśli nie przeżył
			if($b->getTeam($opponent)->lives > 0){
				$b->save();
				Administration::changeTeam();
				GUI::alert("Nastąpiła przymusowa zmiana strony");
				return;
			}
			else{
				$b->save();
				Administration::nextQuestion();
				GUI::alert("Nastąpiła przymusowa zmiana pytania");
				return;
			}
		}
		$b->save();
	}
	
	static function answer(int $anwserID)
	{
		$b = new Board();
		$q = new Question();
		$b->addPoints($q->getPoints($anwserID));
		$q->uncover($anwserID);
		$q->save();
		$b->save();
	}
}
