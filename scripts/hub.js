//AJAX-Code für die Spieleliste und die Spielverwaltung

let updateCooldown = false;

// aktualisieren
function loadGames() {
    if (updateCooldown) return; // Cooldown aktiv? Dann abbrechen.

    $.get("/sites/dynamic/hub_joinGameList.php", function (data) {
        $("#GameListJ").html(data); // data ist der Rückgabewert von hub_joinGameList.php
    });

    $.get("/sites/dynamic/hub_contGameList.php", function (data) {
        $("#GameListC").html(data);
    });

    console.log("loadGames ausgeführt");

    // Cooldown, um Spam zu verhindern
    updateCooldown = true;
    setTimeout(() => updateCooldown = false, 1000);
    /* Der Cooldown wäre eigentlich nicht mehr nötig, da ich sowieso immer auf action_hub.php weiterleite über
    * die forms. Aber vorher hatte ich onclick funktionen hier drinnen und da hat es eben so noch funktioniert.
    * In der derzeitigen Form müsste ich wahrscheinlich über localStorage arbeiten damit der Cooldown wieder funktioniert
    * weil ja beim neuladen der seite auch der cooldown zurückgesetzt wird. wobei ein cooldown in js auch echt leicht
    * umgangen werden kann. Aber es würde zumindest ein paar leute davon abhalten, die seite zu spammen.
    * */

    //TODO: Cooldown über localStorage
}

// Alle x Sekunden die Liste automatisch aktualisieren
setInterval(loadGames, 10000);

// Beim Start die Liste einmalig laden
$(document).ready(function () {
    loadGames();
});

