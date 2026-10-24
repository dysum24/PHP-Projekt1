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
    if ($abo['preis'] > 999999) {
        $fehler[] = "Der Preis ist zu hoch.";
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
    <?php
    if ($abo['id'] == "") {
        echo "Neues Abo";
    } else {
        echo "Abo bearbeiten";
    }
    ?>
</h1>

<?php if (count($fehler) > 0) { ?>
    <div class="meldung">
        <?php foreach ($fehler as $text) { ?>
            <p><?php echo $text; ?></p>
        <?php } ?>
    </div>
<?php } ?>

<form class="formular" method="post" action="abo_bearbeiten.php">
    <input type="hidden" name="id" value="<?php echo $abo['id']; ?>">

    <label>Name</label>
    <input class="feld" type="text" name="name" maxlength="100" value="<?php echo htmlspecialchars($abo['name']); ?>">

    <label>Anbieter</label>
    <input class="feld" type="text" name="anbieter" maxlength="100" value="<?php echo htmlspecialchars($abo['anbieter']); ?>">

    <label>Kategorie</label>
    <select class="feld" name="kategorie_id">
        <option value="">Bitte wählen</option>
        <?php foreach ($kategorien as $kategorie) { ?>
            <option value="<?php echo $kategorie['id']; ?>" <?php if ($kategorie['id'] == $abo['kategorie_id']) { echo "selected"; } ?>>
                <?php echo htmlspecialchars($kategorie['name']); ?>
            </option>
        <?php } ?>
    </select>

    <label>Preis in €</label>
    <input class="feld" type="number" name="preis" step="0.01" min="0" value="<?php echo $abo['preis']; ?>">

    <label>Intervall</label>
    <select class="feld" name="intervall">
        <option value="monatlich" <?php if ($abo['intervall'] == "monatlich") { echo "selected"; } ?>>monatlich</option>
        <option value="jährlich" <?php if ($abo['intervall'] == "jährlich") { echo "selected"; } ?>>jährlich</option>
    </select>

    <label>Startdatum</label>
    <input class="feld" type="date" name="startdatum" value="<?php echo $abo['startdatum']; ?>">

    <label>Kündigung bis</label>
    <input class="feld" type="date" name="kuendigungsfrist" value="<?php echo $abo['kuendigungsfrist']; ?>">

    <label>Notiz</label>
    <textarea class="feld" name="notiz"><?php echo htmlspecialchars($abo['notiz']); ?></textarea>

    <div class="knoepfe">
        <button class="knopf-weiss" type="submit">Speichern</button>
        <a class="knopf-rand" href="index.php">Abbrechen</a>
    </div>
</form>

<?php include 'fuss.php'; ?>
