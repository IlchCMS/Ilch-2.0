# Vagrant einrichten und benutzen

Vagrant ist ein Tool mit dem es möglich ist, einfach eine virtuelle Maschine einzurichten,
so dass alle Entwickler mit dem gleichen System arbeiten und sich um deren Einrichtung nicht wirklich kümmern müssen.

1. Download und Installieren von [Vagrant](http://www.vagrantup.com) 
2. Download und Installieren von [VirtualBox](https://www.virtualbox.org/wiki/Downloads), wobei auch das Extension Pack installiert werden sollte
3. Kopiere die Datei Vagrantfile aus dem development Ordner ins Hauptverzeichnis des Projektes
4. Ändere die Datei Vagrantfile entsprechend deinen Wünschen, sie sollte aber auch ohne Änderungen funktionieren
5. Starte eine Konsole (unter Windows möglichst PowerShell verwenden)
6. Die VM mit __vargrant up__ initialisieren
7. VM kann im Browser über http://localhost:8080 aufgerufen werden, soweit du keinen anderen Port konfiguriert hast

Benutzung nach Intialisierung

* mit __vagrant up__ kann man die VM starten
* mit __vagrant ssh__ kann man sich auf die VM verbinden
* mit __vagrant suspend__ kann man die VM anhalten (muss nicht neu hochgefahren werden, beim nächsten *vagrant up*)
* mit __vagrant halt__ kann man die VM herunterfahren
* Um also Befehle in der VM auszuführen muss diese mit erst gestartet werden, um sich dann darauf verbinden zu können
* Das Ilch Verzeichnis befindet sich auf der VM im Ordner /vagrant