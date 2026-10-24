<?php
// welche seite gerade offen ist, damit der button markiert wird
if (!isset($seite)) {
    $seite = "";
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Abo-Manager</title>
</head>
<body>
    <div class="grid-container">
        <div class="seitenleiste">
            <div class="logo">
                <img src="bilder/logo.png" alt="Abo-Manager">
            </div>

            <div class="menue">
                <a class="button <?php if ($seite == "uebersicht") { echo "aktiv"; } ?>" href="index.php"><img src="bilder/icon_uebersicht.png" alt="">Übersicht</a>
                <a class="button <?php if ($seite == "kategorien") { echo "aktiv"; } ?>" href="kategorien.php"><img src="bilder/icon_kategorien.png" alt="">Kategorien</a>
                <a class="button <?php if ($seite == "auswertung") { echo "aktiv"; } ?>" href="auswertung.php"><img src="bilder/icon_auswertung.png" alt="">Auswertung</a>
            </div>
        </div>

        <div class="rechts">
            <div class="oben">
                <a class="knopf-weiss" href="abo_bearbeiten.php">Neues Abo</a>
            </div>

            <div class="inhalt">