<?php


require_once('config.inc.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$Player = new Player();
$playerID = $_SESSION['playerID'];
if (!isset($_SESSION['playerID'])) {
    die(json_encode(["error" => "Da hat die FH einfach meine Session gelöscht :) thanks, i hate it"]));
}



// Neue Nachricht speichern
if (isset($_POST['sendMessage'])) {
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8'); // XSS-Schutz
    //$message = strip_tags($message); // würde dann noch die Tags entfernen, damit sie im chat gar nicht erst angezeigt werden
    $gameID = $_POST['gameID'];

    // Nachricht speichern
    $Player->sendMessage($gameID, $playerID, $message);

    //TODO: return auslesen und mit classes\MessageHandler ausgeben.

    exit();
}

// Chatverlauf abrufen
if (isset($_POST['getMessages'])) {
    $gameID = $_POST['gameID'];
    echo json_encode($Player->getMessages($gameID)); //mit json_encode() wird das Array in ein JSON-Objekt umgewandelt damit JS das versteht
    exit();
}

//TODO: Eine Klasse für chat+log bauen
