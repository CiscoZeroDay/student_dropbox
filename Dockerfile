FROM ubuntu:22.04

# Eviter les prompts interactifs
ENV DEBIAN_FRONTEND=noninteractive

# Installer Apache, PHP, netcat, sudo, python3
RUN apt-get update && \
    apt-get install -y apache2 php libapache2-mod-php sudo python3 netcat openssh-server && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Activer le support pour .php, .php3, .php4, .php5, .php7
RUN echo "AddType application/x-httpd-php .php .php3 .php4 .php5 .php7" \
    > /etc/apache2/conf-available/php-legacy-ext.conf && \
    a2enconf php-legacy-ext

# Copier le panel étudiant
COPY student /var/www/html/student
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Créer le dossier projects et fixer permissions
RUN mkdir -p /var/www/html/projects && \
    chown -R www-data:www-data /var/www/html/projects && \
    chmod 777 /var/www/html/projects

# Activer l'indexation pour /projects
RUN echo '<Directory /var/www/html/projects>\n    Options +Indexes\n    AllowOverride All\n    Require all granted\n</Directory>' \
    > /etc/apache2/conf-available/projects-index.conf && \
    a2enconf projects-index

# Ajout d'un flag final dans /root
RUN echo "flag{4rbitr4ry_f1le_uplo4d_to_rce}" > /root/root.txt

# Exposer les ports
EXPOSE 80 22

# Lancer le script au démarrage
CMD ["/start.sh"]
