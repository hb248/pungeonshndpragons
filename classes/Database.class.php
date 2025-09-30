<?php


class Database
{
    protected $db;

    function __construct()
    {
        try {
            $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // den inhalt von $e in ein txt file schreiben
            file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);

            die("Verbindung zur Datenbank fehlgeschlagen");
        }
    }
}
