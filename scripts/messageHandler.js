$(document).ready(function () {
    // Falls die Nachricht existiert, das jQuery UI Fenster anzeigen
    $("#messageDialog").show("puff", {}, 500);

    // Automatisch nach 5 Sekunden ausblenden
    setTimeout(function () {
        $("#messageDialog").hide("drop", {}, 500);
    }, 3000);
});

