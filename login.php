<!doctype html>
<html>

<?php
include_once("header.php");

$dbConnection = get_db_handle();
if (isset($_POST['keycode']) && login($_POST['keycode'], $dbConnection)) {
	redirect('index.php');
}



include("head.php"); ?>

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
				<a href="login.php">Login</a> 
			</div>
		</div>
	</div>
</div>

<div id="container">
	<div id="content">

	<br/>
	<?php include("this_week_part.php"); ?>


	<form action="login.php" method="post">
		<table border="0" cellspacing="0" cellpadding="6" class="tborder">
			<tr>
			<td class="thead" colspan="2"><strong>Login Using Keycode</strong></td>
			</tr>
			<tr>
			<td class="trow1"><strong>Keycode:</strong><br />
			<span class="smalltext">To receive your own keycode please contact an admin on IRC.</span></td>
			<td class="trow1"><input type="text" class="textbox" name="keycode" size="25" style="width: 200px;" value="" /></td>
			</tr>
		</table>
		<br />
		<div align="center"><input type="submit" class="button" name="submit" value="Login" /></div>
	</form>

	</div>

</div>


</body>
</html>
