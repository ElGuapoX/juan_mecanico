# Imagen base: PHP + Apache
FROM php:8.2-apache

# Habilitar mod_rewrite (útil para frameworks o rutas limpias)
RUN a2enmod rewrite

# Instalar la extensión mysqli (asegura disponibilidad en tiempo de ejecución)
RUN docker-php-ext-install mysqli || true

# Copiar código fuente dentro del contenedor
COPY src/ /var/www/html/

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80
EXPOSE 80
