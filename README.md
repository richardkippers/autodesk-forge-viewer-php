# autodesk-forge-viewer-php (in development)

Test PHP script for uploading and viewing Revit models in Autodesk Forge (not suitable for production)

Tested on PHP 7.1.8 with Apache.

## installation

Create `environment.ini`:

```
AUTODESK_CLIENT_ID		=	'';
AUTODESK_CLIENT_SECRET	=	''

BUCKET_NAME				=	'';
```

*BUCKET_NAME* : Possible values: -_.a-z0-9 (between 3-128 characters in length). Will be created if bucket not exists.

**Run bower install and composer install.**

## todo

* Faster and better uploading 
* Extend viewer
* Clean code

## Used libraries

* jQuery
* Bootstrap
* dannyvankooten/php-router