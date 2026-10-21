FROM php:8.1-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY . /var/www/html/

# Cấu hình Apache nhận gift.php làm file trang chủ mặc định
RUN echo "DirectoryIndex gift.php index.php index.html" >> /etc/apache2/apache2.conf

RUN chown -R www-data:www-data /var/www/html
