<?php
include 'verbindung.php';
include 'funktionen.php';

// alle abos mit ihrer kategorie holen
$abos = $pdo->query("SELECT abos.preis, abos.intervall, kategorien.name AS kategorie, kategorien.farbe
                     FROM abos
                     JOIN kategorien ON abos.kategorie_id = kategorien.id
                     ORDER BY kategorien.name")->fetchAll();

// kosten pro monat gesamt und je kategorie zusammenrechnen
$gesamt = 0;
$kategorien = [];

foreach ($abos as $abo) {
    $monat = monatskosten($abo['preis'], $abo['intervall']);
    $gesamt = $gesamt + $monat;

    $name = $abo['kategorie'];
    if (!isset($kategorien[$name])) {
        $kategorien[$name] = ['farbe' => $abo['farbe'], 'monat' => 0, 'anzahl' => 0];
    }
    $kategorien[$name]['monat'] = $kategorien[$name]['monat'] + $monat;
    $kategorien[$name]['anzahl'] = $kategorien[$name]['anzahl'] + 1;
}

$seite = "auswertung";
include 'kopf.php';
?>

<h1>Auswertung</h1>
<p class="grau">So verteilen sich deine Kosten.</p>

<div class="karten">
    <div class="karte">
        <div class="grau">Pro Monat</div>
        <div class="zahl"><?php echo euro($gesamt); ?></div>
    </div>
    <div class="karte">
        <div class="grau">Pro Jahr</div>
        <div class="zahl"><?php echo euro($gesamt * 12); ?></div>
    </div>
    <div class="karte">
        <div class="grau">Kategorien mit Abos</div>
        <div class="zahl"><?php echo count($kategorien); ?></div>
    </div>
</div>

<?php if (count($abos) == 0) { ?>
    <p class="grau">Noch keine Abos vorhanden.</p>
<?php } else { ?>
    <div class="box">
        <table>
            <tr>
                <th>Kategorie</th>
                <th>Abos</th>
                <th>Pro Monat</th>
                <th>Pro Jahr</th>
                <th>Anteil</th>
            </tr>
            <?php foreach ($kategorien as $name => $werte) { ?>
                <?php $anteil = round($werte['monat'] / $gesamt * 100); ?>
                <tr>
                    <td>
                        <span class="punkt" style="background-color: <?php echo htmlspecialchars($werte['farbe']); ?>"></span>
                        <?php echo htmlspecialchars($name); ?>
                    </td>
                    <td><?php echo $werte['anzahl']; ?></td>
                    <td><?php echo euro($werte['monat']); ?></td>
                    <td><?php echo euro($werte['monat'] * 12); ?></td>
                    <td>
                        <div class="balken">
                            <div class="balken-fuellung" style="width: <?php echo $anteil; ?>%; background-color: <?php echo htmlspecialchars($werte['farbe']); ?>"></div>
                        </div>
                        <?php echo $anteil; ?> %
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
<?php } ?>

<?php include 'fuss.php'; ?>