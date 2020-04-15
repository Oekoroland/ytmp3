# PHP YouTube to MP3 converter
Der PHP YouTube to MP3 converter ist in der Lage YouTube Videos in MP3-Dateien umzuwandeln, die anschließend heruntergeladen werden können. 

## Systemvoraussetzungen
- Linux Workstation (min. 1GB RAM, 1 Kern & 5GB Speicher)
- [youtube-dl](https://github.com/ytdl-org/youtube-dl) in $PATH
- cookies.txt (exportierte Cookies von YouTube als Textdatei)
- funktionierender Webserver (Caddy bevorzugt)
- Lese- und Schreibrechte im ".cache" Ordner des Homeverzeichnis' des PHP- und Webserverusers (wichtig für youtube-dl bei mehreren queued downloads)
- < php7.0-fpm

Getestet unter Debian 10 amd64 mit PHP7.4-fpm und Caddy Web Server v2.

## Installation

```sh
$ cd /folder/to/www-path
$ git clone https://github.com/Oekoroland/ytmp3.git
$ chmod -R 755 ytmp3/ && chown -R www-data:www-data ytmp3/
```
In den PHP-Dateien `convert.php` und `download.php` muss dann noch der Download-Ordner Pfad in der Variable _$videoDownloadVerzeichnis_ angepasst werden (**/PATH/TO/DOWNLOADS**).
Der Downloads-Ordner, in dem alle heruntergeladenen Dateien von youtube-dl gespeichert werden, sollte am besten außerhalb des www-paths liegen, damit keine Dateien direkt verlinkt und heruntergeladen werden können (Hotlink-Protection). Daher wird ein neuer Ordner außerhalb des Webpfades angelegt mit entsprechenden Berechtigungen. Hierhin sollte auch die cookies.txt abgelegt werden.
```sh
$ mkdir /path/to/downloads
$ chmod -R 755 /path/to/downloads/ && chown -R www-data:www-data /path/to/downloads/
```
Ein Cronjob löscht im Anschluss alle Ordner, die älter als einen Tag sind. Er wird jede Stunde ausgeführt.
```sh
$ crontab -e
$ 0 * * * * find /path/to/downloads/* ! -name 'cookies.txt' -mtime +1 -type d -exec rm -rf {} +
```

## About
Made by [Jan](https://github.com/oekoroland) & [Henrik](https://github.com/henrocker).
