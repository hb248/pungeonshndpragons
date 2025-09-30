<?php


require_once('../../config.inc.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$Player = new Player();
$playerID = $_SESSION['playerID'];
$playerGames = $Player->getPlayerGames($playerID);

?>

    <?php foreach ($playerGames as $game): ?>
        <div class="Game">
            <div class="infoSide">
            <p>#<?= $game['gameID'] ?></p>
                <p>VS <?= $game['enemyName'] ?></p>
                <p>2/2 Spieler</p>
            </div>
            <div class="btnSide">
                <form action="../../action_hub.php" method="POST">
                    <input type="hidden" name="gameID" value="<?= $game['gameID'] ?>">
                    <input type="submit" name="resignGame" value="Aufgeben">
                </form>

                <form action="../../action_hub.php" method="POST">
                    <input type="hidden" name="gameID" value="<?= $game['gameID'] ?>">
                    <input type="submit" name="contGame" value="Fortfahren">
                </form>
            </div>
        </div>
    <?php endforeach; ?>

<?php if (empty($playerGames)): ?>
    <p>Keine laufenden Spiele vorhanden.</p>
<?php endif; ?>






