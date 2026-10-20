<?php
include 'verbindung.php';

$fehler = [];

// leeres abo als startwert
$abo = [
    'id' => '',
    'name' => '',
    'anbieter' => '',
    'kategorie_id' => '',
    'preis' => '',
    'intervall' => 'monatlich',
    'startdatum' => '',
    'kuendigungsfrist' => '',
    'notiz' => ''
];

// bearbeiten: vorhandenes abo laden
if (isset($_GET['id'])) {
    $sql = $pdo->prepare("SELECT * FROM abos WHERE id = ?");
    $sql->execute([$_GET['id']]);
    $abo = $sql->fetch();

    if (!$abo) {
        header("Location: index.php");
        exit;
    }

    if ($abo['notiz'] == null) {
        $abo['notiz'] = "";
    }
}

// formular wurde abgeschickt
if (isset($_POST['name'])) {
    $abo['id'] = $_POST['id'];
    $abo['name'] = trim($_POST['name']);
    $abo['anbieter'] = trim($_POST['anbieter']);
    $abo['kategorie_id'] = $_POST['kategorie_id'];
    $abo['preis'] = str_replace(',', '.', $_POST['preis']);
    $abo['intervall'] = $_POST['intervall'];
    $abo['startdatum'] = $_POST['startdatum'];
    $abo['kuendigungsfrist'] = $_POST['kuendigungsfrist'];
    $abo['notiz'] = trim($_POST['notiz']);

    // eingaben prüfen
    if ($abo['name'] == "") {
        $fehler[] = "Bitte einen Namen eingeben.";
    }
    if ($abo['anbieter'] == "") {
        $fehler[] = "Bitte einen Anbieter eingeben.";
    }
    if ($abo['kategorie_id'] == "") {
        $fehler[] = "Bitte eine Kategorie wählen.";
    }
    if (!is_numeric($abo['preis']) || $abo['preis'] <= 0) {
        $fehler[] = "Bitte einen gültigen Preis eingeben.";
    }
    if ($abo['intervall'] != "monatlich" && $abo['intervall'] != "jährlich") {
        $fehler[] = "Bitte ein Intervall wählen.";
    }
    if ($abo['startdatum'] == "") {
        $fehler[] = "Bitte ein Startdatum eingeben.";
    }

    // nur speichern wenn keine fehler
    if (count($fehler) == 0) {
        $frist = $abo['kuendigungsfrist'];
        if ($frist == "") {
            $frist = null;
        }

        if ($abo['id'] == "") {
            $sql = $pdo->prepare("INSERT INTO abos (kategorie_id, name, anbieter, preis, intervall, startdatum, kuendigungsfrist, notiz) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $sql->execute([$abo['kategorie_id'], $abo['name'], $abo['anbieter'], $abo['preis'], $abo['intervall'], $abo['startdatum'], $frist, $abo['notiz']]);
        } else {
            $sql = $pdo->prepare("UPDATE abos SET kategorie_id = ?, name = ?, anbieter = ?, preis = ?, intervall = ?, startdatum = ?, kuendigungsfrist = ?, notiz = ? WHERE id = ?");
            $sql->execute([$abo['kategorie_id'], $abo['name'], $abo['anbieter'], $abo['preis'], $abo['intervall'], $abo['startdatum'], $frist, $abo['notiz'], $abo['id']]);
        }

        header("Location: index.php");
        exit;
    }
}

// kategorien für die auswahl
$kategorien = $pdo->query("SELECT * FROM kategorien ORDER BY name");

include 'kopf.php';
?>

<h1>