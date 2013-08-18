<?PHP
/***************************************************************************
 *                               constants_it.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton - Fast Track Sites
 *   email                : sales@fasttacksites.com
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 * This program is licensed under the Fast Track Sites Program license 
 * located inside the license.txt file included with this program. This is a 
 * legally binding license, and is protected by all applicable laws, by 
 * editing this page you fall subject to these licensing terms.
 *
 ***************************************************************************/

//============================
// Text values in Italian
//============================
/*Globally Used Values*/
	/*~~Navigation~~*/
		$T_HOME = "Domestico";
		$T_REGISTER = "Registro";
		$T_LOGIN = "Inizio attività";
		$T_LOGOUT = "Termine attività";
		$T_MY_TICKETS = "I Miei Biglietti";
		$T_ADMIN_PANEL = "Pannello Di Admin";
		$T_USER_ADMINISTRATION = "Gestione Dell'Utente";
		$T_CALL_HOME = "Chiamata Domestica";
		$T_CONFIGURATION = "Configurazione";
		$T_THEMES = "Temi";
	
	/*~~General~~*/
		$T_DISABLED = "Attualmente Inabilitato";
		$T_WELCOME_MESSAGE = "Il benvenuto alla pista veloce situa il sistema del biglietto di difficoltà. Prego inizio attività da cominciare a usando il sistema.";
		$T_PLEASE = "Per favore";
		$T_ALL = "Tutti";
		$T_TICKETS = "Biglietti";
		$T_DISPLAY = "Esposizione";
	
	/*~~Logins, Joining, and User Administration~~*/
		$T_USERNAME = "Username";
		$T_PASSWORD = "Parola d'accesso";
		$T_CONFIRM_PASSWORD = "Confermi La Parola d'accesso";
		$T_NEW_PASSWORD = "Nuova Parola d'accesso";
		$T_CONFIRM_NEW_PASSWORD = "Confermi Nuova Parola d'accesso";
		$T_EMAIL_ADDRESS = "Email Address";
		$T_WEBSITE = "Website";
		$T_USER_LEVEL = "Livello Di Accesso";
		$T_ADD_USER = "Aggiunga un nuovo utente";
		$T_EDIT_USER = "Pubblichi un utente corrente";
		$T_DELETE_USER = "Cancelli un utente corrente";
	
	/*~~Ticket Tables~~*/
		$T_OPEN = "Aperto";
		$T_CLOSED = "Chiuso";
		$T_NEW_TROUBLE_TICKET = "Nuovo Biglietto Di Difficoltà";
		$T_CHANGE = "Chg";
		$T_DELETE = "Del";
		$T_TECHNICIAN = "Tecnico";
		$T_USER = "Utente";
		$T_STATUS = "Condizione";
		$T_TITLE = "Titolo";
		$T_CATEGORY = "Categoria";
		$T_DATE_CREATED = "Data Generata";
	
	/*~~Ticket Forms~~*/
		$T_TICKET_ID = "ID";
		$T_NAME = "Nome";
		$T_TICKET_TITLE = "Titolo Del Biglietto";
		$T_PHONE_NUMBER = "Numero di telefono";
		$T_MODEL_NAME = "Nome Di modello";
		$T_SERIAL_NUMBER = "Numero di serie";
		$T_PROBLEM_CATEGORY = "Categoria Di Problema";
		$T_CREATE_TICKET = "Generi Il Biglietto";
	
	/*~~Errors~~*/
		$T_NOT_AUTHORIZED = "Non siete autorizzati ad osservare questa sezione.";
		$T_ENTER_USERNAME = "Introduca prego il nome dell'utente per quale state generando il biglietto.";
		$T_ENTER_TITLE = "Fornisca prego un titolo per il biglietto che state generando.";
		$T_ENTER_PHONE_NUMBER = "Entri prego nel vostro numero di telefono.";
		$T_ENTER_MODEL_NAME = "Introduca Prego Il Vostro Nome Di modello.";
		$T_ENTER_SERIAL_NUMBER = "Entri prego nel numero di serie trovato sulla parte inferiore del vostro prodotto.";
		$T_ENTER_PROBLEM = "Descriva prego dettagliatamente il vostro problema all'interno della scatola fornita.";
		$T_NO_TICKET_FOUND = "Nessun biglietto è stato trovato. Generi prego uno e provi ancora.";
		$T_REQUIRED_USERNAME = "Il username voluto è un campo richiesto. Prego prova ancora.";
		$T_TAKEN_USERNAME = "Il username che avete selezionato già è stato usato da un altro membro nella nostra base di dati. Scelga prego un username differente!";
		$T_TAKEN_EMAIL_ADDRESS = "Il email address che avete selezionato già è stato usato da un altro membro nella nostra base di dati.";
		$T_FIX_ERRORS = "Riparano prego i seguenti errori:";
		$T_CREATION_ERROR = "là ha stato un errore che genera il vostro cliente. Mettasi in contatto con prego il webmaster.";
		$T_PASSWORD_POLICY = "la vostra parola d'accesso deve essere più di 6 caratteri e non essere \'password\' o lo stesso del vostro username";
		$T_PASSWORDS_DONT_MATCH = "Le vostre parole d'accesso non abbinano, soddisfare re-enter loro.";
		$T_ENTER_ALL_INFO = "Fornisca prego TUTTE LE informazioni richieste! <br />";
		$T_COULD_NOT_LOGIN = "Non potreste essere entrati! O il username e la parola d'accesso non abbinano o non avete convalidato il vostro insieme dei membri!";
		$T_TRY_AGAIN = "Prego prova ancora!";
		$T_ADD_USER_ERROR = "Ci era un errore mentre generava il vostro nuovo utente. State riorientandi alla pagina principale.";
		$T_EDIT_USER_ERROR = "Ci era un errore mentre accedeva ai particolari che dell'utente state provando ad aggiornare. State riorientandi alla pagina principale.";
		$T_ADD_PCAT_ERROR = "Ci era un errore mentre generava la vostra nuova categoria di problema. State riorientandi alla pagina principale.";
		$T_EDIT_PCAT_ERROR = "Ci era un errore mentre accedeva alla categoria che di problema state provando ad aggiornare. State riorientandi alla pagina principale.";
		$T_INSERTION_ERROR = "ERRORE: La domanda di SQL da inserire è venuto a mancare";
		$T_DELETION_ERROR = "ERRORE: La domanda di SQL da cancellare è venuto a mancare";
		$T_UPDATE_ERROR = "There was an error while updating your user's details. You are being redirected to the main page.";

