![logo](https://raw.githubusercontent.com/yafp/monoto/master/www/images/logo/monotoLogoBlack.png) Developer notes
==========

# Structure
* docs/
> Contains the documentation

* docs/jsdocs
> Contains the jsdoc files for all monoto javascript files

* tests/
> Contains PHPUnit tests

* www/
> Contains all files needed to run monoto

* www/config/
> Config files for MySQL DB & monoto version informations

* www/css/
> All css files

* www/locale/
> Translations

* www/images/
> All images

* www/inc/
> Included php scripts

* www/js/
> All Javascripts (monoto and 3rd party)


# Coding conventions
## General
* If possible, use  (lower)[camelCase](https://en.wikipedia.org/wiki/Camel_case) for variables and functions.

## JavaScript
* ```console```
    * A lot of logging is used in this project to offer debug help
    * How to use the ```console``` command:
        * ```console.verbose``` for start and end messages of single js functions
        * ```console.log``` for default informations (black)
        * ```console.warn``` for warnings (yellow)
        * ```console.error``` for errors (red)

* ```jsdoc```
    * Online version of [current monoto jsdocs](http://yafp.github.io/monoto/docs/jsdocs/monoto/index.html)
    * Use ```jsdoc``` for function headers
    * Generation: From main repository directory, using jsdoc v3.3.0+: ```jsdoc --configure jsdoc.json --readme README.md --destination docs/jsdocs/```

## PHP
* Consider [PHP Coding conventions](https://www.mediawiki.org/wiki/Manual:Coding_conventions/PHP)
* Use ```phpDocumentor``` style DocBlock for function headers


## package.json
* Validate using ```http://package-json-validator.com/```
