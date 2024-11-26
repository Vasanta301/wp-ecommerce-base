#!/usr/bin/env bash
# Make sure to run this from inside the root wordpress folder (the one with the wp-config.php file)
# sudo bash set-correct-file-permissions.sh
# See https://wordpress.org/support/article/hardening-wordpress/#file-permissions for more info
chown -R bitnami:daemon .
find . -not -path "./.git" -not -path "./.git/*" -not -path "./wp-content/themes/mude/node_modules" -not -path "./wp-content/themes/mude/node_modules/*" -type d -exec chmod 775 {} \;
find . -not -path "./.git" -not -path "./.git/*" -not -path "./wp-content/themes/mude/node_modules" -not -path "./wp-content/themes/mude/node_modules/*" -type f -exec chmod 664 {} \;
chmod 640 wp-config.php


