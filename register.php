<?php
require_once '../config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username']);
    $pw1  = $_POST['password'];
    $pw2  = $_POST['confirm_password'];

    if ($user === '' || $pw1 === '' || $pw2 === '') {
        $error = "Compilare tutti i campi.";
    } elseif ($pw1 !== $pw2) {
        $error = "Le password non combaciano.";
    } elseif (username_esiste($user)) {
        $error = "Username già registrato.";
    } else {
        aggiungi_utente($user, $pw1);
        $success = "Registrazione completata! Ora puoi accedere.";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
</head>
<body>

<h1>Crea un nuovo account</h1>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Username:<br>
        <input type="text" name="username">
    </label><br>

    <label>Password:<br>
        <input type="password" name="password">
    </label><br>

    <label>Conferma Password:<br>
        <input type="password" name="confirm_password">
    </label><br>

    <button type="submit">Registrati</button>
</form>

<p><a href="login.php">Vai al login</a></p>
<p><a href="../index.html">Home</a></p>

</body>
</html>
