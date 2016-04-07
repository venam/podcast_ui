<?php

include_once('header.php');

if (isset($_POST['schedule'])) {
	$dbConnection = get_db_handle();
	$users_available_times = $_POST['schedule'];
	set_user_availabe_time($users_available_times, $dbConnection);
}
redirect('index.php');
