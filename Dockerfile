FROM php:8.0-fpm

LABEL maintainer="Hasmukh Mistry <hasmukhmistry137@gmail.com>"

# Install dependencies and remove cache afterwards
RUN apt-get update && apt-get install -y \
	zlib1g-dev \
	libzip-dev \
	libpng-dev \
	default-mysql-client \
	wget \
	build-essential \
	libpcre3-dev \
	zlib1g-dev \
	libssl-dev && \
    rm -rf /var/lib/apt/lists/*

# Install Nginx with http_gzip_static_module
RUN wget http://nginx.org/download/nginx-1.19.6.tar.gz && \
	tar -xzvf nginx-1.19.6.tar.gz && \
	cd nginx-1.19.6 && \
	./configure --with-http_gzip_static_module && \
	make && make install

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the www.conf file to the PHP-FPM pool configuration directory
COPY www.conf /usr/local/etc/php-fpm.d/

# Copy Nginx configuration file
COPY nginx.conf /usr/local/nginx/conf/nginx.conf

# Copy PHP code
COPY src/ /var/www/html/

# Install zip, unzip, and git and remove cache afterwards
RUN apt-get update && apt-get install -y \
	zip \
	unzip \
	git \
	&& docker-php-ext-install zip pdo_mysql && \
    rm -rf /var/lib/apt/lists/*

# Set /var/www/html as the working directory
WORKDIR /var/www/html

# Copy startup script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Run startup script
CMD ["/start.sh"]
