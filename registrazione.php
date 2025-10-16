<?php

if($_SERVER["REQUEST_METOD"] == "POST"){

    $username = $_POST["$username"];
    $password = $_POST["$password"];

    $utenti = json_decode(file_get_contents("utenti.json"), true);

    foreach($utenti as $utente){
        if($utente["username"] == $username){
            echo "Utente gia registrato!!";
        }
        exit;
    }

    $utenti[] = ["username" => $username, "password" => $password];

    file_put_contents("utenti.json", json_encode($utenti));

    echo "Registrazione completata!";

}else{

?>

<form method="post">
  <h2>Registrazione</h2>
  Username: <input type="text" name="username" required><br>
  Password: <input type="password" name="password" required><br>
  <button type="submit">Registrati</button>
</form>

<?php

}

?>