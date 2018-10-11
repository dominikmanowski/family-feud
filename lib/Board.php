<?php
require_once("Question.php");

class Board implements JsonSerializable
{
	private $teamA;
	private $teamB;
	private $bufor;
	private $active;
	private $round;
	private $questionID;
	
	public function __construct()
	{
		@$data = file_get_contents("db/board.json");
		if($data === FALSE){
			file_put_contents("db/board.json", json_encode([
				"teamA" => [
					"lives" => 3,
					"score" => 0
				],
				"teamB" => [
					"lives" => 3,
					"score" => 0
				],
				"bufor" => 0,
				"active" => "A",
				"questionID" => 0,
				"round" => "regular"
			]));
			$data = file_get_contents("db/board.json");
		}
		
		$data = json_decode($data, false);
		$this->teamA = $data->teamA;
		$this->teamB = $data->teamB;
		$this->bufor = $data->bufor;
		$this->active = $data->active;
		$this->round = $data->round;
		$this->questionID = $data->questionID;
	}
	
	public function jsonSerialize()
	{
        return [
        	"teamA" => ["lives" => $this->teamA->lives,	"score" => $this->teamA->score],
        	"teamB" => ["lives" => $this->teamB->lives,	"score" => $this->teamB->score],
        	"bufor" => $this->bufor,
        	"active" => $this->active,
        	"round" => $this->round,
			"questionID" => $this->questionID
        ];
    }

	public function getRound() : string
	{
		return $this->round;
	}
	
	public function getQuestionID() : int
	{
		return $this->questionID;
	}
	
	public function getActiveTeam()
	{
		return $this->active;
	}
	
	public function getNumberOfQuestions() : int
	{
		return count(json_decode(file_get_contents("./db/questions/".$this->getRound().".json"), true));
	}
	
	public function save()
	{
		file_put_contents("db/board.json", json_encode($this));
	}
	
	public function changeActiveTeam()
	{
		if($this->active === "A"){
			$this->active = "B";
		}
		else if($this->active === "B"){
			$this->active = "A";
		}
	}
	
	public function getTeam(string $name)
	{
		if($name === "A")
			return $this->teamA;
		else if($name === "B")
			return $this->teamB;
	}
	
	public function takeLife() : bool
	{
		if($this->active === "A"){
			$this->teamA->lives -= 1;
			return ($this->teamA->lives > 0);
		}
		else if($this->active === "B"){
			$this->teamB->lives -= 1;
			return ($this->teamB->lives > 0);
		}
	}
	
	public function addPoints(int $x)
	{
		$this->bufor += $x;
	}
	
	public function incrementQuestionID()
	{
		$this->questionID += 1;
		if($this->active === "A"){
			$this->teamA->score += $this->bufor;
		}
		else if($this->active === "B"){
			$this->teamB->score += $this->bufor;
		}
		$this->bufor = 0;
	}
	
	public function startFinal()
	{
		$this->questionID = 0;
		$this->round = "final";
		if($this->active === "A"){
			$this->teamA->score += $this->bufor;
		}
		else if($this->active === "B"){
			$this->teamB->score += $this->bufor;
		}
		$this->bufor = 0;
	}
	
	public function getBufor() : int
	{
		return $this->bufor;
	}
	
	public function resetLifes()
	{
		$this->teamA->lives = 3;
		$this->teamB->lives = 3;
	}
}
