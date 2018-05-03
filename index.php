<?php 
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");
include("templates/header.inc.php")
?>

  

    

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Moj grad, moj klub</h1>
        <p>Dobro došli na probnu stranicu članova Veslačkog kluba Zagreb. Detaljnije o klubu možete saznati na <a href="http://www.vkzagreb.hr/" target="_blank">vkzagreb.hr </a>.
        
        Stranica je dizajnirana pomoću programa <a href="http://getbootstrap.com" target="_blank">Bootstrap v3.3.6</a> .<br><br>
        
        Za izradu dinamičkih svojstava stranice korištena je modificirana PHP skripta prema predlošku sa <a href="http://www.php-einfach.de" target="_blank">www.php-einfach.de</a> , a za bazu podataka koristi se Mysql.
        
        </p>
        <p><a class="btn btn-primary btn-lg" href="register.php" role="button">Registracija novih članova</a></p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Značajke</h2>
          <ul>
          	<li>Registracija novih članova</li> 
          	<li>Prijava registriranih članova</li>
          	<li>Promjena lozinke preko maila</li>
          	<li>Pregled uplata članarine</li>
          	<li>Novosti iz rada kluba</li>
          </ul>
         
        </div>
        <div class="col-md-4">
          <h2>Obavijesti</h2>
		  <ul>
          	<li>Informacije o aktivnostima</li> 
          	<li>Sastanci povjerenstava</li>
          	<li>Termini natjecanja</li>
          	<li>Rezervacija teretane</li>
          	<li>Rezervacija stolnog tenisa</li>
          </ul>
           
          <p><a class="btn btn-default" href="http://www.vkzagreb.hr/" target="_blank" role="button">Detaljnije &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Volim veslanje</h2>
          <p>Alumni klub veslačkog kluba Zagreb organizira sveke prve subote u mjesecu druženje u pivnici Medvedgrad.  </p>
          <p><a class="btn btn-default" href="http://www.pivovara-medvedgrad.hr/ilica/" target="_blank" role="button">Detaljnije &raquo;</a></p>
        </div>
      </div>
	</div> <!-- /container -->
      

  
<?php 
include("templates/footer.inc.php")
?>
