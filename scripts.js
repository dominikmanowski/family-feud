const players = document.querySelector(".players")
const player0 = document.querySelector(".player-0");
const player1 = document.querySelector(".player-1");
const player0name = document.querySelector(".player-0__name");
const player1name = document.querySelector(".player-1__name");
const answersBoard = document.querySelector(".answers__list");
const regularAnswers = document.querySelector(".answers");
const finalAnswers = document.querySelector(".final-answers-wrapper ");
let answers = document.querySelectorAll(".answer");
const finalAnswers0 = document.querySelectorAll(".final-answer-0");
const finalAnswers1 = document.querySelectorAll(".final-answer-1");
let answersPoints = document.querySelectorAll(".points");
const finalPoints0 = document.querySelectorAll(".final-points-0");
const finalPoints1 = document.querySelectorAll(".final-points-1");
const player0ScoreDisp = document.querySelector(".player-0__score-global");
const player1ScoreDisp = document.querySelector(".player-1__score-global");
const roundScoreDisp = document.querySelector(".score-round__value");
const player0Lives = document.querySelectorAll(".player-0__heart");
const player1Lives = document.querySelectorAll(".player-1__heart");
const failAudio = new Audio("fail.ogg");
const buzzerAudio = new Audio("buzzer.ogg");

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
  if (roundScore < 10) {
    roundScoreDisp.textContent = `00${roundScore}`;
  } else if (roundScore < 100) {
    roundScoreDisp.textContent = `0${roundScore}`;
  } else {
    roundScoreDisp.textContent = roundScore;
  }
}

function displayPlayerScore(playerNr, points) {
  if (playerNr == 0) {
    if (points < 10) {
      player0ScoreDisp.textContent = `00${points}`;
    } else if (points < 100) {
      player0ScoreDisp.textContent = `0${points}`;
    } else {
      player0ScoreDisp.textContent = points;
    }
  } else if (playerNr == 1) {
    if (points < 10) {
      player1ScoreDisp.textContent = `00${points}`;
    } else if (points < 100) {
      player1ScoreDisp.textContent = `0${points}`;
    } else {
      player1ScoreDisp.textContent = points;
    }
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
		if(data["action"] === "wait"){
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

var timer = setInterval(apiUpdate, 1000);
