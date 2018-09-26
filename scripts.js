const player0 = document.querySelector(".player-0");
const player1 = document.querySelector(".player-1");
const answers = document.querySelectorAll(".answer");
const answersPoints = document.querySelectorAll(".points");
const player0ScoreDisp = document.querySelector(".player-0__score-global");
const player1ScoreDisp = document.querySelector(".player-1__score-global");
const roundScoreDisp = document.querySelector(".score-round__value");
const player0Lives = document.querySelectorAll(".player-0__heart")
const player1Lives = document.querySelectorAll(".player-1__heart")

let scores, roundScore, activePlayer, questionsLeft, counterRound;

scores = [0, 0];
lives = [3, 3]
roundScore = 0;
activePlayer = 0;
counterRound = false;

function changeActivePlayer() {
    player0.classList.toggle("active");
    player1.classList.toggle("active");
}


function displayAnswer(i, answer, points) {
    answers[i].textContent = answer;
    answersPoints[i].textContent = points;
    
}

function displayRoundScore() {
    if (roundScore < 10) {
        roundScoreDisp.textContent = `00${roundScore}`;
    } else if (roundScore < 100) {
        roundScoreDisp.textContent = `0${roundScore}`;
    } else {
        roundScoreDisp.textContent = roundScore;
    }
}

function countRoundScore() {
    answersPoints.forEach(score => roundScore += Number(score.textContent))
}

function updatePlayerScore(i) {
    scores[i] += roundScore;
    if (i == 0){        
        player0ScoreDisp.textContent = scores[i];
    } else {
        player1ScoreDisp.textContent = scores[i];
    }
    roundScore = 0
}

function lostLive(player) {
    if (player == 0) {
        player0Lives[lives[0]-1].classList.add("hide")
        lives[player] --
    } else if (player == 1) {
        player1Lives[lives[1]-1].classList.add("hide")
        lives[player] --
    }
}
