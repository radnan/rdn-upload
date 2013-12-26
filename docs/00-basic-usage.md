Basic usage
===========

## How to install

### 1. Require package

Use [composer](http://getcomposer.org) to require the `radnan/rdn-upload` package:

~~~bash
$ composer require radnan/rdn-upload:1.*
~~~

### 2. Activate module

Activate the module by including it in your `application.config.php` file:

~~~php
<?php

return array(
	'modules' => array(
		'RdnUpload',
		// ...
	),
);
~~~

## How to use

All interactions are done through the **upload container**. The upload container allows us to upload, fetch, delete, and optionally download files.

~~~php
$adapter = new RdnUpload\Adapter\Filesystem('data/uploads', '/files');
$uploads = new RdnUpload\Container($adapter);

var_dump($_FILES['foo']);
// array(
//     'name' => 'sample-foo.png'
//     'type' => 'image/png'
//     'tmp_name' => '/tmp/php5Wx0aJ'
//     'error' => 0
//     'size' => 15726
// )

/**
 * Upload the file to the `data/uploads/` directory.
 */
$id = $uploads->upload($_FILES['foo']);

/**
 * Use this id to fetch, download, and delete this file object.
 */
echo $id; // b/6/1/b61cd9dbf7fdabc7bd67f27cc066d0fc50eacdc4/sample-foo.png

$object = $uploads->get($id);

echo $object->getBasename(); // sample-foo.png
echo $object->getPublicUrl(); // /files/b/6/1/b61cd9dbf7fdabc7bd67f27cc066d0fc50eacdc4/sample-foo.png
~~~

## How to create adapters

There are 2 steps involved in creating adapters:

1. Create your adapter and register it with the `RdnUpload\Adapter\AdapterManager` service locator
3. Configure the upload container service `RdnUpload\Container` to use your adapter

### 1. Upload container adapter service locator

Please read the [Upload container adapter service locator](02-upload-adapters.md) documentation.

### 2. Upload container service

Please read the [Configuration](01-config.md) documentation.

## Interfaces

The following interfaces are available in the module:

### `RdnUpload\ContainerInterface`

THe primary upload container object must implement this interface.

### `RdnUpload\Adapter\AdapterInterface`

Upload container adapters must implement this interface. These objects handle the actual file operations.

### `RdnUpload\File\FileInterface`

The upload container and adapters consume files via this interface.

### `RdnUpload\Object\ObjectInterface`

Uploaded objects implement this interface.
