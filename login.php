<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $utenti = json_decode(file_get_contents("utenti.json"), true);

    foreach ($utenti as $utente) {
        if ($utente["username"] == $username && $utente["password"] == $password) {
            header("Location: dashboard.php?user=$username");
            exit;
        }
    }

    echo "Credenziali errate!!";

} else {

?>

<form method="post">
  <h2>Login</h2>
  Username: <input type="text" name="username" required><br>
  Password: <input type="password" name="password" required><br>
  <button type="submit">Accedi</button>
</form>

<?php

}

?>
