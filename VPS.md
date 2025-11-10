# 🚀 Panduan Deployment ke VPS - Aplikasi Kuesioner Kesiapan Menikah

Dokumentasi lengkap untuk deploy aplikasi Laravel ini ke VPS (Virtual Private Server).

---

## 📋 Daftar Isi

1. [Persyaratan Server](#persyaratan-server)
2. [Setup Awal VPS](#setup-awal-vps)
3. [Install Dependencies](#install-dependencies)
4. [Setup Database](#setup-database)
5. [Deploy Aplikasi](#deploy-aplikasi)
6. [Konfigurasi Web Server](#konfigurasi-web-server)
7. [Setup SSL (HTTPS)](#setup-ssl-https)
8. [Optimasi & Security](#optimasi--security)
9. [Troubleshooting](#troubleshooting)

---

## 🖥️ Persyaratan Server

### Minimum Requirements:
- **OS**: Ubuntu 20.04 LTS / 22.04 LTS (Recommended)
- **RAM**: Minimum 1GB (2GB+ recommended)
- **Storage**: Minimum 10GB
- **CPU**: 1 Core minimum
- **PHP**: 8.1 atau 8.2
- **Database**: MySQL 8.0+ atau MariaDB 10.3+
- **Web Server**: Nginx atau Apache

---

## 🔧 Setup Awal VPS

### 1. Update System

```bash
sudo apt update
sudo apt upgrade -y
```

### 2. Install Dependencies Dasar

```bash
sudo apt install -y software-properties-common curl wget git unzip
```

### 3. Setup Firewall (UFW)

```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
sudo ufw status
```

---

## 📦 Install Dependencies

### 1. Install PHP 8.2

```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath
```

**Verifikasi:**
```bash
php -v
```

### 2. Install Composer

```bash
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
composer --version
```

### 3. Install Node.js & NPM

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
node -v
npm -v
```

### 4. Install MySQL

```bash
sudo apt install -y mysql-server
sudo mysql_secure_installation
```

**Setup MySQL:**
```bash
sudo mysql
```

```sql
CREATE DATABASE kuisoner CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'kuisoner_user'@'localhost' IDENTIFIED BY 'PASSWORD_YANG_KUAT';
GRANT ALL PRIVILEGES ON kuisoner.* TO 'kuisoner_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Install Nginx

```bash
sudo apt install -y nginx
sudo systemctl start nginx
sudo systemctl enable nginx
```

---

## 🗄️ Setup Database

### 1. Buat Database dan User

```bash
sudo mysql -u root -p
```

```sql
CREATE DATABASE kuisoner CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'kuisoner_user'@'localhost' IDENTIFIED BY 'PASSWORD_YANG_KUAT';
GRANT ALL PRIVILEGES ON kuisoner.* TO 'kuisoner_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 2. Test Koneksi Database

```bash
mysql -u kuisoner_user -p kuisoner
```

---

## 📤 Deploy Aplikasi

### 1. Clone Repository

```bash
cd /var/www
sudo git clone https://github.com/wahyudedik/kuisoner-app.git kuisoner
# atau upload via SCP/SFTP ke /var/www/kuisoner
```

### 2. Set Permissions

```bash
cd /var/www/kuisoner
sudo chown -R www-data:www-data /var/www/kuisoner
sudo chmod -R 755 /var/www/kuisoner
sudo chmod -R 775 /var/www/kuisoner/storage
sudo chmod -R 775 /var/www/kuisoner/bootstrap/cache
```

### 3. Install Dependencies

```bash
cd /var/www/kuisoner
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 4. Setup Environment

```bash
cp .env.example .env
nano .env
```

**Edit `.env` file:**
```env
APP_NAME="Kuesioner Kesiapan Menikah"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kuisoner
DB_USERNAME=kuisoner_user
DB_PASSWORD=PASSWORD_YANG_KUAT

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Migrations & Seeders

```bash
php artisan migrate --force
php artisan db:seed --force
```

### 7. Optimize Laravel

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 🌐 Konfigurasi Web Server

### Nginx Configuration

Buat file konfigurasi Nginx:

```bash
sudo nano /etc/nginx/sites-available/kuisoner
```

**Isi dengan konfigurasi berikut:**

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/kuisoner/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Enable site:**
```bash
sudo ln -s /etc/nginx/sites-available/kuisoner /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Apache Configuration (Alternatif)

Jika menggunakan Apache, buat file:

```bash
sudo nano /etc/apache2/sites-available/kuisoner.conf
```

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/kuisoner/public

    <Directory /var/www/kuisoner/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/kuisoner_error.log
    CustomLog ${APACHE_LOG_DIR}/kuisoner_access.log combined
</VirtualHost>
```

**Enable site:**
```bash
sudo a2ensite kuisoner.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

## 🔒 Setup SSL (HTTPS)

### Menggunakan Certbot (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

**Auto-renewal:**
```bash
sudo certbot renew --dry-run
```

Certbot akan otomatis mengkonfigurasi Nginx untuk HTTPS.

---

## ⚙️ Optimasi & Security

### 1. Setup Queue Worker (Opsional)

Jika menggunakan queue:

```bash
sudo nano /etc/systemd/system/kuisoner-queue.service
```

```ini
[Unit]
Description=Kuisoner Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/kuisoner/artisan queue:work --sleep=3 --tries=3

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl enable kuisoner-queue
sudo systemctl start kuisoner-queue
```

### 2. Setup Cron Job

```bash
sudo crontab -e -u www-data
```

Tambahkan:
```
* * * * * cd /var/www/kuisoner && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Security Hardening

**File Permissions:**
```bash
sudo chmod 644 /var/www/kuisoner/.env
sudo chmod -R 755 /var/www/kuisoner
sudo chmod -R 775 /var/www/kuisoner/storage
sudo chmod -R 775 /var/www/kuisoner/bootstrap/cache
```

**Disable Directory Listing:**
Tambahkan di Nginx config:
```nginx
location ~ /\. {
    deny all;
    access_log off;
    log_not_found off;
}
```

### 4. Setup Firewall

```bash
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

### 5. Optimize PHP-FPM

```bash
sudo nano /etc/php/8.2/fpm/php.ini
```

**Ubah:**
```ini
memory_limit = 256M
upload_max_filesize = 20M
post_max_size = 20M
max_execution_time = 300
```

```bash
sudo systemctl restart php8.2-fpm
```

---

## 🔄 Update Aplikasi

### Script Update

Buat file `update.sh`:

```bash
#!/bin/bash
cd /var/www/kuisoner
git pull origin main
composer install --optimize-autoloader --no-dev
npm install
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
sudo systemctl reload php8.2-fpm
```

**Beri permission:**
```bash
chmod +x update.sh
```

**Jalankan:**
```bash
./update.sh
```

---

## 🐛 Troubleshooting

### 1. Permission Denied

```bash
sudo chown -R www-data:www-data /var/www/kuisoner
sudo chmod -R 755 /var/www/kuisoner
sudo chmod -R 775 /var/www/kuisoner/storage
sudo chmod -R 775 /var/www/kuisoner/bootstrap/cache
```

### 2. 500 Internal Server Error

```bash
# Cek error log
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/php8.2-fpm.log

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 3. Database Connection Error

```bash
# Test koneksi
mysql -u kuisoner_user -p kuisoner

# Cek .env file
cat .env | grep DB_
```

### 4. Assets Tidak Muncul

```bash
# Rebuild assets
npm run build
php artisan optimize:clear
```

### 5. Storage Link

```bash
php artisan storage:link
```

---

## 📝 Checklist Deployment

- [ ] VPS sudah di-setup dengan OS yang sesuai
- [ ] PHP 8.2+ terinstall
- [ ] Composer terinstall
- [ ] Node.js & NPM terinstall
- [ ] MySQL terinstall dan database dibuat
- [ ] Nginx/Apache terinstall dan dikonfigurasi
- [ ] Aplikasi di-clone/upload ke `/var/www/kuisoner`
- [ ] Permissions sudah benar
- [ ] `.env` file sudah dikonfigurasi
- [ ] `APP_KEY` sudah di-generate
- [ ] Migrations sudah dijalankan
- [ ] Seeders sudah dijalankan
- [ ] Dependencies sudah diinstall (`composer install`, `npm install`)
- [ ] Assets sudah di-build (`npm run build`)
- [ ] Laravel sudah di-optimize
- [ ] SSL/HTTPS sudah di-setup
- [ ] Firewall sudah dikonfigurasi
- [ ] Cron job sudah di-setup
- [ ] Queue worker sudah di-setup (jika perlu)

---

## 🔐 Security Best Practices

1. **Gunakan Strong Password** untuk database dan user
2. **Update `.env`** dengan `APP_DEBUG=false` di production
3. **Setup Firewall** (UFW) dengan rules yang tepat
4. **Gunakan HTTPS** dengan SSL certificate
5. **Regular Updates** untuk sistem dan dependencies
6. **Backup Database** secara berkala
7. **Monitor Logs** untuk aktivitas mencurigakan
8. **Limit SSH Access** dengan key-based authentication
9. **Disable Unused Services**
10. **Setup Fail2Ban** untuk proteksi brute force

---

## 📞 Support

Jika mengalami masalah saat deployment, cek:
- Log files: `/var/log/nginx/error.log`, `/var/log/php8.2-fpm.log`
- Laravel logs: `/var/www/kuisoner/storage/logs/laravel.log`
- Database connection: Test dengan `mysql` command
- File permissions: Pastikan `storage` dan `bootstrap/cache` writable

---

**Last Updated**: 2025-11-10
**Version**: 1.0

