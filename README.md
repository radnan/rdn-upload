RdnUpload
=========

The **RdnUpload** ZF2 module makes it really easy to manage file uploads.

The underlying file operations are abstracted away from the developer. By default files are stored in the local filesystem. But we can easily replace the local adapter with something like Amazon cloud storage.

[![Build Status](https://travis-ci.org/radnan/rdn-upload.png)](https://travis-ci.org/radnan/rdn-upload)

## How to install

Use composer to require the `radnan/rdn-upload` package:

~~~bash
$ composer require radnan/rdn-upload:1.*
~~~

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
$adapter = new RdnUpload\Adapter\Local('data/uploads', '/files');
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

### `upload($input)`

Files can be uploaded to the container using a single `$_FILES` array item or an object implementing `RdnUpload\File\FileInterface`.

The container will return a unique string identifier for the uploaded object.

~~~php
/** @var RdnUpload\ContainerInterface $uploads */

/**
 * Upload a file using a single $_FILES item.
 */
$id = $uploads->upload($_FILES['foo']);

/**
 * Upload a file using an input object.
 */
$input = new RdnUpload\File\Input($_FILES['foo']);
$id = $uploads->upload($input);

/**
 * Upload a local file.
 */
$input = new RdnUpload\File\File('sample-foo.png', '/path/to/sample-foo.png');
$id = $uploads->upload($input);
~~~

Remember to store the returned identifier which you will use to retrieve the uploaded object, delete it, etc.

### `has($id)`

Check whether an object with the given identifier exists in the upload container.

### `get($id)`

Get the object with the given identifier. Returns an object implementing `RdnUpload\Object\ObjectInterface`.

~~~php
/** @var RdnUpload\ContainerInterface $uploads */

$object = $uploads->get($id);

echo $object->getBasename();
echo $object->getExtension();

echo $object->getPublicUrl();
echo $object; // The __toString() method will return the output of getPublicUrl()
~~~

### `delete($id)`

Delete the object with the given identifier.

### `download($id)`

Download a local temporary copy of the object with the given identifier. Returns an object implementing `RdnUpload\File\FileInterface`.

~~~php
/** @var RdnUpload\ContainerInterface $uploads */

$file = $uploads->download($id);

$img = new Imagick($file->getPath());
$img->cropThumbnailImage(64, 64);
$img->writeImage();

$thumbId = $uploads->upload($file);
~~~

## Controller Plugin

The module comes with the `uploads()` plugin that allows us to easily access the upload container along with some other goodies.

~~~php
class FooController
{
	public function viewAction()
	{
		$id = /* ... */;
		$object = $this->uploads()->get($id);
	}
}
~~~

You have access to the following methods from this plugin:

* `upload($input)`
* `get($id)`
* `download($id)`
* `has($id)`
* `delete($id)`
* `getContainer()` - Returns the upload container.

### Authorization

In many cases instead of allowing public web access to uploaded files you would like to place them behind some form of authorization. The `getResponse()` method inside the controller plugin makes this easy as pie.

~~~php
// inside a controller action

$id = /* ... */;

if (/* not allowed to view file */)
{
	throw new AccessDeniedException("You do not have access to this file!");
}

return $this->uploads()->getResponse($id);
~~~

## Hydrator Strategy (Forms)

The module comes with a strategy object that can be hooked up to a `file` form input object and it will take care of the rest.

~~~php
/** @var RdnUpload\ContainerInterface $uploads */

$strategy = new RdnUpload\Hydrator\Strategy\Upload($uploads);

$hydrator = new Zend\Stdlib\Hydrator\ClassMethods;
$hydrator->addStrategy('file', $strategy);
~~~

## View Helper

The module comes with the `uploads()` view helper that makes it really easy to render an uploaded object's public url.

~~~php
<?php /** @var Zend\View\Renderer\PhpRenderer $this */ ?>

<?php if ($this->uploads()->has($id)): ?>
	<img src="<?= $this->uploads()->get($id) ?>">
<?php endif ?>
~~~

You have access to the following methods from this helper:

* `get($id)`
* `has($id)`
* `getContainer()` - Returns the upload container.
