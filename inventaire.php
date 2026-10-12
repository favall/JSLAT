<!DOCTYPE html>
<html lang="en">
<head>
  <title>Vente d'occasion du Slat</title>
  <meta charset="utf-8"><meta name="robots" content="noindex, nofollow">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>  
    
<style>
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
}
body {
  background-image: url('background.png');
  background-attachment: fixed;
  background-size: 100% ;
}
h1 {
  text-align: center;
}
</style>  
  
</head>

<body>
<div class="container">
<img src="logo.jpg" class="img-thumbnail" alt="Responsive image">

<h2>Inventaire des invendus</h2>
<h3>Saisir l'identifiant de l'article</h3>

<?php

include('connection.php');
require_once 'inc/functions.php';

$NbArticles = 1 ;

 
print '
<form class="form-horizontal" name="form1" id="form1" method="post" action="inventaire2.php">
<input id="NbArticles" name="NbArticles" type="hidden" value="'.$NbArticles.'">';


// Début de la boucle
$x = 1;
while ($x <= $NbArticles) {

print '

	<div class="form-group">
	  <label class="control-label col-sm-2" for="Article'.$x.'"></label>
	  <div class="col-sm-10">
	    <input 
	      name="Article'.$x.'" 
	      type="text" 
	      class="form-control" 
	      id="Article'.$x.'" 
	      required 
	      pattern=".*euros\).*"
	      title="Impérativement saisir l article dans le menu déroulant">
	  </div>
	</div>

<script>
$(function() {
    $( "#Article'.$x.'" ).autocomplete({
        source: "search.php"
    });
});
</script>';



// fin de la boucle  
$x++;
}
  
print '

  <div class="form-group">
      <div class="col-sm-offset-2 col-sm-9">
        <button type="submit" class="btn btn-primary">Valider</button>
      </div>
  </div>

</form>

</div>
</div>';

?>



</body>
</html>
