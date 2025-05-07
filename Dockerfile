FROM php:8.4-fpm

# Atualização do sistema e instalação de dependências
RUN apt-get update -y && apt-get install -y libpng-dev libjpeg-dev libwebp-dev libicu-dev

# Instalação de extensões PHP
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    intl

# Configuração e instalação da extensão GD
RUN docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-webp=/usr/include/ \
    && docker-php-ext-install gd

# Ajustes de permissões do usuário www-data
RUN usermod -u 1000 www-data && usermod -G staff www-data