<?php

include_once('config.php');


function get_db_handle() {
	global $db_config;
	return new PDO($db_config['host'], $db_config['user'], $db_config['password']);
}

function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name
    $secure = false;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: login.php");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session 
    session_regenerate_id(true);    // regenerated the session, delete the old one. 
}

function get_this_week_topic($psql) {
	$query = "SELECT
		topic.name, topic.description
		FROM topic
		WHERE topic.id = (select max(this_week.id) from this_week)
		LIMIT 1";
	$sth = $psql->prepare($query);
	$sth->execute(array());
	$result = $sth->fetchAll();
	$result = $result[0];
	return "<strong>{$result['name']}</strong><br/>{$result['description']}";
}

function convert_time_to_user_timezone_string($t, $u_timezone) {
	$days_arr = array(
		'-1' => 'Sun',
		'0' =>  'Mon',
		'1' => 'Tue',
		'2' => 'Wed',
		'3' => 'Thu',
		'4' => 'Fri',
		'5' => 'Sat',
		'6' => 'Sun',
		'7' => 'Mon'
	);

	$day_on_gmt_0 =  intval($t/24.0);
	$time_on_gmt_0 = $t % 24.0;
	$day_on_user_gmt = intval(($t+$u_timezone)/24.0);
	$prefix = 'Next week';
	if ($day_on_user_gmt < 0) {
		$prefix = 'This week';
		$day_on_user_gmt = 7 + $day_on_user_gmt;
	}
	if ($day_on_user_gmt == 7) {
		$prefix = 'After Next week';
	}

	$time_on_user_gmt = ($t + $u_timezone) - ($day_on_gmt_0 * 24.0);
	if ($time_on_user_gmt > 23) {
		$time_on_user_gmt %= 24;
	}
	if ($time_on_user_gmt < 0) {
		$time_on_user_gmt = 24 + $time_on_user_gmt;
	}
	return $prefix . " " . $days_arr[$day_on_user_gmt] .
		" time => " .
		($time_on_user_gmt > 9 ?
			$time_on_user_gmt :
			'0' . $time_on_user_gmt
		) .
		" till " .
		(
			($time_on_user_gmt+2) > 24 ?
			'01 ' :
			(
				($time_on_user_gmt+2 > 9) ?
				($time_on_user_gmt+2) :
				('0'+($time_on_user_gmt+2))
			)
		);
}


function get_user_available_time($psql) {
	$query = "SELECT
		start_time FROM available_times
		WHERE uid = ?
		ORDER BY start_time ASC";
	$sth = $psql->prepare($query);
	$sth->execute(array($_SESSION['user_id']));
	$result = $sth->fetchAll();
	return $result;
}

function set_user_availabe_time($schedule, $psql) {
	$query = "DELETE FROM available_times
		WHERE uid = ?";
	$sth = $psql->prepare($query);
	$result = $sth->execute(array($_SESSION['user_id']));
	var_dump($result);
	foreach ($schedule as $s) {
		$s = intval($s);
		$query = "INSERT INTO available_times
			(uid, start_time, topic_id)
			VALUES(?,?, (select id from this_week limit 1) )";
		$sth = $psql->prepare($query);
		$result = $sth->execute(array($_SESSION['user_id'], $s));
	}
}

function get_this_week_time($psql) {
	//calculate the best available time - the one with the most users
	$query = "SELECT COUNT(uid) nb_users,start_time
		FROM available_times
		GROUP BY start_time
		ORDER BY nb_users DESC
		LIMIT 4";
	$sth = $psql->prepare($query);
	$sth->execute(array());
	$result = $sth->fetchAll();
	return $result;
}

function get_users_at_specific_time($time, $psql) {
	$query = "SELECT username
		FROM users, available_times
		WHERE start_time = ?
			AND available_times.uid=users.id";
	$sth = $psql->prepare($query);
	$sth->execute(array($time));
	$result = $sth->fetchAll();
	return $result;
}

function get_username() {
	return $_SESSION['username'];
}

function get_user_current_timezone($psql) {
	if (!isset($_SESSION['user_id'])) {
		return 0;
	}
	$query = "SELECT timezone
		FROM timezones,users
		WHERE userid = users.id AND users.id=?
		LIMIT 1";
	$sth = $psql->prepare($query);
	$sth->execute(array($_SESSION['user_id']));
	$result = $sth->fetchAll();
	//if the user hasn't chosen the timezone yet it sets it as GMT+0
	if (count($result) == 0) {
		return 0;
	}
	$result = $result[0]['timezone'];
	return $result;
}