/*Values for admin.php*/
	$T_UPDATE = "Aggiornamento";
	$T_SEARCH = "Biglietti Di Ricerca";
	$T_TICKET_NUMBER = "Biglietto #";
	$T_SEARCH_WARNING = "Cerchi la base di dati da Username, dal numero del biglietto, O dal nome di tecnologia non tutti e tre le!";
	$T_SEARCH = "Ricerca";
	$T_VIEW_ALL_TICKETS = "Osservi Tutti i Biglietti";
	$T_VIEW_ALL_OPEN_TICKETS = "Biglietti Aperti Di Vista";
	$T_VIEW_ALL_CLOSED_TICKETS = "Biglietti Chiusi Di Vista";

/*Values for config.php*/
	$T_TTS_SETTINGS = "Sistema Settigns Del Biglietto Di Difficoltà";
	$T_ACTIVE = "Attivo";
	$T_INACTIVE_MSG = "Messaggio Inattivo";
	$T_COOKIE_NAME = "Nome Del Biscotto";
	$T_TTS_DEFAULT_LANGUAGE = "Lingua Di Difetto";
	$T_ADMIN_EMAIL = "Email Di Admin";
	$T_SETTINGS_UPDATE_BUTTON = "Regolazioni Dell'Aggiornamento";
	$T_CHANGE_LANGUAGE = "Cambi La Lingua";
	
	$T_ADD_PCAT_SUCCESS = "La vostra nuova categoria di problema è stata aggiunta e state riorientandi alla pagina principale.";
	$T_EDIT_PCAT_SUCCESS = "La vostra categoria di problema è stata aggiornata e state riorientandi alla pagina principale.";
	$T_ADD_PCAT = "Generi La Categoria Di Problema";
	$T_EDIT_PCAT = "Pubblichi La Categoria Di Problema";
	$T_DELETE_PCAT = "Categoria Di Problema Di Cancellazione";
	$T_ADD_PCAT_BUTTON = "Generi La Categoria Di Problema";
	$T_EDIT_PCAT_BUTTON = "Cambilo!";
	$T_PROBLEM_CATEGORY = "Categorie Di Problema";
	$T_PROBLEM_CATEGORIES = "Categorie Di Problema";
	$T_PROBLEM_CATEGORY_NAME = "Nome Di Categoria";
	$T_CHANGE_LANGUAGE = "Cambi La Lingua";
	
