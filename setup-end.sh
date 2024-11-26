#!/usr/bin/env bash

# before running this script, make sure domain is pointed 
# to lightsail instance and you have ran the command:
# sudo git clone url /opt/bitnami/wordpress

# ##### START VARIABLES #####
table_prefix="wp_"
database_name="wp_cc"
domain_name="cosmeticconnection.com.au"
# ##### END VARIABLES #####

cd /opt/bitnami
sudo chown -R bitnami:daemon wordpress

if [[ -e "$HOME/uploads.zip" ]]; then
  cd /opt/bitnami/wordpress/wp-content
  mv ~/uploads.zip .
  unzip uploads.zip
  rm uploads.zip
fi

if [[ -e "$HOME/database.sql" ]]; then
  pass=$(cat ~/password)
  result=$(mysql -u root -p$pass -e "SHOW DATABASES" | grep $database_name)
  # if database does not exist
  if [[ "$result" != "$database_name" ]]; then
    mysql -u root -p$pass -e "CREATE DATABASE ${database_name};"
    mysql -u root -p$pass $database_name < "$HOME/database.sql"
    rm ~/database.sql
  fi
  mysql -u root -p$pass $database_name -e "UPDATE ${table_prefix}options SET option_value='https://${domain_name}' WHERE option_name='siteurl' OR option_name='home';"
fi

cd /opt/bitnami/wordpress
cp wp-config-sample.php wp-config.php
sudo bash set-correct-file-permissions.sh
mude
npm install && npm run build

sudo /opt/bitnami/bncert-tool



