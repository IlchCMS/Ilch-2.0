# Vagrant einrichten und benutzen

Vagrant ist ein Tool mit dem es möglich ist, einfach eine virtuelle Maschine einzurichten,
so dass alle Entwickler mit dem gleichen System arbeiten und sich um deren Einrichtung nicht wirklich kümmern müssen.

1. Download und Installieren von [Vagrant](http://www.vagrantup.com) 
2. Download und Installieren von [VirtualBox](https://www.virtualbox.org/wiki/Downloads), wobei auch das Extension Pack installiert werden sollte
3. Kopiere die Datei development/vagrant/Vagrantfile aus dem development Ordner ins Hauptverzeichnis des Projektes
4. Ändere die Datei Vagrantfile entsprechend deinen Wünschen, sie sollte aber auch ohne Änderungen funktionieren
5. Starte eine Konsole (unter Windows möglichst PowerShell verwenden)
6. In das Hauptverzeichnis des Projektes per Shell wechseln
7. Die VM mit __vargrant up__ initialisieren (Dies dauert beim ersten mal eine ganze Weile, da einiges heruntergeladen werden muss)
8. VM kann im Browser über http://localhost:8080 aufgerufen werden, soweit du keinen anderen Port konfiguriert hast

## Benutzung nach Intialisierung

* mit __vagrant up__ kann man die VM starten
* mit __vagrant ssh__ kann man sich auf die VM verbinden
* mit __vagrant suspend__ kann man die VM anhalten (muss nicht neu hochgefahren werden, beim nächsten *vagrant up*)
* mit __vagrant halt__ kann man die VM herunterfahren
* mit __vagrant reload__ kann man die VM neustarten
* Um also Befehle in der VM auszuführen muss diese mit erst gestartet werden, um sich dann darauf verbinden zu können
* Das Ilch Verzeichnis befindet sich auf der VM im Ordner /vagrant

## MySQL
Das Passwort für den root User ist root. Es werden 2 Datenbanken angelegt: ilch2 und ilch2test.
Die Vagrant Box wird so konfiguriert, dass eine Verbindung von außen also beispielsweise mit HeidiSQL möglich ist.
Der Port zur Verbindung ist dann 13306, funktioniert aber wohl erst nach einem *vagrant reload*

## Debugging mit xdebug
Xdebug ist auf der VM mit installiert und für remote debugging konfiguriert, es sollte also in der IDE konfiguriert werden können.

## Verwendung von Tools in der VM
Dafür muss man sich zunächst auf die Box einwählen, dies ist mit __vagrant ssh__ oder unter Windows mit putty möglich,
was etwas mehr Komfort bieten sollte. Bei der Verwendung von putty sind die Parameter:

* host: 127.0.0.1
* port: 2222
* user: vagrant
* password: vagrant
* alternativ zum Verwendung von Benutzer und Passwort kann auch ein Privatekey verwendet werden,
  wo sich dieser befindet kann mit___vagrant ssh-config__ geprüft werden und mit Hilfe von PuttyGen zu einem Putty Key umgewandelt werden

Nachdem man sich eingeloggt hat, sollte man sich im Verzeichnis /vagrant befinden, was zum Root des Ilch-2.0 Verzeichnises
gelinkt ist.
Falls man seine VM noch umkonfigurieren will, sind dazu ggf. root Rechte notwendig, dazu kann sudo verwendet werden.

### PHP CodeSniffer (phpcs)
Da das Scannen aller Dateien sehr lange dauern kann, empfielt es sich auch der Übersichtlichkeit halber, nur einzelne Dateien zu scannen.
phpcs auf der VM verwendet dabei als Standard automatisch PSR2.

Beispiel:
```
phpcs ./application/libraries/Ilch/Date.php
```

### PHP-CS-Fixer (php-cs-fixer)
Um Fehler die mit phpcs gefunden worden automatisch zu beheben, kann php-cs-fixer verwendet werden.
Um Probleme zu vermeiden, falls das Ergebnis nicht der Erwartungen entspricht sollte man ggf. vorher mal die Datei(en)
committen oder stagen, um wieder zum Ursprungszustand zurückkommen zu können.

Beispiele (beide Befehle bewirken dasselbe, phpcsfix ist nutzt also PSR2):
```
phpcsfix ./application/libraries/Ilch/Date.php
php-cs-fixer fix ./application/libraries/Ilch/Date.php --level=psr2
```

### PHPUnit (phpunit)
Die Verwendung von phpunit wird in der tests/README.md beschrieben.
Der Befehl phpunit sollte verfügbar und xdebug konfiguriert sein.


### Hinweis zur Nutzung der Tools unter Windows
Wenn man phpcs oder phpunit auch in seiner IDE unter Windows einbinden will, werden auch die .bat Dateien benötigt.
Leider ist es bei composer noch nicht möglich die bat-Dateien unter Linux zu erstellen.
Wer also die .bat benötigt, muss den composer install Befehl unter Windows ausführen, während kein vendor Verzeichnis existiert.

# Nutzung der Tools ohne Vagrant
Die Nutzung der Tools ist auch ohne Vagrant und VM nutzbar. Dazu kann auch einfach Composer verwendet werden,
um die Tools zu installieren und im Anschluss nutzen zu können. Wie man Composer installiert und verwendet,
kann man auf [getcomposer.org](http://www.getcomposer.org) nachlesen.
