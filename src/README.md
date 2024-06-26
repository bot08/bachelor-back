Apache2 + PHP + SQLite3

#### Something like this:

```
sudo apt updte
```
```
sudo apt install apache2 php libapache2-mod-php php-sqlite3 sqlite3
```
- ? php-cli php-xml php-zip php-pdo
```
sudo a2enmod rewrite
```
```
sudo systemctl restart apache2
```
In:
/etc/apache2/sites-available/default.conf\
/etc/apache2/sites-available/000-default.conf

Paste:
```
<Directory /var/www/>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```
```
service apache2 restart
```

#### Dockerfile:
```
FROM php:7.4-apache

RUN apt-get update \
    && apt-get install -y \
		wget zip unzip \
        libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        sqlite3 libsqlite3-dev \
        libssl-dev \
    && pecl install mongodb \
    && pecl install redis \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) iconv gd pdo zip opcache pdo_sqlite \
    && a2enmod rewrite expires

RUN echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini
RUN echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

RUN chown -R www-data:www-data /var/www/html

VOLUME /var/www/html

CMD ["apache2-foreground"]
```