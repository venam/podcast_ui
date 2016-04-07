<?php

include_once('header.php');

$dbConnection = get_db_handle();
if (isset($_POST['timezone'])) {
	$tmz = intval($_POST['timezone']);
	save_timezone($tmz, $dbConnection);
}
redirect('index.php');

