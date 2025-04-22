# FlowFinder – Installation Guide

FlowFinder is a self-hosted open-source tool. This guide explains how to install and run the project locally.

---

## 1. Requirements

- **PHP** ≥ 8.2  
- **MariaDB/MySQL** (tested with MariaDB 10.6)  
- PHP extensions: `pdo`, `mbstring`, `json`, etc.  
- **Apache web server** with `mod_rewrite` enabled  
- **Composer** (PHP dependency manager)

### Installing Composer

- **Windows**:  
  👉 https://getcomposer.org/Composer-Setup.exe

- **macOS** (via Homebrew):  
  ```bash
  brew install composer
  ```

- **Linux (Debian/Ubuntu)**:  
  ```bash
  sudo apt install composer
  ```

More info: https://getcomposer.org

---

## 2. Clone the repository

```bash
git clone https://github.com/flowfinder-org/flowfinder.git
cd flowfinder
```

---

## 3. Database setup

1. **Create the database and dev user**:  
   Run:  
   ```
   _database/_create_schema_for_dev.sql
   ```

2. **Create the tables**:  
   Run:  
   ```
   _database/schema_version_00001.sql
   ```

---

## 4. Apache configuration

The Apache `DocumentRoot` must point to the `/public` folder, but `.htaccess` should apply from the project root (`./`).

### Example VirtualHost config (XAMPP on Windows):

```apache
<VirtualHost *:80>  
    ServerName localhost  
    DocumentRoot "C:\Users\[USERNAME]\Git\github.com\flowfinder-org\flowfinder\public"

    <Directory "C:\Users\[USERNAME]\Git\github.com\flowfinder-org\flowfinder">  
        Options Indexes FollowSymLinks MultiViews  
        AllowOverride all  
        Order Deny,Allow  
        Allow from all  
        Require all granted  
    </Directory>  
</VirtualHost>
```

---

## 5. Install dependencies

Run this from the root of the project:

```bash
composer install
```

---

## 6. Run & access

After setting up your virtual host and restarting Apache, visit:

```
http://localhost
```

---

## 7. Optional services

FlowFinder.org also offers **professional cloud versions** and **technical support services**.
