<?php



require_once('config.inc.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$msgHandler = new MessageHandler();

if(!$_SESSION['auth']){
    header('Location: index.php?error=10');
    exit;
}

$Player = new Player();
$stats = $Player->getStats($_SESSION['name']);
$rank = $Player->getRank($_SESSION['name']);
$splash = $Player->getRandomSplashText("hub");

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
    <link rel="stylesheet" href="styles/hub.css">
    <script src="scripts/jqueryui/external/jquery/jquery.js"></script>
    <script src="scripts/jqueryui/jquery-ui.min.js"></script>
    <script src="scripts/hub.js"></script>
    <script src="scripts/messageHandler.js"></script>

    <title>PHP - Hub</title>
</head>
<body>
<nav>
    <p><?= $splash ?>, <?= $_SESSION['name']; ?>!</p>
    <form action="action_auth.php" method="POST">
        <input type="submit" name="Logout" value="Logout" id="LogoutBtn">
    </form>
</nav>

<div id="Meat">

    <div id="Left">
        <header>
            <form action="action_hub.php" method="post">
                <input type="submit" name="createGame" value="Spiel erstellen" id="CreateGameBtn">
            </form>
        </header>
        <main>

            <section id="JoinGame">
                <h2 class="GameHeader">Spiel Beitreten</h2>
                <div id="GameListJ" class="GameList">
                    <!--Hier wird der Inhalt von hub_joinGameList.php eingefügt.-->
                </div>
            </section>

            <section id="ContGame">
                <h2 class="GameHeader">Laufende Spiele</h2>
                <div id="GameListC" class="GameList">
                    <!--Hier wird der Inhalt von hub_contGameList.php eingefügt.-->
                </div>
            </section>

        </main>
    </div>

    <aside>
        <h2>Rang</h2>
        <h1 id="Rank"><?= $rank ?></h1>
        <p>Statistiken</p>

        <div id="StatBox">
            <div id="Lefty">
                <p>Wertung</p>
                <p>Siege</p>
                <p>Niederlagen</p>
                <p>Verhältnis</p>
                <br>
                <p>Schaden ausg.</p>
                <p>Schaden erlit.</p>
                <p>Ausgewichen</p>
                <p>Verfehlt</p>
            </div>
            <div id="Righty">
                <p><?= $stats['rating'] ?></p>
                <p><?= $stats['win'] ?></p>
                <p><?= $stats['lose'] ?></p>
                <p><?= $stats['ratio'] ?></p>
                <br>
                <p><?= $stats['dmgdealt'] ?></p>
                <p><?= $stats['dmgtaken'] ?></p>
                <p><?= $stats['evade'] ?></p>
                <p><?= $stats['miss'] ?></p>
            </div>
        </div>
    </aside>

</div>

<!-- MessageHandler Box -->
<?php echo $msgHandler->showMessage(); ?>

</body>
</html>
