<?php
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

$user = check_user();
$broj = $user["id"];
include("templates/header.inc.php");   //ovo ispisuje zaglavlje

// tu dodati php kod logiku
?>
<?php
 
$perpage = 4;
if(isset($_GET['page']) & !empty($_GET['page'])){
	$curpage = $_GET['page'];
}else{
	$curpage = 1;
}
$start = ($curpage * $perpage) - $perpage;

// slijedi trik za izračun broja zapisa u tablici pomoću PDO
$result = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS id  FROM users "); 
$result->execute();
$result = $pdo->prepare("SELECT FOUND_ROWS()"); 
$result->execute();
$totalres =$result->fetchColumn();
//echo "Ukupno PDO". $totalres;


$endpage = ceil($totalres/$perpage);
$startpage = 1;
$nextpage = $curpage + 1;
$previouspage = $curpage - 1;

$statement= $pdo->prepare("SELECT * FROM `users` LIMIT $start, $perpage"); 
$result = $statement->execute();

?>

<?php
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
<h2>Članarina</h2>

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
    	<form action="?save=personal_data" method="post" class="form-inline">
		<?php
		if ($user['vorname'] == 'Lovro') {   // samo admin Lovro može upisivati članarinu
			?>	
    		<div class="form-group">
    			<label for="inputFee_number" class="col-sm-2 control-label">Broj uplate</label>
    			<div class="col-sm-4">
    				<input class="form-control" id="inputFee_number" name="fee_number" type="text" value="" required>
    			</div>
    		</div>
    		
    		<div class="form-group">
    			<label for="inputAmount" class="col-sm-2 control-label">Iznos</label>
    			<div class="col-sm-4">
    				<input class="form-control" id="inputAmount" name="amount" type="text" value="" required>
    			</div>
    		</div>
			
			<div class="form-group">
    			<label for="inputUseId" class="col-sm-3 control-label">Id Korisnika</label>
    			<div class="col-sm-2">
    				<input class="form-control" id="inputUseId" name="use_id" type="text" value="" required>
    			</div>
    		</div>
			
    		
    		<div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-primary">Spremiti</button>
			    </div>
			</div> 
			<?php }
			else {        echo ("Poštovani ". $user['vorname']. " nemate admistracijska prava za upis članarine");
			}?>
			
		
    	</form>
		
		<div class="container">
	<div class="row">
	<h3>Tablica korisnika</h3>
		<table class="table "> 
		<thead> 
			<tr> 
				<th>#</th> 
				<th>Korisnik</th> 
				<th>E-mail</th> 
			</tr> 
		</thead> 
		<tbody> 
		<?php 
		
		while($r = $statement->fetch()) {
		?>
			<tr> 
				<th scope="row"><?php echo $r['id']; ?></th> 
				<td><?php echo $r['vorname']."  ".$r['nachname'] ; ?></td> 
				<td><?php echo $r['email']; ?></td> 
				<td>
					<!--<a href="update.php?id=<?php echo $r['id']; ?>"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> -->
				</td>
			</tr> 
		<?php } ?>
		</tbody> 
		</table>
	</div>

	<nav aria-label="Page navigation">
  <ul class="pagination">
  <?php if($curpage != $startpage){ ?>
    <li class="page-item">
      <a class="page-link" href="?page=<?php echo $startpage ?>" tabindex="-1" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">First</span>
      </a>
    </li>
    <?php } ?>
    <?php if($curpage >= 2){ ?>
    <li class="page-item"><a class="page-link" href="?page=<?php echo $previouspage ?>"><?php echo $previouspage ?></a></li>
    <?php } ?>
    <li class="page-item active"><a class="page-link" href="?page=<?php echo $curpage ?>"><?php echo $curpage ?></a></li>
    <?php if($curpage != $endpage){ ?>
    <li class="page-item"><a class="page-link" href="?page=<?php echo $nextpage ?>"><?php echo $nextpage ?></a></li>
    <li class="page-item">
      <a class="page-link" href="?page=<?php echo $endpage ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Last</span>
      </a>
    </li>
    <?php } ?>
  </ul>
</nav>
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
//$statement = $pdo->prepare("SELECT * FROM membership_fee WHERE $user['vorname'] == id");
$statement = $pdo->prepare("SELECT membership_fee.* FROM membership_fee  WHERE use_id = $broj");

$result = $statement->execute();


$count = 1;
while($row = $statement->fetch()) {
	echo "<tr>";
	echo "<td>".$count++."</td>";
	echo "<td>".$row['fee_number']."</td>";
	echo "<td>".$row['amount']."</td>";
	echo "<td>".$row['use_id']."</td>";
	//echo "<td>".$row['vorname']."</td>";
	//echo "<td>".$row['nachname']."</td>";
	echo "</tr>";
}
?>
		</table>
	</div>
</div>   <!-- tab-content  -->




<?php 
include("templates/footer.inc.php")
?>