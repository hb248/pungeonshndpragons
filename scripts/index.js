// Mega krasses Script für crazy Animationen. many wow. much impress. so cool.

$(document).ready(function() {
    console.log("Formular Script geladen");

    // localStorage checken, was vorm Reload angezeigt wurde.
    if (localStorage.getItem("formDisplayed") === "RegisBox") {
        $("#LoginBox").hide();
        $("#RegisBox").show();
    } else {
        $("#RegisBox").hide();
        $("#LoginBox").show();
    }

    $("#ToRegis").on("click", function() {
        console.log("ToRegis wurde geklickt");
        $("#LoginBox").hide("drop", {}, 500, function() {
            $("#RegisBox").show("puff", {}, 500);
            // Speichern, dass man jetzt das Registrierungsformular sieht.
            localStorage.setItem("formDisplayed", "RegisBox");
        });
    });

    $("#ToLogin").on("click", function() {
        console.log("ToLogin wurde geklickt");
        $("#RegisBox").hide("drop", {}, 500, function() {
            $("#LoginBox").show("puff", {}, 500);
            localStorage.setItem("formDisplayed", "LoginBox");
        });
    });
});