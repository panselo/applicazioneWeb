<?php

$user = $_GET["user"] ?? "ospite";  //l'operatore ?? ci dice "se la variabile a sinistra esiste, usa quella; altrimenti usa il valore a destra"
echo "Benvenuto, $user!";

?>

<h3>Dashboard</h3>

<div>
  <a href="visualizza.php">Visualizza dati</a><br><br>
  <a href="inserisci.php">Inserisci nuovo dato</a><br><br>
  <a href="modifica.php">Modifica dato</a><br><br>
  <a href="elimina.php">Elimina dato</a><br><br>
</div>