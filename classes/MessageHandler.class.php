<?php


class MessageHandler
{
    private $errorMessages;
    private $successMessages;

    public function __construct()
    {
        $this->errorMessages = [
            1 => "Unerlaubte Zeichen im Benutzernamen.",
            2 => "Benutzername muss zwischen 3 und 13 Zeichen lang sein.",
            3 => "Benutzername existiert nicht.",
            4 => "Passwort ist falsch.",
            5 => "Unerlaubte Zeichen im Benutzernamen.",
            6 => "Benutzername muss zwischen 3 und 13 Zeichen lang sein.",
            7 => "Benutzername schon vergeben.",
            8 => "Passwörter stimmen nicht überein.",
            9 => "Passwort muss zwischen 8 und 72 Zeichen lang sein.",
            10 => "Logge dich ein, bevor du loslegst.",
            11 => "Spiel ist bereits voll. Sorry!",
            12 => "Du kannst nicht gegen dich selbst spielen.",
            13 => "Du hast bereits 10 offene Spiele. Gedulde dich.",
            14 => "Spiel wurde schon abgeschlossen.",
            15 => "Spiel wurde schon abgeschlossen.",
            16 => "Du kannst keine fremden Spiele aufgeben!",
            17 => "Cheater! Du kannst keine Züge für andere Spieler machen.",
            18 => "Cheater! Du bist nicht am Zug.",
            19 => "Cheater! Du bist nicht Teil dieses Spiels.",
            20 => "Cheater! Keine Champions von Außerhalb erlaubt.",
            21 => "Diese Spiel ist bereits vorbei."
        ];

        $this->successMessages = [
            1 => "Login erfolgreich! Willkommen zurück.",
            2 => "Registrierung erfolgreich! Du kannst jetzt loslegen!",
            3 => "Logout erfolgreich. Bis bald!",
            4 => "Spiel erfolgreich erstellt. Warte auf Gegner...",
            5 => "Spiel aufgegeben. Vielleicht beim nächsten Mal!",
            6 => "Spiel beigetreten. Viel Erfolg!",
            7 => "Willkommen zurück. Zeig was du kannst!",
            8 => "Auf zu neue Schlachten!"
        ];
    }

    public function showMessage()
    {
        $message = "";
        $class = "";

        if (isset($_GET['error']) && isset($this->errorMessages[$_GET['error']])) {
            $message = $this->errorMessages[$_GET['error']];
            $class = "errorMessage";
        } elseif (isset($_GET['success']) && isset($this->successMessages[$_GET['success']])) {
            $message = $this->successMessages[$_GET['success']];
            $class = "successMessage";
        }

        if ($message) {
            return "<div id='messageDialog'' title='Hinweis'>
                        <div id='messageContent'>
			                <div class='$class'>{$message}</div>
                        </div>
                    </div>";
        }

        return "";
    }

    public function showMessageDyn($message, $class)
    {
        //TODO: unabhängig vom get parameter machen. Siehe action_chat.php


        return "<div id='messageDialog'' title='Hinweis'>
                    <div id='messageContent'>
                        <div class='$class'>{$message}</div>
                    </div>
                </div>";
    }
}
