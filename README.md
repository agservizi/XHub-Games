# 🎮 Xbox Games Catalog

Una web app moderna e responsive per gestire la tua collezione di giochi Xbox Series X|S con un design ispirato al mondo gaming Xbox.

![Xbox Green](https://img.shields.io/badge/Xbox-107C10?style=for-the-badge&logo=xbox&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

## ✨ Caratteristiche

### 🎯 Funzionalità Principali

- **Gestione Completa**: Aggiungi, modifica, visualizza ed elimina giochi con validazione avanzata
- **Filtri Avanzati**: Ricerca per genere, stato, tipo di supporto e testo libero
- **Design Xbox**: Palette colori ufficiale Xbox con effetti glow e neon
- **Responsive**: Ottimizzato per desktop, tablet e mobile con Mobile-First approach
- **Statistiche**: Dashboard con statistiche della collezione
- **Validazione Form**: Sistema di validazione completo con feedback in tempo reale
- **Auto-Save**: Salvataggio automatico della bozza durante la compilazione
- **Importazione Dati**: Database di giochi Xbox popolari per auto-completamento
- **Gestione Immagini**: Preview immagini, placeholder e gestione errori
- **Messaggi Flash**: Sistema di notifiche per successo/errore

### 🛠️ Nuove Funzionalità Avanzate

- **FormValidator**: Sistema di validazione robusto con sanitizzazione dati
- **ImageUtils**: Gestione immagini con placeholder Xbox-style e lazy loading
- **MessageHelper**: Sistema di messaggi flash per feedback utente
- **GameDataImporter**: Database giochi popolari e sistema di import/export CSV
- **Form Enhancement**: JavaScript per UX migliorata (rating slider, preview, auto-save)
- **Environment Config**: Configurazione tramite file .env per produzione
- **Setup Utilities**: Script di setup e migrazione database automatizzati

### 📊 Campi Gioco

- Titolo e copertina (URL)
- Anno di uscita e genere
- Sviluppatore e publisher
- Lingua e tipo di supporto (fisico/digitale)
- Stato di completamento
- Voto personale (0-10)
- Note personali

### 🎨 Design

- **Colori**: Verde Xbox (#107C10), nero (#181A1B), grigio scuro (#1F1F1F)
- **Effetti**: Glow e neon sui pulsanti, hover animations
- **Typography**: Font gaming moderni (Segoe UI, Roboto)
- **Layout**: Card-based design con griglie responsive

## 🚀 Installazione

### Prerequisiti

- PHP 7.4+ con PDO MySQL
- MySQL 5.7+ o MariaDB
- Web server (Apache, Nginx, o PHP built-in server)

### Setup Rapido

1. **Clona il progetto**

   ```bash
   git clone <repository-url>
   cd xbox-games-catalog
   ```

2. **Configura il database**

   - Crea un file `.env` nella root del progetto:

   ```env
   # Database Configuration
   DB_HOST=127.0.0.1
   DB_NAME=your_database_name
   DB_USER=your_username
   DB_PASS=your_password
   DB_PORT=3306

   # Application Configuration
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=http://localhost:8000
   ```

3. **Setup automatico del database**

   ```bash
   # Avvia il server di sviluppo
   php -S localhost:8000

   # Vai su http://localhost:8000/setup.php
   # Usa l'interfaccia web per:
   # - Testare la connessione database
   # - Creare automaticamente le tabelle
   # - Importare dati di esempio
   ```

4. **Alternativa: Setup manuale**

   ```bash
   # Crea il database MySQL
   mysql -u root -p
   CREATE DATABASE your_database_name;

   # Importa lo schema
   mysql -u root -p your_database_name < database/init.sql
   ```

5. **Avvia il server**

   ```bash
   php -S localhost:8000
   ```

6. **Accedi all'app**
   - Apri il browser su `http://localhost:8000`

## 📁 Struttura del Progetto

```
xbox-games-catalog/
├── 📄 index.php              # Entry point
├── 📄 .env                   # Variabili ambiente (da creare)
├── 📄 setup.php              # Setup guidato database
├── 📄 migrate.php            # Script migrazione automatica
├── 📄 system_test.php        # Test completo sistema
├── 📁 config/
│   └── database.php          # Configurazione DB con .env
├── 📁 database/
│   └── init.sql             # Schema e dati iniziali
├── 📁 src/
│   ├── Router.php           # Sistema di routing
│   ├── 📁 controllers/
│   │   └── GameController.php # Controller con validazione
│   ├── 📁 models/
│   │   └── Game.php
│   ├── 📁 utils/            # Utility classes (NEW)
│   │   ├── FormValidator.php      # Sistema validazione
│   │   ├── MessageHelper.php      # Messaggi flash
│   │   ├── ImageUtils.php         # Gestione immagini
│   │   └── GameDataImporter.php   # Import/export dati
│   └── 📁 views/
│       ├── layout.php       # Layout principale
│       ├── 404.php         # Pagina errore
│       └── 📁 games/
│           ├── index.php    # Lista giochi
│           ├── show.php     # Dettagli gioco
│           ├── create.php   # Aggiungi gioco (con validazione)
│           └── edit.php     # Modifica gioco (con validazione)
└── 📁 public/
    └── 📁 assets/          # CSS, JS, immagini
        ├── 📁 css/
        │   └── xbox-gaming.css
        ├── 📁 js/
        │   ├── xbox-gaming.js
        │   └── form-enhancer.js    # Enhancement UX (NEW)
        └── 📁 images/
```

## 🛠️ Tecnologie Utilizzate

- **Backend**: PHP 7.4+ con architettura MVC
- **Database**: MySQL con PDO per la sicurezza
- **Frontend**: HTML5, TailwindCSS, Vanilla JavaScript
- **Design**: Responsive design mobile-first
- **Sicurezza**: Prepared statements, input validation, XSS protection

## 🎮 Utilizzo

### Aggiungere un Gioco

1. Clicca su "➕ Aggiungi Gioco"
2. Compila i campi obbligatori (titolo, genere, sviluppatore)
3. Aggiungi URL copertina, voto e note (opzionali)
4. Salva il gioco

### Gestire la Collezione

- **Filtra** per genere, stato o tipo di supporto
- **Cerca** per titolo, sviluppatore o publisher
- **Visualizza** statistiche della collezione
- **Modifica** o elimina giochi esistenti

### Statistiche Dashboard

- Numero totale di giochi
- Giochi completati, in corso, da iniziare
- Voto medio della collezione

## 🔧 Configurazione

### Database

Modifica `config/database.php`:

```php
private $host = 'localhost';        // Host MySQL
private $db_name = 'xbox_games_catalog';  // Nome database
private $username = 'root';         // Username MySQL
private $password = '';             // Password MySQL
```

### Personalizzazione Colori

I colori Xbox sono configurati in `src/views/layout.php`:

```javascript
colors: {
    'xbox-green': '#107C10',        // Verde Xbox principale
    'xbox-green-light': '#13A10E',  // Verde chiaro
    'xbox-green-dark': '#0E7A0D',   // Verde scuro
    'xbox-dark': '#181A1B',         // Sfondo principale
    'xbox-dark-light': '#1F1F1F',   // Sfondo secondario
}
```

## 📱 Responsive Design

L'app è ottimizzata per:

- **Desktop** (1024px+): Layout a 4 colonne
- **Tablet** (768px-1023px): Layout a 2-3 colonne
- **Mobile** (320px-767px): Layout a 1 colonna con menu mobile

## 🔒 Sicurezza

- **SQL Injection**: Prevenuta con prepared statements PDO
- **XSS**: Input sanitizzato con `htmlspecialchars()`
- **CSRF**: Sessioni PHP per la gestione dello stato
- **Validation**: Validazione lato server e client

## 🎯 Roadmap Future

- [ ] Upload immagini copertina
- [ ] Sistema di backup/export
- [ ] API REST per integrazione esterna
- [ ] Tema personalizzabile
- [ ] Sistema di wishlist
- [ ] Integrazione Xbox Live API
- [ ] PWA (Progressive Web App)

## 📄 Licenza

Questo progetto è rilasciato sotto licenza MIT. Vedi il file LICENSE per maggiori dettagli.

## 🤝 Contributi

I contributi sono benvenuti! Per contribuire:

1. Fork del progetto
2. Crea un branch per la feature (`git checkout -b feature/AmazingFeature`)
3. Commit delle modifiche (`git commit -m 'Add some AmazingFeature'`)
4. Push al branch (`git push origin feature/AmazingFeature`)
5. Apri una Pull Request

## 📞 Supporto

Per bug reports, feature requests o domande:

- Apri un issue su GitHub
- Contatta via email: [your-email@example.com]

---

<div align="center">

**Creato con ❤️ per la community Xbox Gaming**

![Xbox Controller](https://img.shields.io/badge/Made_for-Xbox_Series_X|S-107C10?style=for-the-badge&logo=xbox&logoColor=white)

</div>
