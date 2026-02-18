#!/usr/bin/env bash
set -e

# Default to 80 if PORT is not set
PORT="${PORT:-80}"

# Update Apache to listen on the correct port
sed -ri "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

exec apache2-foreground