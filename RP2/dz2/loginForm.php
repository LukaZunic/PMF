<?php


function drawLoginForm($msg = '') {
    ?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf8" />
		<title>Login</title>
	</head>
	<body>
		<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
			Korisničko ime: 
			<input type="text" name="username" />
			<br />
			Password:
			<input type="password" name="password" />
			<br />
			<button type="submit" name="gumb" value="login">Ulogiraj se!</button>
			<button type="submit" name="gumb" value="novi">Prijavi novog korisnika!</button>
		</form>

		<?php 
			if($msg !== '')
				echo '<p>' . $msg . '</p>';
		?>
	</body>
	</html>
	<?php
}



function drawSignupForm() {
    ?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf8" />
		<title>Login</title>
	</head>
	<body>
		<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
			Korisničko ime: 
			<input type="text" name="username" />
			<br />
			Password:
			<input type="password" name="password" />
			Email:
			<input type="email" name="email" />
			<br />
			<button type="submit" name="gumb" value="signup">Stvori novog korisnika!</button>
		</form>
	
	</body>
	</html>
	<?php
}


?>