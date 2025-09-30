<?php



require_once('config.inc.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$msgHandler = new MessageHandler();

?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Stylesheets & Scripts -->
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/index.css">
    <script src="scripts/jqueryui/external/jquery/jquery.js"></script>
    <script src="scripts/jqueryui/jquery-ui.min.js"></script>
    <script src="scripts/index.js"></script>
    <script src="scripts/messageHandler.js"></script>

    <title>PHP - Portal</title>
</head>
<body>
<header>
    <h1 id="Welcome">Das Abenteuer wartet!*</h1>
</header>

<main>
    <section id="LoginBox" class="formBox">
        <h2>Login</h2>
        <form action="action_auth.php" method="POST" autocomplete="on">
            <input type="text" name="benutzername" placeholder="Benutzername" pattern="[A-Za-z0-9]{3,13}"
                   title="Dein Benutzername" required>
            <input type="password" name="passwort" placeholder="Passwort" pattern=".{8,72}" title="Dein Passwort"
                   required>
            <input type="submit" name="Login" value="Login">
        </form>
        <p>Keinen Account? <a id="ToRegis">Hier Registrieren.</a></p>
    </section>

    <section id="RegisBox" class="formBox">
        <h2>Registrieren</h2>
        <form action="action_auth.php" method="POST">
            <input type="text" name="benutzername" placeholder="Benutzername" pattern="[A-Za-z0-9]{3,13}"
                   title="Groß-/Kleinbuchstaben und Zahlen zwischen 3-13 Zeichen" required>
            <input type="password" name="passwort" placeholder="Passwort" pattern=".{8,72}"
                   title="Mindestens 8-stellig. Alle Zeichen erlaubt" required>
            <input type="password" name="passwortw" placeholder="Passwort wiederholen" pattern=".{8,72}"
                   title="Mindestens 8-stellig. Alle Zeichen erlaubt" required>
            <input type="submit" name="Registrieren" value="Registrieren">
        </form>
        <p>Passwörter müssen mindestens 8-stellig sein.</p>
        <p>Account vorhanden? <a id="ToLogin">Hier Einloggen.</a></p>
    </section>

</main>

<footer>
    <p>*Nicht wirklich.</p>
</footer>

<!-- MessageHandler Box -->
<?php echo $msgHandler->showMessage(); ?>

</body>
</html>