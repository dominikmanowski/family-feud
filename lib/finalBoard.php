<?php

class finalBoard implements JsonSerializable
{
	private $answers;
	private $questionID;
	private $active;
	private $makeSound;
	
	public function __construct()
	{
		@$data = file_get_contents("db/finalBoard.json");
		if($data === FALSE){
			file_put_contents("db/finalBoard.json", json_encode([
				"answers" => [[], []],
				"questionID" => 0,
				"active" => 0,
				"makeSound" => false
			]));
			$data = file_get_contents("db/finalBoard.json");
		}
		$data = json_decode($data, false);
		$this->answers = $data->answers;	
		$this->questionID = $data->questionID;
		$this->active = $data->active;
		$this->makeSound = $data->makeSound;
	}
	
	public function getActivePlayer()
	{
		return $this->active;
	}
	
	public function getAnswersAmounts()
	{
		return [count($this->answers[0]), count($this->answers[1])];
	}
	
	static function getNumberOfQuestions() : int
	{
		return count(json_decode(file_get_contents("db/questions/final.json")));
	}
	
	static function getAwardedAnswers($QID)
	{
		return json_decode(file_get_contents("db/questions/final.json"), true)[$QID]["answers"];
	}
	
	static function getAwardedAnswersKeys($QID)
	{
		$a = finalBoard::getAwardedAnswers($QID);
		$result = array();
		for($i = 0; $i < count($a); ++$i)
		{
			array_push($result, $a[$i]["answer"]);
		}
		return $result;
	}
	
	public function getAnswers()
	{
		return $this->answers;
	}
	
	public function getQuestionID() : int
	{
		return (int)$this->questionID;
	}
	
	public function getMakeSound($change) : bool
	{
		$result = (bool)$this->makeSound;
		if($change){
			$this->makeSound = false;
			$this->save();
		}
		return $result;
	}
	
	public function makeSound()
	{
		$this->makeSound = true;
	}
	
	public function isNewAnswer($answer)
	{
		for($player = 0; $player < 2; ++$player)
		{
			for($i = 0; $i < $this->getAnswersAmounts()[$player]; ++$i)
			{
				if($this->answers[$player][$i]->text === $answer){
					return false;
				}
			}
		}
		return true;
	}
	
	public function assignAnswer($answer)
	{
		$this->answers[$this->active][$this->questionID]["text"] = $answer;
		$awarded = finalBoard::getAwardedAnswersKeys($this->questionID);
		$found = array_search($answer, $awarded);
		if($found === FALSE){
			$this->answers[$this->active][$this->questionID]["points"] = 0;
		}
		else{
			$this->answers[$this->active][$this->questionID]["points"] = finalBoard::getAwardedAnswers($this->questionID)[$found]["points"];
		}
		
		$this->questionID += 1;
		if($this->questionID === finalBoard::getNumberOfQuestions()){
			if($this->active === 1){
				//game over
			}
			else{
				$this->questionID = 0;
				$this->active = 1;
			}
		}
	}
	
	public function jsonSerialize()
	{
		return [
			"answers" => $this->answers,
			"questionID" => $this->questionID,
			"active" => $this->active,
			"makeSound" => $this->makeSound
		];
	}
	
	public function save()
	{
		file_put_contents("db/finalBoard.json", json_encode($this));
	}
}