function user_timezone_exist($psql) {
	$query = "SELECT timezone
		FROM timezones,users
		WHERE userid = users.id AND users.id=?
		LIMIT 1";
	$sth = $psql->prepare($query);
	$sth->execute(array($_SESSION['user_id']));
	$result = $sth->fetchAll();
	//if the user hasn't chosen the timezone yet it sets it as GMT+0
	if (count($result) == 0) {
		return false;
	}
	return true;
}


function save_timezone($tmz, $psql) {
	if (user_timezone_exist($psql)) {
		//update
		$query = "UPDATE timezones set
			timezone = ? where userid = ?";

	}
	else {
		$query = "INSERT INTO timezones
			(timezone, userid) values(?,?)";
	}

	$sth = $psql->prepare($query);
	$query_result = $sth->execute(array($tmz, $_SESSION['user_id']));
	return $query_result;
}

function login($keycode, $psql) {
	$query = "SELECT
		*
		FROM users
		WHERE usercode = ?
		LIMIT 1";

	$sth = $psql->prepare($query);
	$sth->execute(array($keycode));
	$result = $sth->fetchAll();
	if (count($result) != 1) {
		printd("No such user");
		return false;
	}
	$result = $result[0];
	//printd(var_dump($result));
	$id = $result["id"];
	$password_hash = $result["usercode"];
	$user_browser = $_SERVER['HTTP_USER_AGENT'];
	$ip_address = get_ip();

	$_SESSION['user_id'] =  $id;
	$_SESSION['username'] = $result['username'];
	$login_check = hash('sha512',
		$password_hash . $user_browser . $ip_address
	);
	$_SESSION['login_string'] = $login_check;
	printd($id);
	printd($keycode);
	printd($login_check);
	printd("login_string: $login_check");
	return true;
}



function login_check($psql) {
	/*
	 * one user agent and one ip per user
	 */

	if(isset($_SESSION['user_id'],
		$_SESSION['username'],
		$_SESSION['login_string'])){

		$user_id = $_SESSION['user_id'];
		$login_string = $_SESSION['login_string'];

		//disable the use of another user agent
		$user_browser = $_SERVER['HTTP_USER_AGENT'];
		$ip_address = get_ip();
		printd("ip address is: $ip_address");
		printd("user agent is: $user_browser");

		$query = "SELECT
			*
			FROM users
			WHERE id = ?
			LIMIT 1";
		$sth = $psql->prepare($query);
		$sth->execute(array($user_id));
		$result = $sth->fetchAll();
		if(count($result) != 1) {
			printd("no result from query");
			return -2;
		}
		else {
			$result = $result[0];
			//printd(var_dump($result));
			$password_hash = $result["usercode"];
			$login_check = hash('sha512',
				$password_hash . $user_browser . $ip_address);
			printd("login check string: $login_check");
			printd("current string: $login_string");
			if($login_check == $login_string) {
				printd("user is logged in");
				return 1;
			}
			else {
				printd("session has expired, the login string doesn't match");
				return -3;
			}
		}
	}
	else {
		printd("session not started yet");
		return -1;
	}
}


function logout() {
	$_SESSION = array();
	$params = session_get_cookie_params();
	setcookie(session_name(),
		'', time() - 42000,
		$params["path"],
		$params["domain"],
		$params["secure"],
		$params["httponly"]);
	session_destroy();
}



/*
function make_link_alive($psql, $id) {
	$query = "UPDATE links
		set alive = true where id= ?";
	$sth = $psql->prepare($query);
	$query_result = $sth->execute(array($id));

	if ($query_result === false) {
		printd("Couldn't kill link");
		return -1;
	}
	else {
		printd("Link is now flagged as dead");
		return 1;
	}
}
 */

function printd($msg) {
	if(DEBUG) {
		print "<b>$msg</b><br/>\n";
	}
}

function redirect($page) {
	header("Location: $page", TRUE, 301);
	exit();
}


function startsWith($haystack, $needle) {
	// search backwards starting from haystack length characters from the end
	return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}
function endsWith($haystack, $needle) {
	// search forward starting from end minus needle length characters
	return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

function check_on_ip() {
	return (get_ip() == NULL);
}


function get_ip() {
	$ip_address = @$_SERVER['REMOTE_ADDR'];
	if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
		$ip_address = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
	}
	return $ip_address;
}

