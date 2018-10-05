<?php

class Question implements JsonSerializable
{
	private $question;
	private $answers;
	
	public function __construct()
	{
		@$data = file_get_contents("db/question.json");
		if($data === FALSE){
			$board = new Board();
			$round = $board->getRound();
			$tmp = json_decode(file_get_contents("db/questions/$round.json"), true)[0];
			file_put_contents("db/question.json", json_encode($tmp));
			$data = file_get_contents("db/question.json");
		}
		$data = json_decode($data, false);
		$this->question = $data->question;
		$this->answers = $data->answers;
	}
	
	public function jsonSerialize()
	{
        $tab = ["question" => $this->question];
        $tab["answers"] = [];
        foreach($this->answers as $x)
        {
        	array_push($tab["answers"], ["answer" => $x->answer, "points" => $x->points, "isHidden" => $x->isHidden]);
        }
        return $tab;
    }
    
    public function getAnswers()
    {
    	return $this->answers;
    }
    
    public function save()
	{
		file_put_contents("db/question.json", json_encode($this));
	}
	
	public function getPoints(int $id)
	{
		return $this->answers[$id]->points;
	}
	
	public function uncover(int $id)
	{
		$this->answers[$id]->isHidden = false;
	}
}
