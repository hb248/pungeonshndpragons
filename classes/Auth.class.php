<?php



class Auth extends Database
{

//    private $db;
//
//    function __construct()
//    {
//        try {
//            $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
//            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Fehlermeldung, wenn SQL-Fehler
//        } catch (PDOException $e) {
//            //echo $e;
//            echo "Verbindung zur Datenbank fehlgeschlagen";
//            die();
//        }
//    }

    public function login($name, $pass)
    {
        // Eingabe im Namensfeld filtern wie Pattern
        if (!preg_match("/^[A-Za-z0-9]+$/", $name)) {
            return 1; // Unerlaubte Zeichen
        }

        if (strlen($name) < 3 || strlen($name) > 13) {
            return 2; // Ungültige Zeichenanzahl
        }

        $stmt = $this->db->prepare("SELECT * FROM datPlayer WHERE name = :name");
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Wenn der Benutzer nicht existiert
        if (!$result) {
            return 3; // Benutzername falsch
        }

        if ($result && password_verify($pass, $result['pw_hash'])) {
            return 4; // Login erfolgreich
        } else {
            return 5; // Passwort falsch
        }
    }

    public function addUser($name, $pass, $passw)
    {
        // Erste Etappe: Benutzername

        // Eingabe im Namensfeld filtern wie Pattern
        if (!preg_match("/^[A-Za-z0-9]+$/", $name)) {
            return 1; // Unerlaubte Zeichen
        }

        if (strlen($name) < 3 || strlen($name) > 13) {
            return 2; // Ungültige Zeichenanzahl
        }

        // Benutzername überprüfen auf bereits vorhanden
        $stmt = $this->db->prepare("SELECT * FROM datPlayer WHERE name = :name");
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            //echo "Benutzername schon vergeben";
            return 3; // Benutzername schon vergeben
        }

        // Zweite Etappe: Passwort

        // Passwort überprüfen auf Übereinstimmung
        if ($pass != $passw) {
            return 4; // Passwörter stimmen nicht überein
        }

        // Passwort überprüfen auf mindestlänge
        if (strlen($pass) < 8 || strlen($pass) > 72) {
            return 5; // Passwort zu kurz oder zu lang
        }

        // Passwort hashen
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

        // Benutzer anlegen
        $stmt = $this->db->prepare("INSERT INTO datPlayer (name, pw_hash) VALUES (:name, :pass)");
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":pass", $hashedPass, PDO::PARAM_STR);
        $stmt->execute();
        //print_r($stmt->errorInfo());
        return 6; // Benutzer erfolgreich angelegt
    }


}







