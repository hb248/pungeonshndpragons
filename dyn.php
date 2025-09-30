<?php


require_once('config.inc.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$msgHandler = new MessageHandler();

if (!$_SESSION['auth']) {
    header('Location: index.php?error=10');
    exit;
}

//TODO: gameid von url gegen playerid aus session prüfen

// Get GameID from URL
$gameID = $_GET['gameID'];

$Player = new Player();
$splash = $Player->getRandomSplashText("dyn");

$GameInfo = $Player->getGameInfo($gameID);
$hostChampions = $Player->getChampions($gameID, $GameInfo['hostID']);
$oppoChampions = $Player->getChampions($gameID, $GameInfo['oppoID']);

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
    <link rel="stylesheet" href="styles/dyn.css">
    <script src="scripts/jqueryui/external/jquery/jquery.js"></script>
    <script src="scripts/jqueryui/jquery-ui.min.js"></script>
    <script src="scripts/dyn.js"></script>
    <script src="scripts/messageHandler.js"></script>

    <title>PHP - Game #<?= $gameID ?></title>
</head>
<body>
<nav>
    <div class="navElementWrapper" id="nEW1">
        <p id="SplashText"><?= $splash ?>, <?= $_SESSION['name']; ?>!</p>
    </div>
    <div class="navElementWrapper" id="nEW2">
        <form action="action_hub.php" method="POST" id="ResignForm">
            <input type="hidden" name="gameID" value="<?= $gameID ?>">
            <input type="submit" name="resignGame" value="Aufgeben">
        </form>
    </div>
    <div class="navElementWrapper" id="nEW3">
        <form action="action_auth.php" method="POST" id="LogoutForm">
            <input type="submit" name="Logout" value="Logout" id="LogoutBtn">
        </form>
    </div>
</nav>

<header>
    <div id="Player1">
        <p><?= $GameInfo['hostName'] ?></p>
        <p id="playerdIDshow">ID #<?= $GameInfo['hostID'] ?></p>
    </div>

    <div>
        <div id="StepCounter">
            <p>Runde <?= ceil($GameInfo['step'] / 6) ?></p>
            <p><?= ($GameInfo['step'] % 2 == 1) ? $GameInfo['hostName'] : $GameInfo['oppoName'] ?> ist am Zug</p>
        </div>
    </div>

    <div id="Player2">
        <p><?= $GameInfo['oppoName'] ?></p>
        <p id="playerdIDshow">ID #<?= $GameInfo['oppoID'] ?></p>
    </div>

</header>

<main>
    <section id="HostField">
        <?php foreach ($hostChampions as $champ): ?>
            <div class="ChampionBox" id="<?= $champ['gameChampID'] ?>" data-owner="host">
                <div class="ChampName">
                    <p><?= $champ['name'] ?></p>
                </div>
                <div class="ChampionMeat">
                    <div class="ChampPic">
                        <img src="<?= ltrim($champ['icon_path'], '/') ?>" alt="<?= $champ['name'] ?>">
                    </div>
                    <div class="ChampUI">
                        <div class="upper">
                            <div class="healthBar">
                                <div class="healthBarFill"
                                     style="width: <?= ($champ['current_hp'] / $champ['max_hp']) * 100 ?>%;"></div>
                            </div>
                            <div class="healthValue">
                                <p><?= $champ['current_hp'] ?>/<?= $champ['max_hp'] ?></p>
                            </div>
                        </div>
                        <div class="lower">
                            <div class="ArmorIcon">
                                <img src="images/armor.png" alt="Armor">
                            </div>
                            <div class="ArmorValue"><?= $champ['armor'] ?></div>
                            <div class="DmgIcon">
                                <img src="images/dmg.png" alt="Dmg">
                            </div>
                            <div class="DmgValue"><?= $champ['dmg'] ?></div>
                            <div class="HitIcon">
                                <img src="images/hit.png" alt="Hit">
                            </div>
                            <div class="HitValue"><?= $champ['hit'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
    <section id="BattleField">

        <div id="ActionBoxJQ">
            <div id="ActionBox">
                <div id="A1JQPH" class="A1234JQPH">
                    <div id="A1JQ" class="A1234JQ">
                        <div id="A1" class="A1234">
                            <!--d20-->
                            ??
                        </div>
                    </div>
                </div>
                <p>+</p>
                <div id="A2JQPH" class="A1234JQPH">
                    <div id="A2JQ" class="A1234JQ">
                        <div id="A2" class="A1234">
                            <!--hit-->
                            ??
                        </div>
                    </div>
                </div>
                <p>vs</p>
                <div id="A3JQPH" class="A1234JQPH">
                    <div id="A3JQ" class="A1234JQ">
                        <div id="A3" class="A1234">
                            <!--armor-->
                            ??
                        </div>
                    </div>
                </div>
                <p>=</p>
                <div id="A4JQPH" class="A1234JQPH">
                    <div id="A4JQ" class="A1234JQ">
                        <div id="A4" class="A1234">
                            <!--dmg-->
                            ??
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <section id="OppoField">
        <?php foreach ($oppoChampions as $champ): ?>
            <div class="ChampionBox" id="<?= $champ['gameChampID'] ?>" data-owner="oppo">
                <div class="ChampName">
                    <p><?= $champ['name'] ?></p>
                </div>
                <div class="ChampionMeat">
                    <div class="ChampPic">
                        <img src="<?= ltrim($champ['icon_path'], '/') ?>" alt="<?= $champ['name'] ?>">
                    </div>
                    <div class="ChampUI">
                        <div class="upper">
                            <div class="healthBar">
                                <div class="healthBarFill"
                                     style="width: <?= ($champ['current_hp'] / $champ['max_hp']) * 100 ?>%;"></div>
                            </div>
                            <div class="healthValue">
                                <p><?= $champ['current_hp'] ?>/<?= $champ['max_hp'] ?></p>
                            </div>
                        </div>
                        <div class="lower">
                            <div class="ArmorIcon">
                                <img src="images/armor.png" alt="Armor">
                            </div>
                            <div class="ArmorValue"><?= $champ['armor'] ?></div>
                            <div class="DmgIcon">
                                <img src="images/dmg.png" alt="Dmg">
                            </div>
                            <div class="DmgValue"><?= $champ['dmg'] ?></div>
                            <div class="HitIcon">
                                <img src="images/hit.png" alt="Hit">
                            </div>
                            <div class="HitValue"><?= $champ['hit'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<footer>
    <section id="Log">
        <div id="LogTitle">

            <form action="action_hub.php" method="POST" id="BackToHubForm">
                <input type="submit" name="backToHub" value="Zurück zum Hub">
            </form>
            <p>Log</p>
        </div>
        <div id="LogContent">
            <p>Da ist wohl was schiefgelaufen :)</p>
        </div>
    </section>
    <section id="Chat">
        <div id="ChatTitle">
            <p>Chat</p>
        </div>
        <div id="ChatContent">
            <p>Da ist wohl was schiefgelaufen :)</p>
            <!-- Hier ballert AJAX die Nachrichten rein ;) -->
        </div>
        <!--        <form action="action_chat.php" method="POST">-->
        <!--        Die klassische Form würde an die action Seite leiten. Den POST request übernimmt jetzt js+jquery -->
        <!--        und damit die Seite nicht neugeladen wird gibts preventDefault im js script-->
        <form id="chatForm">
            <input type="hidden" name="gameID" value="<?= $gameID ?>">
            <input type="text" name="message" id="chatInput" placeholder="Nachricht eingeben">
            <input type="submit" value="Senden" id="chatSend">
        </form>
    </section>
</footer>

<div id="hiddenData">
    <div id="playerID"><?= $_SESSION['playerID'] ?></div>
    <div id="gameID"><?= $gameID ?></div>
    <div id="hostID"><?= $GameInfo['hostID'] ?></div>
    <div id="oppoID"><?= $GameInfo['oppoID'] ?></div>
    <div id="step"><?= $GameInfo['step'] ?></div>
</div>

<!-- MessageHandler Box -->
<?php echo $msgHandler->showMessage(); ?>

</body>
</html>
