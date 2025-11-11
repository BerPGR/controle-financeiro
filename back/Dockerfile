# Usa a imagem oficial do PHP com Apache
FROM php:8.4-apache

# Instalação das dependências e extensões
RUN apt-get update && apt-get install -y --no-install-recommends \
      git unzip libzip-dev \
  && docker-php-ext-install pdo pdo_mysql \
  && docker-php-ext-configure zip \
  && docker-php-ext-install zip \
  && rm -rf /var/lib/apt/lists/*

# Habilita o módulo rewrite do Apache (essencial para o roteamento do .htaccess)
RUN a2enmod rewrite

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia e instala o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-scripts

# Copia todos os arquivos da aplicação (incluindo o novo CorsUtil.php e o .htaccess simplificado)
COPY . .

RUN composer dump-autoload --optimize

# Copia e aplica a configuração customizada do VirtualHost (MUITO IMPORTANTE!)
# Esta linha define public/ como DocumentRoot, resolvendo o 404 anterior
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Certifica-se que o Apache tem permissões
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
# CMD padrão da imagem PHP/Apache (não é necessário especificar)