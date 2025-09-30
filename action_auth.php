<?php


require_once('config.inc.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$Auth = new Auth();
$Player = new Player();

if (isset($_POST['Login'])) {
    $benutzername = trim($_POST['benutzername']); // trim entfernt Leerzeichen
    $passwort = trim($_POST['passwort']);

    $loginReturn = $Auth->login($benutzername, $passwort);

    $redirectUrl = [
        1 => "index.php?error=1", // Unerlaubte Zeichen
        2 => "index.php?error=2", // Ungültige Zeichenanzahl
        3 => "index.php?error=3", // Benutzername falsch
        4 => "hub.php?success=1", // Login erfolgreich
        5 => "index.php?error=4"  // Passwort falsch
    ];

    if ($loginReturn == 4) { // Login erfolgreich
        $_SESSION['name'] = $benutzername;
        $_SESSION['auth'] = true;
        // Spieler-ID auch in Session speichern
        $playerID = $Player->getPlayerID($benutzername);
        $_SESSION['playerID'] = $playerID;

        if (!isset($_SESSION['playerID'])) {
            die("Fehler: `playerID` nicht in der Session!");
        }
    } else {
        $_SESSION['name'] = "";
        $_SESSION['auth'] = false;
        $_SESSION['playerID'] = "";
    }

    header("Location: $redirectUrl[$loginReturn]");
    exit();
}

if (isset($_POST['Registrieren'])) {
    $benutzername = trim($_POST['benutzername']);
    $passwort = trim($_POST['passwort']);
    $passwortw = trim($_POST['passwortw']);

    $regReturn = $Auth->addUser($benutzername, $passwort, $passwortw);

    $redirectUrl = [
        1 => "index.php?error=5", // Unerlaubte Zeichen
        2 => "index.php?error=6", // Ungültige Zeichenanzahl
        3 => "index.php?error=7", // Benutzername schon vergeben
        4 => "index.php?error=8", // Passwörter stimmen nicht überein
        5 => "index.php?error=9", // Passwort zu kurz/lang
        6 => "hub.php?success=2"  // Registrierung erfolgreich
    ];

    if ($regReturn == 6) { // Registrierung erfolgreich
        $_SESSION['name'] = $benutzername;
        $_SESSION['auth'] = true;
        // Spieler-ID auch in Session speichern
        $playerID = $Player->getPlayerID($benutzername);
        $_SESSION['playerID'] = $playerID;
    } else {
        $_SESSION['name'] = "";
        $_SESSION['auth'] = false;
        $_SESSION['playerID'] = "";
    }

    header("Location: $redirectUrl[$regReturn]");
    exit();
}

if (isset($_POST['Logout'])) {
    session_destroy();
    header('Location: index.php?success=3');
    exit();
}