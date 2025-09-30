<?php


require_once('config.inc.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$Player = new Player();
$playerID = $_SESSION['playerID'];
$gameID = $_POST['gameID'];
//TODO: gameID auch in session speichern zb bei contGame, um Schummeln zu verhindern

$redirectUrl = [
    1 => "index.php?error=17", // Anfrage für falschen Spieler
    2 => "index.php?error=18", // Spieler nicht am Zug
    3 => "index.php?error=19", // Spieler nicht Teil des Spiels
    4 => "index.php?error=20", // Spieler hat versucht auf einen nicht zugehörigen/fremden Champion zuzugreifen
    5 => "hub.php?error=21" // Spiel ist schon vorbei
];

if (isset($_POST['getGameInfo'])) {
    echo json_encode($Player->getGameInfo($gameID));
    exit();
}

if (isset($_POST['attackerID']) && isset($_POST['defenderID'])) {

    // Spielinformationen holen
    $gameData = $Player->getGameInfo($gameID);

    // Ist das Spiel noch aktiv?
    if ($gameData['winnerID'] != null) {
        header("Location: $redirectUrl[5]");
        exit(); // Spiel ist schon vorbei
    }
    //error_log("action_game.php - Zeile 37 - Spiel ist noch aktiv \n", 3, __DIR__ . '/error_log.log');

    //Legitimitätscheck 1 - Kommt die Anfrage vom richtigen Spieler?
    if ($_POST['playerID'] != $playerID) {
        //Skript wird einfach beendet, da es sich nur um Schummelei handeln kann
        header("Location: $redirectUrl[1]");
        session_destroy(); //gtfo
        exit();
    }

    //error_log("action_game.php - Zeile 47 - LegiCheck1 passed \n", 3, __DIR__ . '/error_log.log');

    //Legitimitätscheck 2 - Sind ist der Spieler teil des Spiels und am Zug?
    $step = $_POST['step'];
    //$resFrom = null;

    $host = $gameData['hostID'];
    $oppo = $gameData['oppoID'];
    $playerIDatk = 0; // es gibt keine playerID 0, also im besten fall wirft das hier nen error
    $playerIDdef = 0;

    // Angreifer und Verteidiger bestimmen
    if ($host == $playerID) {
        $playerIDatk = $host;
        $playerIDdef = $oppo;
    } else if ($oppo == $playerID) {
        $playerIDatk = $oppo;
        $playerIDdef = $host;
    } else {
        header("Location: $redirectUrl[3]");
        session_destroy(); // gtfo
        exit(); // Spieler ist nicht Teil des Spiels
    }

    //error_log("action_game.php - Zeile 71 - LegiCheck2 passed \n", 3, __DIR__ . '/error_log.log');

    // Ist der Spieler am Zug?
    if (($playerIDatk == $host && $step % 2 == 0) || ($playerIDatk == $oppo && $step % 2 == 1) || $gameData['step'] != $step) {
        header("Location: $redirectUrl[2]");
        session_destroy(); // gtfo
        exit(); // Spieler ist nicht am Zug
    }

    //error_log("action_game.php - Zeile 80 - Spieler ist wirklich am Zug \n", 3, __DIR__ . '/error_log.log');


    // Werte der Champions holen
    // Gibt: max_hp, current_hp, armor, dmg, hit zurück
    $attacker = $Player->getChampionByID($gameID, $_POST['attackerID'], $host, $oppo);
    $defender = $Player->getChampionByID($gameID, $_POST['defenderID'], $host, $oppo);

    $attackerDMG = $attacker['dmg'];
    //error_log("action_game.php - Zeile 88 - Champion Werte geholt \n", 3, __DIR__ . '/error_log.log');

    //Legitimitätscheck 3 - Passt die attackerID und defenderID zu den Spielern?
    if ($attacker == null || $defender == null) {
        header("Location: $redirectUrl[4]");
        session_destroy(); //gtfo
        exit(); //Spieler hat versucht auf einen nicht existierenden Champion zuzugreifen
        //TODO: prüfen ob es nicht noch möglich ist den champ des gegner auszuwählen um anzugreifen. irgendwas mit host/oppo und step bauen.
        //TODO: anscheinend ist es möglich eigene champions anzugreifen. das sollte auch nicht unbedingt möglich sein.
    }

    //error_log("action_game.php - Zeile 99 - LegiCheck3 passed \n", 3, __DIR__ . '/error_log.log');

    //Legitimitätscheck 4 - Hat der attacker schon angegriffen?
//    if ($attacker['hasAttacked'] == 1) {
//        exit(); //Champion hat schon angegriffen
//    }
    //TODO: die restliche logik dazu implementieren bevor es hier als cheatingversuch abgestempelt wird.

    // alles cool? Dann wird der Kampf durchgeführt

    // Angriffswurf
    $d20 = rand(1, 20); //evtl durch random_int ersetzen für kRyPtOgRaPhIeScHe SiChErHeIt
    $attackRoll = $d20 + $attacker['hit'];
    $dmgRoll = rand(1, $attackerDMG);
    // Würfelwurf ausgeben
    $bonusroll = 0;

    if ($d20 == 20) {
        $bonusroll = rand(1, $attackerDMG);
        $dmgRoll += $bonusroll;
        $Player->sendLog($gameID, "Spieler $playerIDatk hat KRITISCH gewürfelt (nat 20).");
    } else {
        $Player->sendLog($gameID, "Spieler $playerIDatk hat $d20 gewürfelt (1-20).");
        //TODO: Eine Animation in dyn.js dafür bauen
    }

    $damage = ($d20 == 20 || $attackRoll >= $defender['armor']) ? $dmgRoll : 0; // kann nicht unter 0 fallen

    if ($damage > 0) {
        if ($d20 == 20) {
            $Player->sendLog($gameID, "Spieler $playerIDatk hat " . ($damage - $bonusroll) . " Schaden und $bonusroll Bonusschaden erwürfelt  (2x 1-$attackerDMG).");
        } else {
            $Player->sendLog($gameID, "Spieler $playerIDatk hat $damage Schaden erwürfelt (1-$attackerDMG).");
        }
        //$Player->sendLog($gameID, "Spieler $playerIDatk hat $damage Schaden erwürfelt.");
    }
// else {
//        $Player->sendLog($gameID, "Spieler $playerIDatk hat verfehlt.");
//    }


    //TODO: schaden ist 1 bis schadenswert. bei 20, dmg zwei mal würfeln. bei 1 = miss


    // HP berechnen
    $newHP = max(0, $defender['current_hp'] - $damage); // damage abziehen und auf 0 setzen, wenn hp unter 0 fallen

    //error_log("action_game.php - Zeile 121 - Kampf erwürfelt. \n", 3, __DIR__ . '/error_log.log');

    // Daten in die Datenbank schreiben
    $Player->updateChampions($gameID, $_POST['attackerID'], $attacker['champName'], $_POST['defenderID'], $defender['champName'], $newHP, $damage, $playerIDatk, $playerIDdef);

    //error_log("action_game.php - Zeile 126 - Daten in die Datenbank geschrieben. \n", 3, __DIR__ . '/error_log.log');

    // null, wenn das Spiel noch nicht zu Ende ist. sonst steht eine playerID drin
    $gameEnd = $Player->updateGame($gameID, $_POST['attackerID'], $_POST['defenderID'], $playerIDatk, $playerIDdef); // step erhöhen, winner prüfen, etc.

    //error_log("action_game.php - Zeile 131 - Spielstatus geupdated. \n", 3, __DIR__ . '/error_log.log');

    echo json_encode([
        "roll" => $d20,
        "hit" => $attacker['hit'],
        "armor" => $defender['armor'],
        "damage" => $damage,
        "defenderID" => $_POST['defenderID'],
        "newHP" => $newHP,
        "maxHP" => $defender['max_hp'],
        "gameEnd" => $gameEnd
    ]);
}

if (isset($_POST['getChampionHPs'])) {
    $stmt = $Player->getChampionHPs($_POST['gameID']);
    echo json_encode($stmt);
    exit();
}


