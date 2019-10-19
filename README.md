# Pizzaservice
Der *Pizzaservice* wurde im Praktikum des Moduls *Entwicklung webbasierter Anwendungen* (Development of Web-Based Applications) erstellt.

### Datenbank
Es wurde eine Datenbank mit Hilfe von *XAMPP* und *MariaDB* erstellt. Mit der SQL-Datei [pizzaservice.sql](pizzaservice.sql) kann eine für das Projekt passende Datenbank erstellt werden.

### Funktionsweise
Die Website wird mittels PHP und den Inhalten aus der Datenbank dynamisch aufgebaut.

Der Pizzaservice umfasst eine [Bestellseite](src/bestellung.php), auf der Pizzen mit Hilfe eines Warenkorbs bestellt werden können. Wurden Pizzen bestellt, landen diese auf der Seite des [Bäckers](src/baecker.php), der den aktuellen Status der Pizza anpassen kann. Wurden die Pizzen zubereitet so landen die Pizzen beim [Fahrer](src/fahrer.php), der die Pizzen ausliefert. Sobald dies geschehen ist, ist die Bestellung abgeschlossen und wird nicht mehr angezeigt.

Der Kunde kann immer den aktuellen Status seiner Bestellungen unter [Kunde](src/kunde.php) einsehen. Dies funktioniert mit einer beim Kunden abgelegten SessionID.

Die Website ist gegen *SQL-Injection* und *Cross Site Scripting* geschützt.

Unter [KundenStatus](src/kundenStatus.php) wird der Status der Pizzen mittels AJAX aktualisiert und als JSON bereitgestellt.
