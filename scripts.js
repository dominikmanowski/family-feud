const player0 = document.querySelector(".player-0");
const player1 = document.querySelector(".player-1");
const answersBoard = document.querySelector(".answers__list");
let answers = document.querySelectorAll(".answer");
const answersPoints = document.querySelectorAll(".points");
const player0ScoreDisp = document.querySelector(".player-0__score-global");
const player1ScoreDisp = document.querySelector(".player-1__score-global");
const roundScoreDisp = document.querySelector(".score-round__value");
const player0Lives = document.querySelectorAll(".player-0__heart");
const player1Lives = document.querySelectorAll(".player-1__heart");

let lives = [3, 3];
let questionID = -1;

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
}

function apiUpdate()
{
	$.post(window.location.href+"api.php", {"Qid" : questionID}, function(result){
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
