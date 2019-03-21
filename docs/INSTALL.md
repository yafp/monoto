![logo](https://raw.githubusercontent.com/yafp/monoto/master/www/images/logo/monotoLogoBlack.png) installation
==========

## 1. Download
Download the latest monoto version from github
* [https://github.com/macfidelity/monoto/zipball/master](https://github.com/macfidelity/monoto/zipball/master)

## 2. Extract
* Extract the download archive

## 3. Prepare database
* create databases (using the informations from MYSQL_STRUCT.txt)
* create user for new database

## 4. Adjust config
* Check ```www/config/databaseConfig.php``` and modify the mysql access settings to your needs

## 5. Upload
* Upload the extracted content of the ```www``` folder to your webserver

## 6. Setup
* Visit ```setup.php``` in your browser and create your monoto admin user with this script

## 7. Cleanup
* Secure your monoto install
  * ```config/databaseConfig.php``` (only webserver should be allowed to read this file)
  * delete ```setup.php```

## 8. Apache & .htaccess (optional)
* ```.htaccess``` is needed for error handling (404)
* ```AllowOverride``` must be set to ```All```, otherwise .htaccess won't work.
