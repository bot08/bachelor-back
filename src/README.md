Apache2 + PHP + SQLite3

#### Something like this:

sudo apt updte

sudo apt install apache2 php libapache2-mod-php php-sqlite3 sqlite3
- ? php-cli php-xml php-zip php-pdo

sudo a2enmod rewrite

sudo systemctl restart apache2

In:
/etc/apache2/sites-available/default.conf
/etc/apache2/sites-available/000-default.conf

Paste:
<Directory /var/www/>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>

service apache2 restart