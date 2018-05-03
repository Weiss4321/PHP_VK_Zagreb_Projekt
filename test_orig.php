<?php
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

$user = check_user();
include("templates/header.inc.php");   //ovo ispisuje zaglavlje

// tu dodati php kod logiku
if(isset($_GET['save'])) {
	$save = $_GET['save'];
	
	if($save == 'personal_data') {
		$fee_number = trim($_POST['fee_number']);
		$amount = trim($_POST['amount']);
		$use_id = trim($_POST['use_id']);
		
		if($fee_number == "" || $amount == "") {
			$error_msg = "Molim ispuniti fee_number i amount.";
		} else {
			//$statement = $pdo->prepare("UPDATE users SET vorname = :vorname, nachname = :nachname, updated_at=NOW() WHERE id = :userid");
			//$result = $statement->execute(array('vorname' => $vorname, 'nachname'=> $nachname, 'userid' => $user['id'] ));
			
			// inserte dodati
			//$insert = $pdo->prepare("INSERT INTO securitytokens (user_id, identifier, securitytoken) VALUES (:user_id, :identifier, :securitytoken)");
			//$insert->execute(array('user_id' => $user['id'], 'identifier' => $identifier, 'securitytoken' => sha1($securitytoken)));
			//INSERT INTO `membership_fee` (`id`, `fee_number`, `amount`, `use_id`, `user_lock`) VALUES ('4', '1234', '500', '3', CURRENT_TIMESTAMP)
			$insert = $pdo->prepare("INSERT INTO membership_fee ( fee_number, amount, use_id,  user_lock) VALUES (:fee_number, :amount, :use_id, CURRENT_TIMESTAMP)");
			$insert->execute(array('fee_number' => $fee_number, 'amount' => $amount, 'use_id' => $use_id));
			
			$success_msg = "Podaci su uspješno spremljeni.";
		}
	} 
}


?>

<div class="container main-container">
<h1>Članarina</h1>



<div>

<!-- Nav tabs messages-->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#data" aria-controls="home" role="tab" data-toggle="tab">Unos podataka</a></li>
    <li role="presentation"><a href="#pregled" aria-controls="profile" role="tab" data-toggle="tab">Pregled uplata</a></li>
    <li role="presentation"><a href="#report" aria-controls="profile" role="tab" data-toggle="tab">Izvještaj</a></li>
  </ul>

  <!-- Unos podataka-->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="data">
    	<br>
    	<form action="?save=personal_data" method="post" class="form-horizontal">
		
    		<div class="form-group">
    			<label for="inputFee_number" class="col-sm-2 control-label">Broj uplate</label>
    			<div class="col-sm-10">
    				<input class="form-control" id="inputFee_number" name="fee_number" type="text" value="" required>
    			</div>
    		</div>
    		
    		<div class="form-group">
    			<label for="inputAmount" class="col-sm-2 control-label">Iznos</label>
    			<div class="col-sm-10">
    				<input class="form-control" id="inputAmount" name="amount" type="text" value="" required>
    			</div>
    		</div>
			
			<div class="form-group">
    			<label for="inputUseId" class="col-sm-2 control-label">Id Korisnika</label>
    			<div class="col-sm-10">
    				<input class="form-control" id="inputUseId" name="use_id" type="text" value="" required>
    			</div>
    		</div>
    		
    		<div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-primary">Spremiti</button>
			    </div>
			</div>
    	</form>
		<div class="panel panel-default">
 
<table class="table">
<tr>
	<th>#</th>
	<th>Korisnik</th>
	<th>Id</th>
</tr>
<?php 
$statement = $pdo->prepare("SELECT * FROM users ORDER BY id");
$result = $statement->execute();
$count = 1;
while($row = $statement->fetch()) {
	echo "<tr>";
	echo "<td>".$count++."</td>";
	echo "<td>".$row['vorname']." ".$row['nachname']." </td>";
	
	echo "<td>".$row['id']."</td>";
	echo "</tr>";
}
?>
</table>
</div>
    </div>
	<!-- Pregled uplata -->
    <div role="tabpanel" class="tab-pane" id="pregled">
    	<br>
    	
		<table class="table">
			<tr>
			<th>#</th>
			<th>Broj uplate</th>
			<th>Iznos</th>
			<th>Id korisnika</th>
			<th>Ime</th>
			<th>Prezime</th
			</tr>
<?php 
//$statement = $pdo->prepare("SELECT * FROM membership_fee ORDER BY id");
$statement = $pdo->prepare("SELECT membership_fee.*, users.vorname, users.nachname FROM membership_fee  LEFT JOIN users ON membership_fee.use_id = users.id ORDER BY id");


$result = $statement->execute();
$count = 1;
while($row = $statement->fetch()) {
	echo "<tr>";
	echo "<td>".$count++."</td>";
	echo "<td>".$row['fee_number']."</td>";
	echo "<td>".$row['amount']."</td>";
	echo "<td>".$row['use_id']."</td>";
	echo "<td>".$row['vorname']."</td>";
	echo "<td>".$row['nachname']."</td>";
	echo "</tr>";
}
?>
		</table>
	</div>	
	<!-- Izvještaj -->
    <div role="tabpanel" class="tab-pane" id="report">
    	<br>
	<h1>U izradi .....</h1>	
	</div>
</div>   <!-- tab-content  -->




<?php 
include("templates/footer.inc.php")
?>