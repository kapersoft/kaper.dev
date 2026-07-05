FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates \
    curl \
    git \
    gnupg \
    jq \
    openssh-server \
    software-properties-common \
    sudo \
    unzip \
    && rm -rf /var/lib/apt/lists/*

RUN add-apt-repository -y ppa:ondrej/php \
    && apt-get update && apt-get install -y --no-install-recommends \
    php8.5-bcmath \
    php8.5-cli \
    php8.5-curl \
    php8.5-intl \
    php8.5-mbstring \
    php8.5-mysql \
    php8.5-sqlite3 \
    php8.5-xml \
    php8.5-zip \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /home/ubuntu
