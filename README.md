# MManager
Un semplice gestore delle spese familiari - A simple personal finance manager

## Come si installa
1. Installa apache 2.4, php 7 e mysql (ad esempio architetture WAMP o LAMP);
2. Carica i file nin una cartella (solitamente `mmanager` sotto `www`);
3. Crea il lo schema mysql;
4. Imposta i valori di configurazione del DB nel file `config.php` (server, username, password, database);
5. Collegati su `http://localhost/install.php` e premi il bottone `Installa`;
6. Una volta completata la procedura collegati a `http://localhost/index.php` (la prima connessione è da fare con l'utente `admin/admin`);

## How to install
1. Install apache 2.4, php 7 and mysql (WAMP or LAMP server);
2. Put the files in a folder (usually `mmanager` under `www`);
3. Create the mysql schema;
4. Set the correct value in `config.php` (server, username, password, database);
5. Connect to `http://localhost/install.php` and press the button `Install`;
6. Once completed go to `http://localhost/index.php` and connect with `admin/admin`;

## To do (ITA)
- Impostare i ringraziamenti;
- Creare il wiki;
- Sviluppare le trasazioni ricorenti;
- Sviluppare l'import&export delle transazioni;
- Sviluppare la gestione dei portafogli;
- Sviluppare il controllo di fine anno;
- Sviluppare le spese previste;
- Sviluppare la notifica mail a spesa inserita;
- Sviluppare il caricamento degli scontrini;
- Migliorare la traduzione dell'inglese;

## To do (ENG)
- Set credits;
- Create wiki;
- Insert recursive transaction;
- Import&Export csv (with staging);
- Wallet management;
- End year check;
- Future expenses;
- Mail notify;
- Bills upload;
- Deploy a better english translation;