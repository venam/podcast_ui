<!doctype html>
<html>

<?php
include("header.php");
include("head.php");

$dbConnection = get_db_handle();
?>

<body>
<!--
      _)                                  |   
 __ \  |\ \  /  _ \  __| __|   __ \   _ \ __| 
 |   | | `  <   __/ |  \__ \   |   |  __/ |   
_|  _|_| _/\_\\___|_|  ____/_)_|  _|\___|\__| 
-->

<div id="header">
	<div id="infobar">
		<div id="navcontainer">
			<div id="nixerlogo">
			<a href="http://nixers.net/">nixers</a><span style="color:#638B87; font-size: 13px;">' podcast</span>
			</div>
			<div id="navlinks">
				<span style="color:#F26711;"><?=get_username();?></span>
				<span> | </span>
				<a href="logout.php">Logout</a> 
			</div>
		</div>
	</div>
</div>

<div id="container">
	<div id="content">

	<br/>
	<?php include("this_week_part.php"); ?>
	<?php include("rules_part.php"); ?>
	<?php include("time_selection_part.php"); ?>

	</div>
</div>

</body>
</html>
