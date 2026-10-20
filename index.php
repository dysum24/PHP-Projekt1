<?php
include 'verbindung.php';

// alle abos mit ihrer kategorie holen
$abos = $pdo->query("SELECT abos.*, kategorien.name AS kategorie, kategorien.farbe
                     FROM abos
                     JOIN kategorien ON abos.kategorie_id = kategorien.id
                     ORDER BY abos.name")->fetchAll();

include 'kopf.php';
?>

<h1>Übersicht</h1>
<p class="grau">Alle Abos auf einen Blick.</p>

<?php if (count($abos) == 0) { ?>
    <p class="grau">Noch keine Abos vorhanden.</p>
<?php } else { ?>
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
                <td><?php echo number_format($abo['preis'], 2, ',', '.'); ?> €</td>
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
                    <a href="abo_loeschen.php?id=<?php echo $abo['id']; ?>">Löschen</a>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>

<?php include 'fuss.php'; ?>