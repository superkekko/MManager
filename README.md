# MManager
Un semplice gestore delle spese famigliari

## Come si installa
1. Installa apache 2.4, php 7 e mysql (ad esempio architetture WAMP o LAMP);
2. Carica i file nin una cartella (solitamente `mmanager` sotto `www`);
3. Crea il lo schema mysql;
4. Imposta i valori di configurazione del DB nel file `config.php` (server, username, password, database);
5. Collegati sul DB e lanciare gli sql da install.sql a tutte le update presenti nella cartella install-update
6. Una volta completata la procedura collegati a `http://localhost/index.php` (la prima connessione è da fare con l'utente `admin/admin`);

## Come si aggiorna
- Per aggiornare la versione web scaricare la release più recente;
- Per aggiornare il db ainstallare gli sql mancanti dalla cartella install-update.

## Thanks to
- Font Awesome Free 5.10.2 (https://fontawesome.com)
- Tabulator 4.7.2 (http://tabulator.info/)
- SB Admin 2 4.0.7 (https://startbootstrap.com/theme/sb-admin-2)
- Bootstrap 4.3.1 (https://getbootstrap.com/)
- Chart.js 2.8.0 (https://www.chartjs.org)
- jQuery 3.5.1 (https://jquery.com/)
- jQuery Easing 1.4.1 (http://gsgd.co.uk/sandbox/jquery/easing/)
- moment.js 2.28.0 (http://momentjs.com)
