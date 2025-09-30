$(document).ready(function () {

    //------------------------------------------------------------------------------------------------------------------
    //AJAX-Code für den Chat und den Log
    //------------------------------------------------------------------------------------------------------------------

    function loadChat() {
        let gameID = $("input[name='gameID']").val();

        $.post("action_chat.php", {getMessages: true, gameID: gameID}, function (response) {
            let messages = JSON.parse(response); // JSON-Array in JS-Array umwandeln weil ja.
            //console.log(messages); // Debug
            $("#ChatContent").html(""); // Chat leeren
            messages.forEach(msg => { // und Chat wieder füllen
                let decodedMessage = $("<div>").html(msg.message).text(); // htmlspecialchars decode - Zuerst nachricht in div einfügen, dann text() um nur den text zu bekommen.
                $("#ChatContent").append(`<p><i>${msg.date}</i> <strong>${msg.name}:</strong> ${decodedMessage}</p>`); // Nachrichten einfügen
            });

            // den Chat nach unten scrollen
            let chatContent = document.getElementById("ChatContent"); // hat mit $("#ChatContent") nicht funktioniert. kp warum
            chatContent.scrollTop = chatContent.scrollHeight;

            //TODO: Scrollposition merken und wiederherstellen nach reload oder reload solange verhindern

        });
    }

    function loadLog() {
        let gameID = $("input[name='gameID']").val(); // Sucht einfach die ganze Seite nach dem input ab, also auch im Chat-Formular. ez fix: id="gameID" statt name="gameID"
        //TODO: gameID per ID suchen, nicht per name

        $.post("action_log.php", {getLog: true, gameID: gameID}, function (response) {
            //console.log(response); // Debug-Ausgabe der Antwort
            let log = JSON.parse(response); // JSON-Array in JS-Array umwandeln weil ja. JSON-Array in JS-Array umwandeln weil ja.
            $("#LogContent").html(""); // Log leeren
            log.forEach(logEntry => { // und Log wieder füllen
                $("#LogContent").append(`<p><i>${logEntry.date.split(" ")[1]}</i> ${logEntry.message}</p>`); // Log einfügen
            });
            //TODO: rausfinden warum .split hier nötig war aber beim chat nicht.

            // den Log nach unten scrollen
            let logContent = document.getElementById("LogContent");
            logContent.scrollTop = logContent.scrollHeight;

        });
    }


    $("#chatForm").submit(function (e) {
        e.preventDefault(); // Damit die form nicht abgeschickt wird bzw die Seite neu lädt

        let gameID = $("input[name='gameID']").val();
        let message = $("#chatInput").val().trim(); // input[name='message'] wäre auch möglich, aber id="chatInput" ist schöner

        if (message == "") return; // Keine leeren Nachrichten. null/undefined würden an trim() scheitern.


        $.post("action_chat.php", {gameID: gameID, message: message, sendMessage: true}, function () {

            $("#chatInput").val(""); // Nach senden Eingabefeld leeren
            loadChat(); // und Direkt neu laden
            loadLog(); // und Log neu laden
        });
    });

    setInterval(loadChat, 8000);
    loadChat(); // Beim Start einmalig laden

    setInterval(loadLog, 10000);
    loadLog();

    //------------------------------------------------------------------------------------------------------------------
    //(AJAX-)Code für Spielfeld und Spielmechaniken
    //------------------------------------------------------------------------------------------------------------------

    // Who am I? Erstmal alle Infos holen
    let playerID = $("#playerID").text();
    let gameID = $("#gameID").text();

    let hostID = $("#hostID").text();
    let oppoID = $("#oppoID").text();

    let step = $("#step").text(); //sollte mit checkTurn aktuell gehalten werden

    let whoami = playerID == hostID ? "host" : "oppo";
    let gameover = false; // make a wild fuckin' guess, buddy
    let atkBlocker = false; // Blockiert Angriffe bis der aktuelle abgeschlossen ist
    let lastTurnPlayer = null; // Speichert, wer zuletzt am Zug war
    let selectedChampion = null; // Speichert den gewählten Angreifer
    let selectedTarget = null;   // Speichert das gewählte Ziel
    let hpUpdateInterval = null; // Variable für das Intervall

    if (whoami == "host") {
        $("#HostField").addClass("playerField");
    } else {
        $("#OppoField").addClass("playerField");
    }

    // Checken, wer dran ist
    function checkTurn() {
        $.post("action_game.php", {getGameInfo: true, gameID: gameID}, function (response) {
            let data = JSON.parse(response);
            step = data.step;
            let turnPlayer = (step % 2 === 1) ? data.hostID : data.oppoID;
            let turnPlayerName = (step % 2 === 1) ? data.hostName : data.oppoName;

            let currentContent = $("#StepCounter").html();
            let newContent = `
                <p>Runde ${Math.ceil(step / 6)}</p>
                <p>${turnPlayerName} ist am Zug</p>
            `;

            if (currentContent !== newContent) {
                $("#StepCounter").fadeOut(400, function () {
                    $(this).html(newContent).fadeIn(400);
                });
            }

            if (data.winnerID != null) {
                gameover = true; //falls es noch nicht gesetzt war. zb wenn man die seite neu lädt.
                $("#ResignForm").html('<input type="submit" name="backToHub" value="Zurück zum Hub">'); // den Aufgeben button ersetzen mit einem zurück zum Hub button
            }


            //TODO: checken ob die Meldung jetzt wirklich nur einmal kommt, oder ob das spammen kann.
            if (gameover) {
                loadLog(); // Log aktualisieren bevor der Alert kommt, weil der Alert das Fenster blockiert.
                alert("Game Over! Auf zu neue Abenteuern!");
                gameover = true; // unnötig, aber sicher ist sicher
                return;
            }

            //Nur enableAttack aufrufen, wenn sich der Zug geändert hat aka Spamschutz
            if (turnPlayer != lastTurnPlayer) {
                lastTurnPlayer = turnPlayer; // Speichern, wer jetzt dran ist

                if (turnPlayer == playerID && !gameover) {
                    enableAttack();
                    $("#StepCounter").addClass("myTurnHighlight");
                } else {
                    disableAttack();
                    $("#StepCounter").removeClass("myTurnHighlight");
                }
            }
        });
    }

    checkTurn(); // Beim Start einmalig aufrufen

    function enableAttack() {
        // HP-Update Interval stoppen für Performance, ab hier wird nach jedem Angriff sowieso aktualisiert.
        clearInterval(hpUpdateInterval);
        hpUpdateInterval = null;
        updateChampionHPs(); // Einmalig beim Start ausführen falls sich der Interval blöd trifft oder man die seite neu lädt.


        // Eigenen Champion auswählen
        $(".ChampionBox[data-owner='" + whoami + "']").off("click").on("click", function () {
            if (selectedChampion) {
                $("#" + selectedChampion).removeClass("selected"); // Vorherigen Champion deselektieren
            }
            $(this).addClass("selected"); // Diesen Selektieren
            selectedChampion = $(this).attr("id"); // ID des selektierten Champions speichern
        });

        // Gegnerischen Champion auswählen
        $(".ChampionBox[data-owner='" + (whoami === "host" ? "oppo" : "host") + "']").off("click").on("click", function () {
            if (!selectedChampion) return; // je nachdem ob ein eigner Champion selektiert ist oder nicht gehts weiter
            $(this).addClass("selected"); // diesen Selektieren
            selectedTarget = $(this).attr("id"); // ID des selektierten Ziels speichern

            // Angriff ausführen
            if (!gameover && !atkBlocker) executeAttack(selectedChampion, selectedTarget); // extra billig gesichert.
            //TODO: sicherung verbessern und evtl schauen ob das nicht schon so passt wegen db.
            console.log("Angriff: " + selectedChampion + " -> " + selectedTarget);
        });
    }

    function disableAttack() {
        $(".ChampionBox").removeClass("selected").off("click");

        //Spielfeld aktualisieren, damit der wartende Spieler auch sieht was passiert.
        if (hpUpdateInterval) return; // Wenn schon ein Interval läuft, dann nicht nochmal starten

        // Alle 3 Sekunden HP aktualisieren
        hpUpdateInterval = setInterval(updateChampionHPs, 3000); // Alles cool? Dann let's go!
    }

    function executeAttack(attackerID, defenderID) {
        console.log("executeAttack wird ausgeführt!");
        atkBlocker = true;
        // Zuerst alles absenden
        $.post("action_game.php", {
            gameID: gameID,
            playerID: playerID,
            attackerID: attackerID,
            defenderID: defenderID,
            step: step
        }, function (response) {
            //console.log("executeAttack response:" + response);
            // und dann die Animation starten
            console.log(response); //raw ausgabe
            let data = JSON.parse(response);
            console.log("Response von executeAttack: " + JSON.stringify(data));

            //An dem punkt weiß man schon wer gewonnen hat, also kann man hier gleich abbrechen. gameEnd gibt die spielerID des gewinners zurück.
            if (data.gameEnd != null) {
                loadLog(); // Log aktualisieren bevor der Alert kommt, weil der Alert das Fenster blockiert.
                updateChampionHPs(); //damit auch noch der letzte Angriff angezeigt wird.
                //alert("Game Over! Gewinner: Spieler " + data.gameEnd);
                console.log("Ich hoffe es kam so ein Alert: Game Over! Gewinner: Spieler " + data.gameEnd);
                gameover = true;
                checkTurn(); // Damit sollten die Buttons ausgetauscht werden und beide Spieler in disableAttack landen.

                return;
            }

            console.log("Das wars mit executeAttack. Daten werden nun an animateAttack übergeben.");
            animateAttack(data);
        });
    }

    function animateAttack(data) {
        console.log("yay, wir sind in der animateAttack Funktion!");
        // Alle Boxen auf `display: none` setzen, um Animation von Anfang an zu starten. sollte eig auch ohne funktionieren, aber sicher ist sicher.
        $("#A1JQ, #A2JQ, #A3JQ, #A4JQ, #ActionBoxJQ").hide();

        // Werte in die richtigen Boxen einfügen
        $("#A1").text(`${data.roll}`);
        $("#A2").text(`${data.hit}`);
        $("#A3").text(`${data.armor}`);
        $("#A4").text(`${data.damage > 0 ? data.damage : "X"}`);

        // Schrittweise Animation
        $("#ActionBoxJQ").fadeIn(500, function () {
            $("#A1JQ").fadeIn(500, function () {
                $("#A2JQ").fadeIn(500, function () {
                    $("#A3JQ").fadeIn(500, function () {
                        $("#A4JQ").fadeIn(500, function () {
                            // 1 Sekunde warten, dann alles ausblenden
                            setTimeout(function () {
                                $("#A1JQ, #A2JQ, #A3JQ, #A4JQ, #ActionBoxJQ").fadeOut(500, function () {
                                    updateHealth(data.defenderID, data.newHP, data.maxHP);
                                });
                            }, 1000);
                        });
                    });
                });
            });
        });
        $(".ChampionBox").removeClass("selected"); // Alle Deselektieren
        loadLog(); // Log aktualisieren
        atkBlocker = false;
        checkTurn();
        console.log("animateAttack ist fertig!");
    }

    function updateHealth(champID, newHP, maxHP) {
        // Zahlen in die Boxen einfügen
        $("#" + champID + " .healthValue p").text(newHP + "/" + maxHP);

        // Healthbar anpassen
        $("#" + champID + " .healthBarFill").css("width", (newHP / maxHP * 100) + "%");

        // Wenn HP <= 0, dann ausblenden
        if (newHP <= 0) {
            $("#" + champID).fadeOut();
        }
    }

    // Interval alle 3 Sekunden, um zu checken, wer am Zug ist
    let turnInterval = setInterval(function () {
        if (!gameover) {
            checkTurn();
        } else {
            clearInterval(turnInterval);
        }
    }, 3000);

    updateChampionHPs(); // Einmalig beim Start ausführen damit man auch aktuell ist wenn man rejoined als spieler der grade dran ist.


    function updateChampionHPs() {
        $.post("action_game.php", {getChampionHPs: true, gameID: gameID}, function (response) {
            let data = JSON.parse(response);

            data.forEach(champ => {
                let champID = champ.gameChampID;
                let currentHP = champ.current_hp;
                let maxHP = champ.max_hp;

                // (Falls HP sich geändert hat,) aktualisieren
                $("#" + champID + " .healthValue p").text(currentHP + "/" + maxHP);

                // Healthbar anpassen
                $("#" + champID + " .healthBarFill").animate({width: (currentHP / maxHP * 100) + "%"}, 500);

                // Wenn HP <= 0, dann Champion ausblenden. Nur wenn sichtbar, weil ichs mir eingebildet habe. pErFoRmAnCe
                if (currentHP <= 0) {
                    if ($("#" + champID).is(":visible")) $("#" + champID).fadeOut(500);
                }
            });
        });
    }


});
