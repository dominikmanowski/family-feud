<center>
<?php
require_once("Security.php");
require_once("GUI.php");

GUI::formButton("Aktualizuj tablicę", "updateBoard");
GUI::formButton("Zła odpowiedź", "wrongAnswer");
GUI::formButton("Zmiana drużyny", "changeTeam");
GUI::formButton("Następne pytanie", "nextQuestion");
?>
<hr style="width: 80%"/>
</center>
