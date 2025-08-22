#!/bin/bash
# Lancer SSH
service ssh start

# Créer un utilisateur vulnérable avec SUID sur python
useradd -m ctfuser
echo "ctfuser:ctfpass" | chpasswd
chown ctfuser:ctfuser /home/ctfuser

# Donner SUID sur python pour privilege escalation
chmod +s /usr/bin/python3

# Lancer Apache
apachectl -D FOREGROUND
