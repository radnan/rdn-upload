RdnUpload
=========

The **RdnUpload** ZF2 module makes it really easy to manage file uploads.

The underlying file operations are abstracted away from the developer. By default files are stored in the local filesystem. But we can easily replace the local adapter with something like Amazon cloud storage.

## How to install

The module is still under development.

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

## Controller Plugin

The module comes with the `uploads()` plugin that allows us to easily access the upload container along with some other goodies.

~~~php
class FooController
{
	public function viewAction()
	{
		$id = /* ... */;
		$object = $this->uploads($id);
	}
}
~~~

todo

### Response

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

// todo
~~~

## View Helper

The module comes with the `uploads()` view helper that makes it really easy to render an uploaded object's public url.

~~~php
<?php /** @var Zend\View\Renderer\PhpRenderer $this */ ?>

<?php if ($this->uploads()->getContainer()->has($id)): ?>
	<img src="<?= $this->uploads($id) ?>">
<?php endif ?>
~~~
