<?php 
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

include("templates/header.inc.php");
?>
 <div class="container small-container-330">
	<h2 >Passwort vergessen</h2>


<?php 
$showForm = true;
 
if(isset($_GET['send']) ) {
	if(!isset($_POST['email']) || empty($_POST['email'])) {
		$error = "<b>Bitte eine E-Mail-Adresse eintragen</b>";
	} else {
		$statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
		$result = $statement->execute(array('email' => $_POST['email']));
		$user = $statement->fetch();		
 
		if($user === false) {
			$error = "<b>Kein Benutzer gefunden</b>";
		} else {
			
			$passwortcode = random_string();
			$statement = $pdo->prepare("UPDATE users SET passwortcode = :passwortcode, passwortcode_time = NOW() WHERE id = :userid");
			$result = $statement->execute(array('passwortcode' => sha1($passwortcode), 'userid' => $user['id']));
			
			$empfaenger = $user['email'];
			$betreff = "Nova zaporka za prijavu na www.php-einfach.de"; //Ersetzt hier den Domain-Namen
			$from = "From: Vorname Nachname <absender@domain.de>"; //Ersetzt hier euren Name und E-Mail-Adresse
			$url_passwortcode = getSiteURL().'passwortzuruecksetzen.php?userid='.$user['id'].'&code='.$passwortcode; //Setzt hier eure richtige Domain ein
			$text = 'Bok '.$user['vorname'].',
za tvoju prijavu na Moj klub zatražena je nova lozinka. Za izdavanje nove lozinke prijavi se unutar 24 sata na
'.$url_passwortcode.'
 
Ako si se u međuvremenu sjetio zaporke ili je nisi niti zatražio, molim ignoriraj ovaj mail.
 
Lijepi pozdrav,
Lovro';
			
			//echo $text;
			 
			mail($empfaenger, $betreff, $text, $from);
 
			echo "Ein Link um dein Passwort zurückzusetzen wurde an deine E-Mail-Adresse gesendet.";	
			$showForm = false;
		}
	}
}
 
if($showForm):
?> 
	Gib hier deine E-Mail-Adresse ein, um ein neues Passwort anzufordern.<br><br>
	 
	<?php
	if(isset($error) && !empty($error)) {
		echo $error;
	}
	
	?>
	<form action="?send=1" method="post">
		<label for="inputEmail">E-Mail</label>
		<input class="form-control" placeholder="E-Mail" name="email" type="email" value="<?php echo isset($_POST['email']) ? htmlentities($_POST['email']) : ''; ?>" required>
		<br>
		<input  class="btn btn-lg btn-primary btn-block" type="submit" value="Neues Passwort">
	</form> 
<?php
endif; //Endif von if($showForm)
?>

</div> <!-- /container -->
 

<?php 
include("templates/footer.inc.php")
?>