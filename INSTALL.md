![logo](https://raw.githubusercontent.com/yafp/monoto/master/images/icons/monoto_logo_black.png) installation
==========

## 1. Download
Download the latest monoto version from github
- https://github.com/macfidelity/monoto/zipball/master

## 2. Extract
Extract the archive on disk

## 3. Adjust config
Check **conf/config.php** and modify it to your needs (mysql settings)

## 4. Prepare database
- Prepare your mysql server
- create databases (using the informations from MYSQL_STRUCT.txt)

## 5. Upload
upload the extracted files to your webserver target url

## 6. Setup
Visit **setup.php** in your browser and create your admin user with this script.

## 7. Cleanup
Secure your monoto install
- conf/config.php (only webserver should be allowoed to read this file)
- remove/delete setup.php
- optional: add .htaccess protection to your monoto installation - as it isnt designed for security aspects right now. Sorry

Enjoy!
