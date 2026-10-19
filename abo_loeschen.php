<?php
include 'verbindung.php';

// abo mit der id aus der adresse löschen
if (isset($_GET['id'])) {
    $sql = $pdo->prepare("DELETE FROM abos WHERE id = ?");
    $sql->execute([$_GET['id']]);
}

header("Location: index.php");
exit;