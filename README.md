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
1. First is the destination directory
2. Second is the $_FILES variable

> Don't forget ! To upload a file from a HTML form, you have to add this code to the <form> tag : enctype="multipart/form-data"

## Add rules / actions
### Restrict extensions
You can choose which extensions are allowed for the uploaded file. 
For instance, if you only want images files, you will allow : png, jpg, gif.
```php
$upload->set_allowed_extensions(array('png', 'jpg', gif));
```
> Since the use of MIME types is deprecated in terms of security, the class does not use them.

### Restrict max file size

## Contributing
I would like to improve this library by adding more file types in the future. 
Feel free to contribute to the library development by telling me file types you would want.

