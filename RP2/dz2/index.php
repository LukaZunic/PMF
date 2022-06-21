<?php

require_once 'db.php';
require_once 'loginForm.php';

session_start();


function processLogin(){

  if(!isset($_POST["username"]) || preg_match('/[a-zA-Z]{1, 20}/', $_POST["username"])){
		drawLoginForm();
  }

  if(!isset($_POST["password"])){
		drawLoginForm();
  }

  $db = DB::getConnection();

  try{

		$st = $db->prepare('SELECT password_hash FROM dz2_users WHERE username=:username');
		$st->execute(array('username' => $_POST["username"]));

	} catch (PDOException $e){
    drawLoginForm('Greška:' . $e->getMessage());
    return;
  }

  $row = $st->fetch();

  if($row === false){
    drawLoginForm('Korisnik ne postoji!');
    return;
  }

  if(!password_verify($_POST["password"], $row["password_hash"])){
    drawLoginForm('Pogrešna lozinka!');
    return;
  }

  $_SESSION["username"] = $_POST["username"];

  header('Location: homepage.php');
  exit;

}


function processSignup(){
	if(!isset($_POST["username"]) || preg_match('/[a-zA-Z]{1, 20}/', $_POST["username"]))
		drawLoginForm();

	if(!isset($_POST["password"]))
		drawLoginForm();

	$db = DB::getConnection();

	try{
		$st = $db->prepare('SELECT password_hash FROM dz2_users WHERE username=:username');
		$st->execute(array('username' => $_POST["username"]));
	}	
	catch(PDOException $e){ drawLoginForm('Greška:' . $e->getMessage()); return; }

	if($st->rowCount() > 0){
		drawLoginForm('Taj korisnik već postoji.');
		return;
	}
	else{
		try{

      $registration_sequence = 'abc';
      $has_registered = 1;

			$st = $db->prepare('INSERT INTO dz2_users (username, password_hash, email, registration_sequence, has_registered) VALUES (:username, :hash, :email, :registration_sequence, :has_registered)');
			$hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
			$st->execute(array('username' => $_POST["username"], 'hash' => $hash, 'email' => $_POST["email"], 'registration_sequence' => $registration_sequence, 'has_registered' => $has_registered));
		}
		catch(PDOException $e){ drawLoginForm('Greška:' . $e->getMessage()); return; }

		drawLoginForm('Novi korisnik je uspješno dodan!');
	}
}


if(isset($_POST["gumb"]) && $_POST["gumb"] === "login")
	processLogin();
else if(isset($_POST["gumb"]) && $_POST["gumb"] === "novi")
  drawSignupForm();
else if(isset($_POST["gumb"]) && $_POST["gumb"] === "signup")
  processSignup();
else
	drawLoginForm();

?>