	<table border="0" cellspacing="0" cellpadding="6" class="tborder">
		<tr>
		<td class="thead" colspan="2"><strong>Time Selection</strong></td>
		</tr>

		<tr>
		<td class="trow1">
			<strong>My Timezone</strong></br>
			<form action='savetimezone.php' method='post'>
				<select name='timezone'>
					<?php

					// getting the current timezone to use later in the interface
					// it is necessary so we can set the select input directly to
					// the current one
					$user_timezone = get_user_current_timezone($dbConnection);

					// save it in javascript too, it could be useful later
					echo "<script>\n";
					echo "var user_timezone = $user_timezone;";
					echo "</script>";

					for ($i =-12; $i<12; $i++) {
						if ($i == $user_timezone) {
							echo "<option selected value='$i'>GMT".($i>=0?'+'.$i:$i).'</option>';
						}
						else {
							echo "<option value='$i'>GMT".($i>=0?'+'.$i:$i).'</option>';
						}
					}

					?>
				</select>
				<br />
				<div><input type="submit" class="button" name="submit" value="Set Timezone" /></div>

			</form>
		</td>
		</tr>

		<tr>
		<td class="trow1">
			<strong>My Available Time</strong></br>
			<?php
				$avl_time = get_user_available_time($dbConnection);
				echo "<script>\n";
				echo "var avl_time = ".json_encode($avl_time).";\n";
				echo "</script>\n";
				//TODO: loop and create TIME => BUTTON TO REMOVE
				if (count($avl_time) === 0) {
					echo 'No time set for this week<br/>';
				}
				else {
					foreach ($avl_time as $t) {
						echo convert_time_to_user_timezone_string($t['start_time'], $user_timezone)."<br/>";
					}
				}
			?>
		</td>
		</tr>
		<tr>
		<td class="trow2">
		<strong>Add Time</strong><br/>
		<br/>
		Select at least two hours where you are available:<br/>

		<form id='time_selection' action='change_available_time.php' method='post'>
		</form>
		</td>
		</tr>
	</table>
	<br/>
<script>
var days_arr = {
	'-1': 'Sun',
	'0':  'Mon',
	'1':  'Tue',
	'2':  'Wed',
	'3':  'Thu',
	'4':  'Fri',
	'5':  'Sat',
	'6':  'Sun',
	'7':  'Mon'
};

var day_selector = document.getElementById('time_selection');
var inputs_to_add = '';
var cur_day = '';

for (var i = 0; i< 24*7; i++) {
	var day_on_gmt_0 =  Math.floor(i/24.0);
	var time_on_gmt_0 = i % 24.0;

	var day_on_user_gmt = Math.floor((i+user_timezone)/24.0);
	/*
	console.log(day_on_gmt_0 + "   " + day_on_user_gmt);
	 */
	var prefix = 'Next week';

	if (day_on_user_gmt < 0) {
		prefix = 'This week';
		day_on_user_gmt = 7 + day_on_user_gmt;
	}
	if (day_on_user_gmt == 7) {
		prefix = 'After Next week';
	}

	var time_on_user_gmt = (i + user_timezone) - (day_on_gmt_0 * 24.0);
	if (time_on_user_gmt > 23) {
		time_on_user_gmt %= 24;
	}
	if (time_on_user_gmt < 0) {
		time_on_user_gmt = 24 + time_on_user_gmt;
	}
	if (cur_day != days_arr[day_on_user_gmt]) {
		inputs_to_add += "\n<hr/>\n";
		cur_day = days_arr[day_on_user_gmt];
	}

	/*
	console.log(
		"GMT0 : " +
		days_arr[day_on_gmt_0] +
		" time => " +
		time_on_gmt_0 +
		" till " +
		( (time_on_gmt_0+2) > 24 ? '1' : ''+(time_on_gmt_0+2))
	);
	console.log(
		"GMT User : " +
		days_arr[day_on_user_gmt] +
		" time => " +
		time_on_user_gmt +
		" till " +
		( (time_on_user_gmt+2) > 24 ? '1' : ''+(time_on_user_gmt+2))
	);
	 */
	inputs_to_add += "\n" + prefix + " " +
		days_arr[day_on_user_gmt] +
		" time => " +
		(time_on_user_gmt>9?time_on_user_gmt:'0'+ time_on_user_gmt) +
		" till " +
		( (time_on_user_gmt+2) > 24 ? '01: ' :
			( (time_on_user_gmt+2>9) ? (time_on_user_gmt+2) :
				('0'+(time_on_user_gmt+2)))+": ")+
		"<input "+
		(check_if_time_was_chosen(i) ? ' checked ' : '')
		+" type='checkbox' name='schedule[]' value='"+i+"' /><br/>";
}

function check_if_time_was_chosen(s) {
	for(var i=0; i< avl_time.length; i++) {
		if (avl_time[i]['start_time'] == s) {
			return true;
		}
	}
	return false;
}
console.log(avl_time);
inputs_to_add += "\n<hr/>\n";
inputs_to_add += "\n<div><input type='submit' class='button' name='submit' value='Change Available time' /></div>";
day_selector.innerHTML = inputs_to_add;


</script>
