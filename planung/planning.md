# Planung

// http://localhost/Projekt1/PHP-Projekt1/

## Idee
- abo manager, alle abos an einem ort
- kosten pro monat / jahr sehen
- warnung wenn kündigungsfrist bald abläuft

## Datenbank
- db name: abo_manager
- 2 tabellen, kategorien + abos
- 1 kategorie -> viele abos (1:N)

kategorien
- id (int, auto increment)
- name (varchar 50)
- farbe (varchar 7, z.b. #e63946)

abos
- id (int, auto increment)
- kategorie_id -> kategorien.id
- name, anbieter (varchar 100)
- preis (decimal 8,2)
- intervall (monatlich / jaehrlich)
- startdatum (date)
- kuendigungsfrist (date)
- notiz (text, optional)

## Entscheidungen
- nur monatlich + jährlich, sonst zu viel rechnen
- jährlich / 12 = monatskosten
- kategorie mit abos nicht löschbar -> meldung
- ein formular für neu + bearbeiten
- kategorien zuerst bauen, formular braucht die für die auswahl
- nur main branch

## Seiten
- index.php -> übersicht, filter, hinweise
- abo_bearbeiten.php -> formular neu / bearbeiten
- abo_loeschen.php -> löschen, zurück zur übersicht
- kategorien.php -> anzeigen, anlegen, löschen
- auswertung.php -> kosten monat / jahr / kategorie
- verbindung.php -> db verbindung
- kopf.php, fuss.php -> navigation oben, fuß unten
- style.css

## Oberfläche
- oben immer: titel + nav (übersicht, neues abo, kategorien, auswertung)
- übersicht: hinweis oben, dann filter, dann tabelle (name, kategorie, preis, intervall, kündigung bis, bearbeiten, löschen)
- formular: name, anbieter, kategorie dropdown, preis, intervall, startdatum, kündigung bis, notiz, speichern
- kategorien: kleines formular oben (name, farbe), liste darunter
- auswertung: gesamt monat + jahr, darunter je kategorie
- fehler in rot über dem formular

## Eingaben prüfen
- pflicht: name, anbieter, kategorie, preis, intervall, startdatum
- preis = zahl > 0
- datum gültig
- bei fehler nichts speichern

## Reihenfolge
1. datenbank + grundgerüst
2. kategorien
3. abos
4. auswertung + hinweise
5. filter + css

## Wenn zeit bleibt
- suche, sortierung
- kategorien umbenennen