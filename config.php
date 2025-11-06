<?php

// Percorsi file JSON
define('USERS_FILE', __DIR__ . '/data/users.json');
define('PERSONE_FILE', __DIR__ . '/data/persone.json');

// Avvia sessione se non è già attiva
function inizializza_sessione() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Protegge le pagine riservate
function verifica_autenticazione() {
    inizializza_sessione();
    if (!isset($_SESSION['username'])) {
        header('Location: ' . get_base_path() . 'login/login.php');
        exit;
    }
}

// Calcola automaticamente quanti ../ servono
function get_base_path() {
    $attuale = dirname($_SERVER['PHP_SELF']);
    $livelli = substr_count($attuale, '/') - 1;
    return str_repeat('../', max(0, $livelli));
}

// Lettura JSON
function leggi_json($file_path) {
    if (!file_exists($file_path)) {
        return [];
    }

    $contenuto = file_get_contents($file_path);
    $dati = json_decode($contenuto, true);

    return $dati ?? [];
}

// Scrittura JSON
function scrivi_json($file_path, $data) {
    $cartella = dirname($file_path);

    if (!is_dir($cartella)) {
        mkdir($cartella, 0777, true);
    }

    file_put_contents($file_path, json_encode($data, JSON_PRETTY_PRINT));
}

// Controllo codice fiscale (solo formato)
function valida_codice_fiscale($codice_fiscale) {
    $regex = '/^[A-Z]{6}[0-9]{2}[A-Z][0-9]{2}[A-Z][0-9]{3}[A-Z]$/';
    return preg_match($regex, $codice_fiscale);
}

// Controllo CF duplicato
function codice_fiscale_esiste($cf) {
    $persone = leggi_json(PERSONE_FILE);

    foreach ($persone as $p) {
        if ($p['codice_fiscale'] === $cf) {
            return true;
        }
    }

    return false;
}

// Controllo username duplicato
function username_esiste($username) {
    $users = leggi_json(USERS_FILE);

    foreach ($users as $u) {
        if ($u['username'] === $username) {
            return true;
        }
    }

    return false;
}

// Verifica credenziali utente
function verifica_credenziali($username, $password) {
    $users = leggi_json(USERS_FILE);

    foreach ($users as $u) {
        if ($u['username'] === $username && password_verify($password, $u['password'])) {
            return true;
        }
    }

    return false;
}

// Registrazione utente
function aggiungi_utente($username, $password) {
    $users = leggi_json(USERS_FILE);

    $users[] = [
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ];

    scrivi_json(USERS_FILE, $users);
    return true;
}

// Aggiunge persona
function aggiungi_persona($cf, $nome, $cognome, $data_nascita) {
    $persone = leggi_json(PERSONE_FILE);

    $persone[] = [
        'codice_fiscale' => strtoupper($cf),
        'nome' => $nome,
        'cognome' => $cognome,
        'data_nascita' => $data_nascita
    ];

    scrivi_json(PERSONE_FILE, $persone);
}

// Elimina persona tramite CF
function elimina_persona($cf) {
    $persone = leggi_json(PERSONE_FILE);

    $nuova_lista = array_filter($persone, function ($p) use ($cf) {
        return $p['codice_fiscale'] !== $cf;
    });

    scrivi_json(PERSONE_FILE, array_values($nuova_lista));
}

// Filtri di ricerca
function ottieni_persone($cognome = '', $data_dopo = '') {
    $persone = leggi_json(PERSONE_FILE);

    return array_filter($persone, function ($p) use ($cognome, $data_dopo) {

        $ok = true;

        if ($cognome !== '') {
            $ok = $ok && stripos($p['cognome'], $cognome) !== false;
        }

        if ($data_dopo !== '') {
            $ok = $ok && strtotime($p['data_nascita']) > strtotime($data_dopo);
        }

        return $ok;
    });
}

// Formatta date
function formatta_data($data) {
    return date('d/m/Y', strtotime($data));
}