/*Values for footer.php*/
	$T_POWERED_BY = "Alimentato Vicino";
	$T_FTSTTS = "Fast Track Sites Trouble Ticket System";
	$T_COPYRIGHT = "Copyright";
	$T_FTS = "Fast Track Sites";
	
/*Values for header.php*/
	$T_WELCOME_BACK = "Benvenuto Indietro";
	$T_WELCOME_GUEST = "Ospite Benvenuto";
	
/*Values for index.php*/
	$T_CURRENT_TICKETS = "Biglietti Correnti";
	
/*Values for join.php*/
	$T_New_Account  = "Create a New Account";
	$T_Desired_Username = "Desired Username";
	$T_FULL_NAME = "Full Name";
	$T_WELCOME = "Welcome";
	$T_THANK_YOU_FOR_REGISTERING = "Grazie per registrare!";
	$T_LOGIN_WITH_FOLLOWING = "Ora potete inizio attività con le seguenti informazioni:";
	$T_CREATE_ACCOUNT = "Generi Il Cliente";
	$T_CHECKER_IN_USE = "[In Uso]";
	$T_CHECKER_GOOD = "[Buon]";

/*Values for login.php*/
	$T_NOW_LOGGED_IN_MSG = "Ora siete entrati As";
	$T_AND_BEING_REDIRECTED_MAIN = "e stanno riorientandi alla pagina principale.";
	$T_STAY_LOGGED_IN = "Soggiorno entrato";
	
/*Values for post.php*/
	$T_REPLY_ADDED = "La vostra risposta è stata inviata e state riorientandi al biglietto.";
	$T_TICKET_CREATED = "Il vostro biglietto è stato generato e state riorientandi ad esso.";
	$T_STATUS_CHANGED_OPEN = "Il biglietto è stato riaperto e state riorientandi ad esso.";
	$T_STATUS_CHANGED_CLOSED = "Il biglietto è stato chiuso e state riorientandi ad esso.";
	
/*Values for users.php*/
	$T_ADD_USER_SUCCESS = "Il vostro nuovo utente è stato aggiunto e state riorientandi alla pagina principale.";
	$T_EDIT_USER_SUCCESS = "Particolari del vostro utente sono stati aggiornati e state riorientandi alla pagina principale.";
	$T_ADD_USER_BUTTON = "Generi L'Utente";
	$T_EDIT_USER_BUTTON = "Cambilo!";
	$T_CURRENT_USERS = "Utenti Correnti";
	
/*Values for themes.php*/
	$T_CHANGE_THEME_SUCCESS = "Il vostro tema è stato cambiato con successo.";
	$T_CHANGE_THEME_ERROR = "là era un errore mentre tentava di cambiare il vostro tema.";
	$T_PREVIEW = "Previsione";
	$T_AUTHOR = "Autore";
	$T_THEMES_UPDATE_BUTTON = "Cambiamento Esso!";

/*Values for viewticket.php*/
	$T_VIEW_TICKET = "Biglietto di Vista";
	$T_TICKET_ENTRIES = "Entrate Del Biglietto";
	$T_NO_TICKET_FOUND_BY_ID = "Nessun biglietto è stato trovato tramite questa identificazione. Prego prova ancora";
	$T_NO_TICKET_DATA_FOUND = "Ci era un errore mentre tentava di ottenere il contenuto del vostro biglietto, prego si mette in contatto con un coordinatore a sales@fasttracksites.com";
	$T_UPDATE_TICKET = "Biglietto Dell'Aggiornamento";
	$T_REPLY = "Risposta";
	$T_SUBMIT_REPLY = "Presenti La Risposta";

?>