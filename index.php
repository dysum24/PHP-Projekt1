<?php
include 'verbindung.php';
include 'funktionen.php';

// filter nach kategorie
$filter = "";
if (isset($_GET['kategorie'])) {
    $filter = $_GET['kategorie'];
}

// abos holen, mit oder ohne filter
if ($filter == "") {
    $abos = $pdo->query("SELECT abos.*, kategorien.name AS kategorie, kategorien.farbe
                         FROM abos
                         JOIN kategorien ON abos.kategorie_id = kategorien.id
                         ORDER BY abos.name")->fetchAll();
} else {
    $sql = $pdo->prepare("SELECT abos.*, kategorien.name AS kategorie, kategorien.farbe
                          FROM abos
                          JOIN kategorien ON abos.kategorie_id = kategorien.id
                          WHERE abos.kategorie_id = ?
                          ORDER BY abos.name");
    $sql->execute([$filter]);
    $abos = $sql->fetchAll();
}

// kategorien für die auswahl im filter
$kategorien = $pdo->query("SELECT * FROM kategorien ORDER BY name");

// kosten pro monat zusammenrechnen
$proMonat = 0;
foreach ($abos as $abo) {
    $proMonat = $proMonat + monatskosten($abo['preis'], $abo['intervall']);
}

// abos deren kündigungsfrist in den nächsten 30 tagen abläuft
$hinweise = $pdo->query("SELECT name, kuendigungsfrist FROM abos
                         WHERE kuendigungsfrist BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                         ORDER BY kuendigungsfrist")->fetchAll();

$seite = "uebersicht";
include 'kopf.php';
?>

<h1>Übersicht</h1>
<p class="grau">Alle Abos auf einen Blick.</p>

<div class="karten">
    <div class="karte">
        <div class="grau">Abos</div>
        <div class="zahl"><?php echo count($abos); ?></div>
    </div>
    <div class="karte">
        <div class="grau">Pro Monat</div>
        <div class="zahl"><?php echo euro($proMonat); ?></div>
    </div>
    <div class="karte">
        <div class="grau">Pro Jahr</div>
        <div class="zahl"><?php echo euro($proMonat * 12); ?></div>
    </div>
</div>

<?php if (count($hinweise) > 0) { ?>
    <div class="hinweis">
        <strong>Kündigungsfrist läuft bald ab</strong>
        <?php foreach ($hinweise as $hinweis) { ?>
            <p><?php echo htmlspecialchars($hinweis['name']); ?>, bis <?php echo date("d.m.Y", strtotime($hinweis['kuendigungsfrist'])); ?></p>
        <?php } ?>
    </div>
<?php } ?>

<form class="filter" method="get" action="index.php">
    <select class="feld" name="kategorie">
        <option value="">Alle Kategorien</option>
        <?php foreach ($kategorien as $kategorie) { ?>
            <option value="<?php echo $kategorie['id']; ?>" <?php if ($kategorie['id'] == $filter) { echo "selected"; } ?>>
                <?php echo htmlspecialchars($kategorie['name']); ?>
            </option>
        <?php } ?>
    </select>
    <button class="knopf-rand" type="submit">Filtern</button>
</form>

<?php if (count($abos) == 0) { ?>
    <?php if ($filter != "") { ?>
        <p class="grau">Keine Abos in dieser Kategorie.</p>
    <?php } else { ?>
        <p class="grau">Noch keine Abos vorhanden.</p>
        <a class="knopf-weiss" href="abo_bearbeiten.php">Erstes Abo anlegen</a>
    <?php } ?>
<?php } else { ?>
    <div class="box">
        <table>
            <tr>
                <th>Name</th>
                <th>Kategorie</th>
                <th>Preis</th>
                <th>Intervall</th>
                <th>Kündigung bis</th>
                <th></th>
            </tr>
            <?php foreach ($abos as $abo) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($abo['name']); ?></td>
                    <td>
                        <span class="punkt" style="background-color: <?php echo htmlspecialchars($abo['farbe']); ?>"></span>
                        <?php echo htmlspecialchars($abo['kategorie']); ?>
                    </td>
                    <td><?php echo euro($abo['preis']); ?></td>
                    <td><?php echo $abo['intervall']; ?></td>
                    <td>
                        <?php
                        if ($abo['kuendigungsfrist'] != null) {
                            echo date("d.m.Y", strtotime($abo['kuendigungsfrist']));
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>
                    <td>
                        <a href="abo_bearbeiten.php?id=<?php echo $abo['id']; ?>">Bearbeiten</a>
                        <a href="abo_loeschen.php?id=<?php echo $abo['id']; ?>" onclick="return confirm('Abo wirklich löschen?');">Löschen</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
<?php } ?>

<?php include 'fuss.php'; ?>