<?php 
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

include("templates/header.inc.php")
?>
<div class="container main-container registration-form">
<h1>Registracija </h1>
<?php
$showFormular = true; //Variable ob das Registrierungsformular anezeigt werden soll
 
if(isset($_GET['register'])) {
	$error = false;
	$vorname = trim($_POST['vorname']);
	$nachname = trim($_POST['nachname']);
	$email = trim($_POST['email']);
	$passwort = $_POST['passwort'];
	$passwort2 = $_POST['passwort2'];
	
	if(empty($vorname) || empty($nachname) || empty($email)) {
		echo 'Molim ispuniti sva polja<br>';
		$error = true;
	}
  
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo 'Molim upisati važeću mail adresu<br>';
		$error = true;
	} 	
	if(strlen($passwort) == 0) {
		echo 'Molim upisati zaporku<br>';
		$error = true;
	}
	if($passwort != $passwort2) {
		echo 'Upisi zaporke nisu identični<br>';
		$error = true;
	}
	
	//Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
	if(!$error) { 
		$statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
		$result = $statement->execute(array('email' => $email));
		$user = $statement->fetch();
		
		if($user !== false) {
			echo 'Ta mail adresa je već zauzeta<br>';
			$error = true;
		}	
	}
	
	//Keine Fehler, wir können den Nutzer registrieren
	if(!$error) {	
		$passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);
		
		$statement = $pdo->prepare("INSERT INTO users (email, passwort, vorname, nachname) VALUES (:email, :passwort, :vorname, :nachname)");
		$result = $statement->execute(array('email' => $email, 'passwort' => $passwort_hash, 'vorname' => $vorname, 'nachname' => $nachname));
		
		if($result) {		
			echo 'Uspješno je registriran novi korisnik <a href="login.php">Izvrši prijavu</a>';
			$showFormular = false;
		} else {
			echo 'Nastala je greška kod spremanja<br>';
		}
	} 
}
 
if($showFormular) {
?>

<form action="?register=1" method="post">

<div class="form-group">
<label for="inputVorname">Ime:</label>
<input type="text" id="inputVorname" size="40" maxlength="250" name="vorname" class="form-control" required>
</div>

<div class="form-group">
<label for="inputNachname">Prezime:</label>
<input type="text" id="inputNachname" size="40" maxlength="250" name="nachname" class="form-control" required>
</div>

<div class="form-group">
<label for="inputEmail">E-Mail:</label>
<input type="email" id="inputEmail" size="40" maxlength="250" name="email" class="form-control" required>
</div>

<div class="form-group">
<label for="inputPasswort">Zaporka:</label>
<input type="password" id="inputPasswort" size="40"  maxlength="250" name="passwort" class="form-control" required>
</div> 

<div class="form-group">
<label for="inputPasswort2">Ponovljena zaporka:</label>
<input type="password" id="inputPasswort2" size="40" maxlength="250" name="passwort2" class="form-control" required>
</div> 
<button type="submit" class="btn btn-lg btn-primary btn-block">Registriraj se sada</button>
</form>
 
<?php
} //Ende von if($showFormular)
	

?>
</div>
<?php 
include("templates/footer.inc.php")
?>