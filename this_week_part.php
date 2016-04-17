	<table border="0" cellspacing="0" cellpadding="6" class="tborder">
		<tr>
		<td class="thead" colspan="2"><strong>Next week</strong></td>
		</tr>
		<tr>
		<td class="trow1"><?=get_this_week_topic($dbConnection)?></td><br/>
		</tr>
		<tr>
		<td class="trow2">Current calculated podcast time:
		<?php
$possible_times = get_this_week_time($dbConnection);
if (count($possible_times) == 0) {
	echo 'No one joined yet! Be the first to join.<br/>';
}
else {
	foreach($possible_times as $p) {
		echo '<br/>';
		$tt = get_user_current_timezone($dbConnection);
		$users_at_time = get_users_at_specific_time($p['start_time'], $dbConnection);
		$users_string = ' ';
		foreach ($users_at_time as $us) {
			$users_string .= $us['username'] . ' ';
		}
		
		echo convert_time_to_user_timezone_string($p['start_time'], $tt).
			' GMT'.($tt<0?'-':'+').$tt.
			' With '.$p['nb_users'].' users: '. $users_string;
	}
}
		?> </td>
		</tr>
	</table>

	<br/>

