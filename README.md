# FortBase

## Online Zugriff bis 18.06.2025

Über den Link http://benlotharhoffma.bplaced.net/?page=Login ist bis zum 18.06.2025 ein Server Verfügbar und es wird keine lokale installation benötigt.

Für eine Anmeldung mit Berechtigungen für die Nutzung der Seite, bitte die folgenden Daten verwenden:

Benutzername: tester
passwort: 123

## Projekt lokal aufsetzen

### Für Windows

Installieren Sie **XAMPP** für Windows um einfach einen Apache2 und MySQL Server starten zu können. 

#### Frontend & Backend

Nach erfolgreicher Installation, starten Sie die Anwendung und klciken auf **Explorer**. Navigieren Sie dann zum Ordner **htdocs** und fügen sie dort den Ordner **www** aus dem Repository ein. 

Zum starten der Web-Applikation müssen Sie in XAMPP den Apache Server mit dem Button "**starten**" starten. Dann können Sie über einen Webbrowser die Web-Applikation über http://127.0.0.1/ erreichen

#### Datenbank 

Um die Datenbank aufzusetzen, starten Sie den MySQL Server über XAMPP und klicken Sie auf den Button **Admin**. Dort erstellen Sie eine neuen Datenbank und importieren die Datei **FortiBase.sql** aus dem Repository. Dabei ist nur wichtig, das Format SQL beim Import auszuwählen.
