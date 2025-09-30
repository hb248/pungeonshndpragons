<?php


require_once('config.inc.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$Player = new Player();
$playerID = $_SESSION['playerID'];

if (isset($_POST['createGame'])) {
    $createGameReturn = $Player->createGame($playerID);

    $redirectUrl = [
        1 => "hub.php?error=13", // Spieler hat bereits zu viele Spiele gehosted
        2 => "hub.php?success=4" // Spiel erfolgreich erstellt
    ];

    header("Location: $redirectUrl[$createGameReturn]");
    exit();
}

if (isset($_POST['joinGame']) && isset($_POST['gameID'])) {
    $gameID = $_POST['gameID'];
    $joinGameReturn = $Player->joinGame($gameID, $playerID);

    $redirectUrl = [
        1 => "hub.php?error=11", // Spiel bereits voll
        2 => "hub.php?error=12", // Spieler kann nicht gegen sich selbst spielen
        3 => "dyn.php?gameID=$gameID&success=6" // Spiel erfolgreich gejoint
    ];

    header("Location: $redirectUrl[$joinGameReturn]");
    exit();
}

if (isset($_POST['resignGame']) && isset($_POST['gameID'])) {
    $gameID = $_POST['gameID'];
    $resignGameReturn = $Player->resignGame($gameID, $playerID);

    $redirectUrl = [
        1 => "hub.php?error=14", // Spiel wurde schon Abgeschlossen/Aufgegeben, oder wartet auf Gegner
        2 => "hub.php?success=5", // Spiel erfolgreich aufgegeben
        3 => "hub.php?error=16" // Spieler gehört nicht zum Spiel
    ];

    header("Location: $redirectUrl[$resignGameReturn]");
    exit();
}

if (isset($_POST['contGame']) && isset($_POST['gameID'])) {
    $gameID = $_POST['gameID'];
    $contGameReturn = $Player->contGame($gameID);

    $redirectUrl = [
        1 => "hub.php?error=15", // Spiel wurde schon Abgeschlossen/Aufgegeben, oder wartet auf Gegner (in dem fall beitreten button benutzen)
        2 => "dyn.php?gameID=$gameID&success=7" // Spiel kann fortgesetzt werden
    ];

    header("Location: $redirectUrl[$contGameReturn]");
    exit();
}

if (isset($_POST['backToHub'])) {
    header("Location: hub.php?success=8");
    exit();
}
