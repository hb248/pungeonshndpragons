<?php


require_once('../../config.inc.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$Player = new Player();
$playerID = $_SESSION['playerID'];
$joinableGames = $Player->getJoinableGames();

// Beitretbare Spiele (JoinGame-Liste) oder "Spiel Beitreten"
?>

<?php foreach ($joinableGames as $game): ?>
    <div class="Game">
        <div class="infoSide">
            <p>#<?= $game['gameID'] ?></p>
            <p>Spiel von <?= $game['hostName'] ?></p>
            <p>1/2 Spieler</p>
        </div>
        <div class="btnSide">
            <?php if ($game['hostID'] != $playerID): ?>
                <form action="../../action_hub.php" method="POST">
                    <input type="hidden" name="gameID" value="<?= $game['gameID'] ?>">
                    <input type="submit" name="joinGame" value="Beitreten">
                </form>
            <?php else: ?>
                (Dein Spiel)
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>


<?php if (empty($joinableGames)): ?>
    <p>Keine beitretbaren Spiele vorhanden.</p>
<?php endif; ?>