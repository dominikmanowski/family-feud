<center>
<hr style="width: 80%"/>
<?php
require_once("Security.php");
require_once("GUI.php");
require_once("Board.php");
$b = new Board();
?>
<table width="100%">
	<tr>
		<td width="33%">
			<center>
				<u>Pytanie</u>
			</center>
		</td>
		<td width="33%">
			<center>
				<u>Runda</u>
			</center>
		</td>
		<td width="33%">
			<center>
				<u>Aktywny Zespół</u>
			</center>
		</td>
	</tr>
	<tr>
		<td width="33%">
			<center>
				<b>
					<?php
						print $b->getQuestionID()+1;
						print "/";
						print $b->getNumberOfQuestions();
					?>
				</b>
			</center>
		</td>
		<td width="33%">
			<center>
				<b>
					<?php
						print $b->getRound();
					?>
				</b>
			</center>
		</td>
		<td width="33%">
			<center>
				<b>
					<?php
						if($b->getActiveTeam() === "A"){
							print "Left";
						}
						else if($b->getActiveTeam() === "B"){
							print "Right";
						}
						else {
							print "-";
						}
					?>
				</b>
			</center>
		</td>
	</tr>
</table>
</center>
