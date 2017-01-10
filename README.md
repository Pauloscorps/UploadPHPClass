# UploadPHPClass
Minimalist PHP Class to upload files to you server.

## What is it ?
The PHP class Upload, is a simple and minimalist helper for file upload.
It allows you to define few simple checking rules, to make sur the sent files match your expectations.

## Begin
Here is the simple code to upload a file :

```php
require('Upload.php');
$upload = new Upload('documents', $_FILES['file']);
$upload->upload();
```
To upload your file, you juste have to include and instantiate the class with 2 parameters ;
* 1. First is the destination directory
* 2. Second is the $_FILES variable

> Don't forget ! To upload a file from a HTML form, you have to add this code to the form tag : enctype="multipart/form-data"

## Add rules / actions
### Restrict extensions
You can choose which extensions are allowed for the uploaded file. 
For instance, if you only want images files, you will allow : png, jpg, gif.
```php
$upload->set_allowed_extensions(array('png', 'jpg', gif));
```
> Since the use of MIME types is deprecated in terms of security, the class does not use them.

### Restrict max file size
You can choose the max file size allowed (bytes) for the file.
For instance, if the limitation is 2 M :
```php
$upload->set_max_size_allowed(200000);
```
### Define custom name for the final file
You can choose the final name of your file usgin set_name() function.
```php
$upload->set_name(‘Mon fichier de test !’, true);
```
The second param (default : false) allows you to define if the file name must be rewriten. If your sure of the name given in the first param, do :
```php
$upload->set_name(‘mon-fichier-de-test');
```

## Result
Upload result is returned by the upload() method. 
```php
$result = $upload->upload();
```
This var can be :
* An array containing errors
* TRUE if the upload is ok
* FALSE in case of internal error

### Example do display result
```php
<?php if(is_array($result)) { ?>
<h3>Erreur</h3>
<ol>
<?php foreach($result AS $k => $error) { ?>
<li><?php echo $error; ?></li>
<?php } ?>
</ol>
<?php } else if($result === true) { ?>
<p>Fichier envoyé !</p>
<?php } else { ?>
<p>Erreur interne !</p>
<?php } ?>
```

## Access vars
Here are the public vars contained in the upload object :
• $errors
• $final_name : final name of the file including its extension

## Contributing
Feel free to help me upgrading this class. It's far from perfect and all the ideas are good to take.

