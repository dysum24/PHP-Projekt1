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

// alle kategorien mit der anzahl ihrer abos
$kategorien = $pdo->query("SELECT kategorien.*, COUNT(abos.id) AS anzahl
                           FROM kategorien
                           LEFT JOIN abos ON abos.kategorie_id = kategorien.id
                           GROUP BY kategorien.id
                           ORDER BY kategorien.name")->fetchAll();

$seite = "kategorien";
include 'kopf.php';
?>

<h1>Kategorien</h1>
<p class="grau">Ordne deine Abos in Kategorien ein.</p>

<?php if ($meldung != "") { ?>
    <p class="meldung"><?php echo $meldung; ?></p>
<?php } ?>

<div class="zwei-spalten">
    <div class="box">
        <h3>Neue Kategorie</h3>
        <form class="formular" method="post" action="kategorien.php">
            <label>Name</label>
            <input class="feld" type="text" name="name" maxlength="50" placeholder="z. B. Streaming">

            <label>Farbe</label>
            <input type="color" name="farbe" value="#888888">

            <div class="knoepfe">
                <button class="knopf-weiss" type="submit">Anlegen</button>
            </div>
        </form>
    </div>

    <div class="box">
        <?php if (count($kategorien) == 0) { ?>
            <p class="grau">Noch keine Kategorien vorhanden.</p>
        <?php } else { ?>
            <table>
                <tr>
                    <th>Kategorie</th>
                    <th>Abos</th>
                    <th></th>
                </tr>
                <?php foreach ($kategorien as $kategorie) { ?>
                    <tr>
                        <td>
                            <span class="punkt" style="background-color: <?php echo htmlspecialchars($kategorie['farbe']); ?>"></span>
                            <?php echo htmlspecialchars($kategorie['name']); ?>
                        </td>
                        <td><?php echo $kategorie['anzahl']; ?></td>
                        <td>
                            <?php if ($kategorie['anzahl'] == 0) { ?>
                                <a href="kategorien.php?loeschen=<?php echo $kategorie['id']; ?>" onclick="return confirm('Kategorie wirklich löschen?');">Löschen</a>
                            <?php } else { ?>
                                <span class="grau">in Benutzung</span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>
</div>

<?php include 'fuss.php'; ?>