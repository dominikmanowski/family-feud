const select = name => document.querySelector(name)
const selectAll = name => document.querySelector(name)

const players = select(".players")
const player0 = select(".player-0");
const player1 = select(".player-1");
const player0name = select(".player-0__name");
const player1name = select(".player-1__name");
const answersBoard = select(".answers__list");
const regularAnswers = select(".answers");
const finalAnswers = select(".final-answers-wrapper ");
let answers = selectAll(".answer");
const finalAnswers0 = selectAll(".final-answer-0");
const finalAnswers1 = selectAll(".final-answer-1");
let answersPoints = selectAll(".points");
const finalPoints0 = selectAll(".final-points-0");
const finalPoints1 = selectAll(".final-points-1");
const player0ScoreDisp = select(".player-0__score-global");
const player1ScoreDisp = select(".player-1__score-global");
const roundScoreDisp = select(".score-round__value");
const player0Lives = selectAll(".player-0__heart");
const player1Lives = selectAll(".player-1__heart");
const failAudio = new Audio("fail.ogg");
const buzzerAudio = new Audio("buzzer.ogg");


const pointsFormat = points => {
  if (points < 10) {
    return `00${points}`;
  } else if (points < 100) {
    return `0${points}`;
  } else {
    return `${points}`;
  }
} 


let finalAnswersBoard;

let lives = [3, 3];
let questionID = -1;
let soundPlay = true;

function markActivePlayer(playerNr) {
  if (playerNr == 0) {
    player0.classList.add("active");
    player1.classList.remove("active");
  } else if (playerNr == 1) {
    player0.classList.remove("active");
    player1.classList.add("active");
  }
}

function displayAnswer(i, answer, points) {
  answers[i].textContent = answer;
  answersPoints[i].textContent = points;
}

function displayRoundScore(roundScore) {
  roundScoreDisp.textContent = pointsFormat(roundScore);
}

function displayPlayerScore(playerNr, points) {
  if (playerNr == 0) {
    player0ScoreDisp.textContent = pointsFormat(points);
  } else if (playerNr == 1) {
    player1ScoreDisp.textContent = pointsFormat(points);
  }

  roundScoreDisp.textContent = "000";
}

function lostLive(playerNr) {
  if (playerNr == 0) {
    player0Lives[lives[0] - 1].classList.add("hide");
  } else if (playerNr == 1) {
    player1Lives[lives[1] - 1].classList.add("hide");
  }
  if(soundPlay){
  	soundPlay = false;
  	failAudio.currentTime = 0;
  	failAudio.play();
  }
  lives[playerNr]--;
}

function cleanBoard(answersAmount = 7) {
  while (answersBoard.hasChildNodes()) {
    answersBoard.removeChild(answersBoard.lastChild);
  }

  for (let i = 0; i < answersAmount; i++) {
    answersBoard.innerHTML += `<li class="answers__item"><span class="number">${i + 1}.</span><span class="answer">...........................</span><span class="points">00</span></li>`;
  }

  player0Lives.forEach(live => live.classList.remove("hide"));
  player1Lives.forEach(live => live.classList.remove("hide"));

  lives = [3, 3]

  player0.classList.remove("active");
  player1.classList.remove("active");

  roundScoreDisp.textContent = "000";
  answers = document.querySelectorAll(".answer");
  answersPoints = document.querySelectorAll(".points");
}

function showFinalBoard(winner) {  
    
  if (winner == 0) {
    player0name.classList.add("center")
    player1.classList.add("hide");
    player0Lives.forEach(live => live.classList.add("hide"))
    
  } else if (winner == 1) {
    player1name.classList.add("center")
    player0.classList.add("hide");
    player1Lives.forEach(live => live.classList.add("hide"))
  }
  
  player0.classList.remove("active");
  player1.classList.remove("active");
  player0ScoreDisp.innerHTML = "000"
  player1ScoreDisp.innerHTML = "000"
  regularAnswers.classList.add("d-none");
  finalAnswers.classList.remove("d-none");

}

function displayFinalAnswer(i, answer, points, player) {
  if (player == 0) {
    finalAnswers0[i].textContent = answer;
    finalPoints0[i].textContent = points;
  } else if (player == 1) {
    finalAnswers1[i].textContent = answer;
    finalPoints1[i].textContent = points;
  }


}

function playBuzzer() {
  buzzerAudio.currentTime = 0;
  buzzerAudio.play();
}

function apiUpdate()
{
	$.post(window.location.href+"api.php", {"Qid" : questionID}, function(result){
		soundPlay = true;
		var data = JSON.parse(result);
		if(data["action"] === "startFinal"){
			showFinalBoard(data["winner"]);
			apiFinalUpdate();
			clearInterval(timer);
			timer = setInterval(apiFinalUpdate, 1000);
		}
		else if(data["action"] === "wait"){
			return;
		}
		else if(data["action"] === "clean"){
			cleanBoard(data["answersAmount"]);
			displayPlayerScore(0, data["scoreA"]);
			displayPlayerScore(1, data["scoreB"]);
			questionID = data["Qid"];
		}
		else if(data["action"] === "update"){
			displayPlayerScore(0, data["scoreA"]);
			displayPlayerScore(1, data["scoreB"]);
			displayRoundScore(data["roundScore"]);
			while(lives[0] > data["livesA"])
			{
				lostLive(0);
			}
			while(lives[1] > data["livesB"])
			{
				lostLive(1);
			}
			
			for(var i = 0; i < data["answersAmount"]; i += 1)
			{
				if(data["answers"][i]["isHidden"] == false){
					displayAnswer(i, data["answers"][i]["answer"], data["answers"][i]["points"]);
				}
			}
			
			if(data["activePlayer"] === "A"){
				markActivePlayer(0);
			}
			else if(data["activePlayer"] === "B"){
				markActivePlayer(1);
			}
			questionID = data["Qid"];
		}
	});
}

function apiFinalUpdate()
{
	$.post(window.location.href+"apiFinal.php", {"Qid" : questionID}, function(result){
		var data = JSON.parse(result);
		let score = [0, 0]; 
		if(data["action"] === "wait"){
			return;
		}
		else if(data["action"] === "update"){
			for(var player = 0; player < 2; player += 1)
			{
				for(var i = 0; i < data["answersAmount"][player]; i += 1)
				{
					displayFinalAnswer(i, data["answers"][player][i]["text"], data["answers"][player][i]["points"], player);
					score[player] += data["answers"][player][i]["points"];
				}
				displayPlayerScore(player, score[player]);
			}
			
			displayRoundScore(score[0] + score[1]);
			questionID = data["Qid"];
			if(data["makeSound"] == true){
				playBuzzer();
			}
		}
	});
}

var timer = setInterval(apiUpdate, 1000);
