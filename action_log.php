<?php


require_once('config.inc.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$Player = new Player();
$playerID = $_SESSION['playerID'];


// Neue Nachricht speichern
/*if (isset($_POST['sendLog'])) {
    $message = $_POST['message']; // ohne XSS-Schutz
    //$message = strip_tags($message); // würde dann noch die Tags entfernen, damit sie im chat gar nicht erst angezeigt werden
    $gameID = $_POST['gameID'];

    // Nachricht speichern
    $classes\Player->sendMessage($gameID, $playerID, $message);

    exit();
}*/
// Neue Log Nachrichten werden direkt in der classes\Player Klasse gespeichert

// Chatverlauf abrufen
if (isset($_POST['getLog'])) {
    $gameID = $_POST['gameID'];
    echo json_encode($Player->getLog($gameID)); //mit json_encode() wird das Array in ein JSON-Objekt umgewandelt damit JS das versteht
    exit();
}

//TODO: Eine Klasse für chat+log bauen
