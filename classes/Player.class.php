<?php


class Player extends Database
{

    public function getPlayerID($name)
    {
        $stmt = $this->db->prepare("SELECT playerID FROM datPlayer WHERE name = :name");
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getStats($name)
    {
        $stmt = $this->db->prepare("SELECT win, lose, rating, dmgdealt, dmgtaken, evade, miss FROM datPlayer WHERE name = :name");
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $result['ratio'] = round($result['lose'] != 0 ? $result['win'] / $result['lose'] : 0, 2); // Damit man nicht durch 0 teilt. Weil dann würde die Welt explooooodieren.
        return $result;
    }

    public function getRank($name)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*)+1 as rank FROM datPlayer WHERE rating > (SELECT rating FROM datPlayer WHERE name = :name)");
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getRandomSplashText($type)
    {
        $stmt = $this->db->prepare("SELECT text FROM datSplash WHERE type = :type ORDER BY RAND() LIMIT 1");
        $stmt->bindValue(":type", $type, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchColumn();

        if ($result) {
            return $result;
        }
        return "Hier könnte etwas motivierendes stehen";
    }


    // Spiel erstellen & beitreten Mechaniken für Hub

    public function createGame($playerID)
    {
        // Schritt 1: Prüfen, wie viele Spiele der Spieler gerade hostet (nur `waiting`)
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM datGame WHERE hostID = :playerID AND status = 'waiting'");
        $stmt->bindValue(":playerID", $playerID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchColumn();

        if ($result >= 10) {
            return 1; //Spieler hat bereits 10 Spiele gehostet
        }

        //Schritt 2: Spiel erstellen wenn alles passt
        $stmt = $this->db->prepare("INSERT INTO datGame (hostID, status, step) VALUES (:playerID, 'waiting', 1)");
        $stmt->bindValue(":playerID", $playerID, PDO::PARAM_INT);
        $stmt->execute();

        // Log Nachricht speichern
        $gameID = $this->db->lastInsertId(); // ID des neuen Spiels zurückgeben
        $this->sendLog($gameID, "Spiel #$gameID wurde von Spieler $playerID erstellt.");

        // Champions zuweisen
        $this->assignChampions($gameID, $playerID);

        return 2; //Spiel erfolgreich erstellt
    }

    public function joinGame($gameID, $playerID)
    {
        // Schritt 1: Prüfen, ob das Spiel noch joinbar ist UND ob der Spieler nicht gegen sich selbst spielt
        $stmt = $this->db->prepare("SELECT hostID, oppoID FROM datGame WHERE gameID = :gameID AND status = 'waiting' AND oppoID IS NULL");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return 1; // Spiel ist nicht mehr joinbar
        }

        //Schritt 2: Spieler kann nicht gegen sich selbst spielen
        if ($result['hostID'] == $playerID) {
            return 2; // Spieler kann nicht gegen sich selbst spielen
        }

        //Schritt 3: Spiel joinen
        $stmt = $this->db->prepare("UPDATE datGame SET oppoID = :playerID, status = 'ongoing' WHERE gameID = :gameID AND status = 'waiting'");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->bindValue(":playerID", $playerID, PDO::PARAM_INT);
        $stmt->execute();

        // Log Nachricht speichern
        $this->sendLog($gameID, "Spieler $playerID ist dem Spiel #$gameID beigetreten.");

        // Champions zuweisen
        $this->assignChampions($gameID, $playerID);

        return 3; //Spiel erfolgreich gejoint
    }

    public function getJoinableGames()
    {
        $stmt = $this->db->prepare("SELECT g.gameID, g.hostID, p.name as hostName FROM datGame g JOIN datPlayer p ON g.hostID = p.playerID WHERE g.status = 'waiting'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Um rauszufinden welche offenen Spiele der Spieler hat
    public function getPlayerGames($playerID)
    {
        $stmt = $this->db->prepare("SELECT g.gameID, g.status, 
                                CASE WHEN g.hostID = :playerID THEN p2.name ELSE p1.name END as enemyName
                                FROM datGame g
                                LEFT JOIN datPlayer p1 ON g.hostID = p1.playerID
                                LEFT JOIN datPlayer p2 ON g.oppoID = p2.playerID
                                WHERE (g.hostID = :playerID OR g.oppoID = :playerID) AND g.status = 'ongoing'");
        $stmt->bindValue(":playerID", $playerID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

        /*Ergebnis:
         * gameID | status | enemyName
         * 1      | waiting| Gegner1
         * 2      | waiting| Gegner2
         * ...
         * */

        //ongoing durch waiting ersetzen um liste anzuzeigen, falls keine vollen spiele vorhanden sind
    }

    public function resignGame($gameID, $playerID)
    {
        //Schritt 1: Überprüfen ob das spiel noch resignbar ist und ob der Spieler host oder oppo ist
        $stmt = $this->db->prepare("SELECT status, hostID, oppoID FROM datGame WHERE gameID = :gameID");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['status'] !== 'ongoing') {
            return 1; //Spiel ist nicht mehr, oder noch nicht resignbar
            //TODO: waiting und finished einzeln filtern
        }

        if ($result['hostID'] != $playerID && $result['oppoID'] != $playerID) {
            return 3; //Spieler ist nicht Teil des Spiels
        }

        //Schritt 3: Spiel aufgeben
        $stmt = $this->db->prepare("UPDATE datGame SET status = 'finished', winnerID = CASE WHEN hostID = :playerID THEN oppoID ELSE hostID END WHERE gameID = :gameID");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->bindValue(":playerID", $playerID, PDO::PARAM_INT);
        $stmt->execute();

        // Log Nachricht speichern
        $this->sendLog($gameID, "Spieler $playerID hat das Spiel #$gameID aufgegeben.");

        // Elo-Rating
        $winnerID = ($result['hostID'] == $playerID) ? $result['oppoID'] : $result['hostID'];
        $loserID = ($result['hostID'] == $playerID) ? $result['hostID'] : $result['oppoID'];
        $this->eloRating($result['hostID'], $result['oppoID'], $winnerID, $gameID);

        // Stats aktualisieren
        $this->updateStats($loserID, 'lose', 1);
        $this->updateStats($winnerID, 'win', 1);

        return 2; //Spiel erfolgreich aufgegeben
    }

    public function contGame($gameID)
    {
        //Schritt 1: Überprüfen ob das spiel noch fortsetzbar ist indem der status auf 'ongoing' ist
        $stmt = $this->db->prepare("SELECT status FROM datGame WHERE gameID = :gameID");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchColumn();

        if ($result !== 'ongoing') {
            return 1; //Spiel ist nicht mehr fortsetzbar
        }

        //gameID in Session speichern
        $_SESSION['gameID'] = $gameID;
        //TODO: zb in action_game.php verwenden. aber zuerst cases durchdenken!

        //Schritt 2: Spiel fortsetzen
        return 2; //Spiel kann fortgesetzt werden
    }


    // Chat Mechaniken für Dyn

    public function sendMessage($gameID, $playerID, $message)
    {
//        if (trim($message) === '') {
//            return false; // Leere Nachrichten verhindern
//        }
        // Wird direkt in dyn.js überprüft

        //An dieser Stelle sollte eigentlich schon alles cool sein.

        $stmt = $this->db->prepare("INSERT INTO datMessages (gameID, playerID, message) VALUES (:gameID, :playerID, :message)");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->bindValue(":playerID", $playerID, PDO::PARAM_INT);
        $stmt->bindValue(":message", $message, PDO::PARAM_STR);
        $result = $stmt->execute();

        // Log Nachricht speichern
        //$this->sendLog($gameID, "(DEBUG) Spieler $playerID hat eine Nachricht in Spiel #$gameID gesendet.");

        return $result; // gibt true oder false zurück
    }

    public function getMessages($gameID)
    {
        $stmt = $this->db->prepare("SELECT m.message, p.name, DATE_FORMAT(m.date, '%H:%i:%s') as date FROM datMessages m
                            JOIN datPlayer p ON m.playerID = p.playerID
                            WHERE m.gameID = :gameID ORDER BY m.date ASC");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->execute();

        // DATE_FORMAT kürzt das Datum weg.


        return $stmt->fetchAll(PDO::FETCH_ASSOC); // gibt ein Array mit allen Nachrichten zurück
    }


    // Log Mechaniken für Dyn

    public function sendLog($gameID, $message)
    {
        $stmt = $this->db->prepare("INSERT INTO datLog (gameID, message) VALUES (:gameID, :message)");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->bindValue(":message", $message, PDO::PARAM_STR);
        $result = $stmt->execute();

        return $result; // gibt true oder false zurück
    }

    public function getLog($gameID)
    {
        $stmt = $this->db->prepare("SELECT message, date FROM datLog WHERE gameID = :gameID ORDER BY date ASC");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // gibt ein Array mit allen Nachrichten zurück

        //Keine ahnung warum "DATE_FORMAT(date, '%H:%i:%s') as date" hier probleme macht
        //TODO: Warum wird der chat richtig sortiert wenn man hier datum vorfiltert aber log nicht?
    }


    // Spielmechaniken für Dyn

    public function assignChampions($gameID, $playerID)
    {
        // Schritt 1: 3 zufällige Champions auswählen
        $stmt = $this->db->prepare("SELECT champID, hp FROM datChampion ORDER BY RAND() LIMIT 3");
        $stmt->execute();
        $champions = $stmt->fetchAll(PDO::FETCH_ASSOC);


        // Schritt 2: Champions dem Spieler in `datGameChampions` zuweisen
        $stmt = $this->db->prepare("INSERT INTO datGameChampions (gameID, playerID, champID, hp) VALUES (:gameID, :playerID, :champID, :hp)");

        foreach ($champions as $champion) {
            $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
            $stmt->bindValue(":playerID", $playerID, PDO::PARAM_INT);
            $stmt->bindValue(":champID", $champion['champID'], PDO::PARAM_INT);
            $stmt->bindValue(":hp", $champion['hp'], PDO::PARAM_INT);
            $stmt->execute();

            // Log Nachricht speichern
            $this->sendLog($gameID, "Spieler $playerID wurde Champion {$champion['champID']} für Spiel #$gameID zugeteilt.");
        }

        // Log Nachricht speichern
        $this->sendLog($gameID, "Zuteilung der Champions für Spieler $playerID in Spiel #$gameID abgeschlossen.");

        return true; // nur true, weil kb auf Fehlerbehandlung
    }

    public function getChampions($gameID, $playerID)
    {
        $stmt = $this->db->prepare("
    SELECT gc.gameChampID, c.name, c.hp AS max_hp, gc.hp AS current_hp, c.armor, c.dmg, c.hit, gc.hasAttacked, c.icon_path
    FROM datGameChampions gc
    JOIN datChampion c ON gc.champID = c.champID
    WHERE gc.gameID = :gameID AND gc.playerID = :playerID
");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->bindValue(":playerID", $playerID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGameInfo($gameID)
    {
        $stmt = $this->db->prepare("
        SELECT g.hostID, g.oppoID, g.step, g.winnerID, g.status, 
               p1.name as hostName, p2.name as oppoName
        FROM datGame g
        LEFT JOIN datPlayer p1 ON g.hostID = p1.playerID
        LEFT JOIN datPlayer p2 ON g.oppoID = p2.playerID
        WHERE g.gameID = :gameID
    ");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getChampionByID($gameID, $gameChampID, $hostID, $oppoID)
    {
        $stmt = $this->db->prepare("
        SELECT c.hp AS max_hp, gc.hp AS current_hp, c.armor, c.dmg, c.hit, gc.playerID, c.name AS champName, gc.hasAttacked
        FROM datGameChampions gc
        JOIN datChampion c ON gc.champID = c.champID
        WHERE gc.gameID = :gameID AND gc.gameChampID = :gameChampID
        ");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->bindValue(":gameChampID", $gameChampID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // Hier bekomme ich werte: max_hp, current_hp, armor, dmg, hit, playerID, hasAttacked zurück

        //Legitimitätscheck 3 - Passt die attackerID und defenderID zu den Spielern?
        if ($result['playerID'] != $hostID && $result['playerID'] != $oppoID) {
            return null;
        }

        return $result;
    }

    public function updateChampions($gameID, $attackerID, $attackerName, $defenderID, $defenderName, $defenderHP, $dmg, $playerIDatk, $playerIDdef)
    {
        // Schritt 1: HP des Verteidigers aktualisieren
        $stmt = $this->db->prepare("UPDATE datGameChampions SET hp = :hp WHERE gameChampID = :defenderID");
        $stmt->bindValue(":defenderID", $defenderID, PDO::PARAM_INT);
        $stmt->bindValue(":hp", $defenderHP, PDO::PARAM_INT);
        $stmt->execute();

        // Schritt 2: hasAttacked des Angreifers auf 1 setzen
        $stmt = $this->db->prepare("UPDATE datGameChampions SET hasAttacked = 1 WHERE gameChampID = :attackerID");
        $stmt->bindValue(":attackerID", $attackerID, PDO::PARAM_INT);
        $stmt->execute();

        //Schaden ist nur 0 wenn es ein "Miss" war. in dem fall ändern sich auch die hp nicht
        if ($dmg == 0) {
            // Log Nachricht speichern
            $this->sendLog($gameID, "$attackerName hat $defenderName verfehlt.");

            // Stats aktualisieren
            $this->updateStats($playerIDatk, 'miss', 1);
            $this->updateStats($playerIDdef, 'evade', 1);

            return;
        }

        // Stats aktualisieren
        $this->updateStats($playerIDatk, 'dmgdealt', $dmg);
        $this->updateStats($playerIDdef, 'dmgtaken', $dmg);

        if ($defenderHP <= 0) {
            // Log Nachricht speichern
            $this->sendLog($gameID, "$attackerName hat $defenderName für $dmg Schaden getroffen und besiegt.");
            return;
        }

        //wenn schaden durchkommt, dann wird das hier ausgeführt
        // Log Nachricht speichern
        $this->sendLog($gameID, "$attackerName hat $defenderName für $dmg Schaden getroffen und seine/ihre HP auf $defenderHP reduziert.");
        //return;
    }

    public function updateGame($gameID, $attackerID, $defenderID, $player1ID, $player2ID)
    {
        // Schritt 1: Step erhöhen
        $stmt = $this->db->prepare("UPDATE datGame SET step = step + 1 WHERE gameID = :gameID");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->execute();

        //$this->sendLog($gameID, "Schritt $step");
        ///TODO: irgendwo ind er funktion die Zugnummer und die Rundennummer ausgeben

        // Schritt 2: hasAttacked zurücksetzen, nur wenn 6 Schritte vergangen sind
        $stmt = $this->db->prepare("SELECT step FROM datGame WHERE gameID = :gameID");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->execute();
        $step = $stmt->fetchColumn();

        if (($step - 1) % 6 == 0) {
            $stmt = $this->db->prepare("UPDATE datGameChampions SET hasAttacked = 0 WHERE gameID = :gameID");
            $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
            $stmt->execute();
        }


        //Schritt 3: Leben aller Champions überprüfen
//        $player1ID = $attackerID;
//        $player2ID = $defenderID;

        //error_log("DEBUG: player1ID - " . json_encode($player1ID) . " player2ID - " . json_encode($player2ID) . " gameID - " . json_encode($gameID), 3, __DIR__ . '/error_log.log');

        $stmt = $this->db->prepare("
            SELECT 
                SUM(CASE WHEN playerID = :player1ID THEN hp ELSE 0 END) AS player1HP,
                SUM(CASE WHEN playerID = :player2ID THEN hp ELSE 0 END) AS player2HP
            FROM datGameChampions
            WHERE gameID = :gameID
        ");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->bindValue(":player1ID", $player1ID, PDO::PARAM_INT);
        $stmt->bindValue(":player2ID", $player2ID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        //error_log("DEBUG: SQL Result (PHP) - " . json_encode($result), 3, __DIR__ . '/error_log.log');

        $player1HP = $result['player1HP'] ?? 0; //könnte man eig wieder isset($result['player1HP']) ? $result['player1HP'] : 0; machen dann wär die ganze seite mit php < 7 kompatibel
        $player2HP = $result['player2HP'] ?? 0;

        //error_log("DEBUG: SQL Result - player1HP: " . json_encode($result['player1HP']) . " | player2HP: " . json_encode($result['player2HP']), 3, __DIR__ . '/error_log.log');


        // Schritt 4: Gewinner überprüfen
        if ($player1HP <= 0) {
            $stmt = $this->db->prepare("UPDATE datGame SET status = 'finished', winnerID = :player2ID WHERE gameID = :gameID");
            $stmt->bindValue(":player2ID", $player2ID, PDO::PARAM_INT);
            $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
            $stmt->execute();

            // Log Nachricht speichern
            $this->sendLog($gameID, "Spieler $player2ID hat das Spiel #$gameID gewonnen.");

            // Elo-Rating
            $this->eloRating($player1ID, $player2ID, $player2ID, $gameID);

            // Stats aktualisieren
            $this->updateStats($player1ID, 'lose', 1);
            $this->updateStats($player2ID, 'win', 1);

            return $player2ID;
        }

        if ($player2HP <= 0) {
            $stmt = $this->db->prepare("UPDATE datGame SET status = 'finished', winnerID = :player1ID WHERE gameID = :gameID");
            $stmt->bindValue(":player1ID", $player1ID, PDO::PARAM_INT);
            $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
            $stmt->execute();

            // Log Nachricht speichern
            $this->sendLog($gameID, "Spieler $player1ID hat das Spiel #$gameID gewonnen.");

            // Elo-Rating
            $this->eloRating($player1ID, $player2ID, $player1ID, $gameID);

            // Stats aktualisieren
            $this->updateStats($player1ID, 'win', 1);
            $this->updateStats($player2ID, 'lose', 1);

            return $player1ID;
        }

        return null;


    }

    private function eloRating($playerID1, $playerID2, $winnerID, $gameID)
    {
        // Schritt 1: Elo-Werte der Spieler holen aus datPlayer, spalte rating
        $stmt = $this->db->prepare("SELECT playerID, rating FROM datPlayer WHERE playerID IN (:playerID1, :playerID2)");
        $stmt->bindValue(":playerID1", $playerID1, PDO::PARAM_INT);
        $stmt->bindValue(":playerID2", $playerID2, PDO::PARAM_INT);
        $stmt->execute();
        $ratings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // playerID => rating

        // Spieler-IDs korrekt zuordnen, weil vorher wars bissl random^^
        $ratingP1 = $ratings[$playerID1];
        $ratingP2 = $ratings[$playerID2];

        $EloSkala = 400; // Bestimmt wie stark die Elo-Werte schwanken
        $kFaktor = 20; // Sollte nach Spieler angepasst werden. 20 wäre ein Erwachsener bis 2400 im Schach

        // Schritt 2: Erwartungswert berechnen
        $E1 = 1 / (1 + pow(10, ($ratingP2 - $ratingP1) / $EloSkala));
        $E2 = 1 / (1 + pow(10, ($ratingP1 - $ratingP2) / $EloSkala));

        // Schritt 3: Neue Elo-Werte berechnen
        $newRatings = [];
        if ($winnerID == $playerID1) {
            $newRatings[$playerID1] = round($ratingP1 + $kFaktor * (1 - $E1));
            $newRatings[$playerID2] = round($ratingP2 + $kFaktor * (0 - $E2));
        } else {
            $newRatings[$playerID1] = round($ratingP1 + $kFaktor * (0 - $E1));
            $newRatings[$playerID2] = round($ratingP2 + $kFaktor * (1 - $E2));
        }

        // Schritt 4: Neue Elo-Werte in die Datenbank schreiben
        foreach ($newRatings as $playerID => $newRating) {
            $stmt = $this->db->prepare("UPDATE datPlayer SET rating = :rating WHERE playerID = :playerID");
            $stmt->bindValue(":rating", $newRating, PDO::PARAM_INT);
            $stmt->bindValue(":playerID", $playerID, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Schritt 5: Log-Meldungen schreiben
        foreach ($newRatings as $playerID => $newRating) {
            $oldRating = ($playerID == $playerID1) ? $ratingP1 : $ratingP2;
            $diff = $newRating - $oldRating;
            $this->sendLog($gameID, "Spieler $playerID hat nun $newRating Elo-Rating. (" . ($diff >= 0 ? "+" : "") . "$diff)");
        }
    }

    public function getChampionHPs($gameID)
    {
        $stmt = $this->db->prepare("
        SELECT gameChampID, hp AS current_hp, (SELECT hp FROM datChampion WHERE champID = gc.champID) AS max_hp
        FROM datGameChampions gc
        WHERE gameID = :gameID
    ");
        $stmt->bindValue(":gameID", $gameID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function updateStats($playerID, $stat, $amount)
    {
        //bindValue geht nicht für Spaltennamen, deshalb dieser super krasse switch:
        switch ($stat) {
            case 'win':
            case 'lose':
            case 'rating':
            case 'dmgdealt':
            case 'dmgtaken':
            case 'evade':
            case 'miss':
                $stmt = $this->db->prepare("UPDATE datPlayer SET $stat = $stat + :amount WHERE playerID = :playerID");
                $stmt->bindValue(":amount", $amount, PDO::PARAM_INT);
                $stmt->bindValue(":playerID", $playerID, PDO::PARAM_INT);
                return $stmt->execute();
            default:
                error_log("FEHLER: Ungültiger Statistik-Name: $stat");
                return false;
        }
    }


}