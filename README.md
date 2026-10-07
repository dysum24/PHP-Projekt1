# Abo-Manager

Eine Webanwendung zur Verwaltung von Abonnements mit PHP und MySQL.

Schulprojekt P1 von Dylan Sumner, Klasse FIA 12

## Beschreibung

Mit dem Abo-Manager lassen sich alle laufenden Abonnements an einem Ort verwalten. Die Anwendung zeigt die Kosten pro Monat und pro Jahr und weist auf Abos hin, deren Kündigungsfrist bald abläuft.

## Funktionen

- Abos anlegen, bearbeiten und löschen
- Kategorien anlegen und löschen
- Übersicht aller Abos mit Filter nach Kategorie
- Kostenübersicht pro Monat, pro Jahr und je Kategorie
- Hinweis auf Abos, deren Kündigungsfrist in den nächsten 30 Tagen abläuft

## Technik

- PHP
- MySQL
- HTML und CSS
- XAMPP

## Dateien

- index.php: Übersicht aller Abos
- abo_formular.php: Abo anlegen und bearbeiten
- abo_loeschen.php: Abo löschen
- kategorien.php: Kategorien anlegen und löschen
- kosten.php: Kostenübersicht
- db.php: Verbindung zur Datenbank
- header.php und footer.php: Seitenkopf mit Navigation und Seitenfuß
- style.css: Gestaltung
- datenbank.sql: Export der Datenbank
- docs: Projektantrag und Planung

## Installation

1. XAMPP starten (Apache und MySQL).
2. Den Ordner PHP-Projekt1 in den Ordner htdocs von XAMPP kopieren.
3. In phpMyAdmin die Datenbank abo_manager anlegen und die Datei datenbank.sql importieren.
4. Im Browser http://localhost/PHP-Projekt1 öffnen.