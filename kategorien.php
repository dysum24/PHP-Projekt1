<?php
include 'verbindung.php';

$meldung = "";

// neue kategorie speichern
if (isset($_POST['name'])) {
    $name = trim($_POST['name']);
    $farbe = $_POST['farbe'];

    if ($name == "") {
        $meldung = "Bitte einen Namen eingeben.";
    } else {
        $sql = $pdo->prepare("INSERT INTO kategorien (name, farbe) VALUES (?, ?)");
        $sql->execute([$name, $farbe]);
        header("Location: kategorien.php");
        exit;
    }
}

// kategorie löschen, aber nur ohne abos
if (isset($_GET['loeschen'])) {
    $id = $_GET['loeschen'];

    $sql = $pdo->prepare("SELECT COUNT(*) FROM abos WHERE kategorie_id = ?");
    $sql->execute([$id]);
    $anzahl = $sql->fetchColumn();

    if ($anzahl > 0) {
        $meldung = "Diese Kategorie hat noch Abos und kann nicht gelöscht werden.";
    } else {
        $sql = $pdo->prepare("DELETE FROM kategorien WHERE id = ?");
        $sql->execute([$id]);
        header("Location: kategorien.php");
        exit;
    }
}

// alle kategorien holen
$kategorien = $pdo->query("SELECT * FROM kategorien ORDER BY name");

include 'kopf.php';
?>

<h1>Kategorien</h1>

<?php if ($meldung != "") { ?>
    <p class="meldung"><?php echo $meldung; ?></p>
<?php } ?>

<form method="post" action="kategorien.php">
    <input class="feld" type="text" name="name" placeholder="Name der Kategorie">
    <input type="color" name="farbe" value="#888888">
    <button class="knopf-weiss" type="submit">Anlegen</button>
</form>

<table>
    <?php foreach ($kategorien as $kategorie) { ?>
        <tr>
            <td><span class="punkt" style="background-color: <?php echo $kategorie['farbe']; ?>"></span></td>
            <td><?php echo htmlspecialchars($kategorie['name']); ?></td>
            <td><a href="kategorien.php?loeschen=<?php echo $kategorie['id']; ?>">Löschen</a></td>
        </tr>
    <?php } ?>
</table>

<?php include 'fuss.php'; ?>