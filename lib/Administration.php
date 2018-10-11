<?php
require_once("Board.php");
require_once("Question.php");
require_once("GUI.php");
class Administration
{
	const startFinalVerificationString = "4D2R$%gdat7U6RFUYFUGDS98Y98Y98t&^r&^r^%dytfyufuow&d#o";

	static function startFinal($verification)
	{
		if($verification !== Administration::startFinalVerificationString){
			return;
		}
		$b = new Board();
		$b->startFinal();
		$b->save();
		file_put_contents("db/apiHash.json", json_encode(["startFinal"]));
		die( "<script>window.location.href=\"/finalAdmin.php\";</script>");
	}
	
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
		$b->resetLifes();
		$max = count(json_decode(file_get_contents("db/questions/regular.json"), true));
		$b->save();
		if($b->getQuestionID() === $max){
			Administration::startFinal(Administration::startFinalVerificationString);
		}
		else{
			file_put_contents("db/question.json", json_encode(json_decode(file_get_contents("db/questions/".$b->getRound().".json"), true)[$b->getQuestionID()]));
		}
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
				$b->takeLife();
				$b->takeLife();
				$b->save();
				GUI::alert("Nastąpiła przymusowa zmiana strony");
				return;
			}
			/*else{
				$b->save();
				Administration::nextQuestion();
				GUI::alert("Nastąpiła przymusowa zmiana pytania");
				return;
			}*/
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
	
	static function updateBoard()
	{
		file_put_contents("db/apiHash.json", json_encode([hash('sha512', " ")]));
	}
	
	static function showAnswers()
	{
		$q = new Question;
		$no = count($q->getAnswers());
		for($i = 0; $i < $no; ++$i)
		{
			$q->uncover($i);
		}
		$q->save();
		Administration::updateBoard();
	}
}
