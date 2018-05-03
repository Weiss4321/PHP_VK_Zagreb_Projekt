<?php 
session_start();
session_destroy();
unset($_SESSION['userid']);

//Remove Cookies
setcookie("identifier","",time()-(3600*24*365)); 
setcookie("securitytoken","",time()-(3600*24*365)); 

require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

include("templates/header.inc.php");
?>

<div class="container main-container">
Odjava je bila uspješna. <a href="login.php">Natrag na Prijavu</a>.
</div>
<?php 
include("templates/footer.inc.php")
?>