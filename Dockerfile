# 1. On part d'une base officielle PHP avec le serveur web Apache
FROM php:8.4-apache

# 2. On active l'URL Rewriting (indispensable pour les routes Symfony)
RUN a2enmod rewrite

# 3. On installe les outils système et les extensions PHP nécessaires (ex: pour la BDD)
RUN apt-get update \
    && apt-get install -y libzip-dev git unzip --no-install-recommends \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip pdo pdo_mysql

# 4. On dit à Apache que le point d'entrée du site est le dossier "public/" de Symfony
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. On installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. On se place dans le dossier du site
WORKDIR /var/www/html

# 7. On copie tout le code source dans l'image (sauf ce qui est dans .dockerignore)
COPY . .

# 8. On installe les dépendances PHP sans les outils de développement
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=prod
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 9. On donne les bons droits d'écriture aux dossiers de cache et de logs
RUN mkdir -p var/cache var/log && chown -R www-data:www-data var/

# 10. On expose le port 80 pour que Fly.io puisse y accéder
EXPOSE 80

# On s'assure que la base de données est créée et que les droits sont parfaits
# On crée un dossier DATA spécifique pour SQLite
RUN mkdir -p /var/www/html/var/data
RUN touch /var/www/html/var/data/data.db
RUN chown -R www-data:www-data /var/www/html/var
RUN chmod -R 777 /var/www/html/var/data

