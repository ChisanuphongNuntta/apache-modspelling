# ใช้ภาพพื้นฐาน PHP 7.4 พร้อม Apache
FROM php:7.4.29-apache

# ติดตั้งโมดูล Apache และเครื่องมือที่จำเป็น
RUN apt-get update && apt-get install -y \
    vim \
    && a2enmod rewrite speling headers \
    && rm -rf /var/lib/apt/lists/*

# กำหนดโฟลเดอร์ Document Root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf

# ตั้งค่าการอนุญาตไฟล์
RUN chown -R www-data:www-data /var/www/html

# เปิดพอร์ต 80
EXPOSE 80

# รัน Apache ในโหมด foreground
CMD ["apache2-foreground"]
