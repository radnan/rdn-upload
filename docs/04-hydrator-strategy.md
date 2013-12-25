Hydrator strategy
=================

The module comes with a strategy object that can be hooked up to a `file` form input object and it will take care of the rest.

~~~php
/** @var RdnUpload\ContainerInterface $uploads */

$strategy = new RdnUpload\Hydrator\Strategy\Upload($uploads);

$hydrator = new Zend\Stdlib\Hydrator\ClassMethods;
$hydrator->addStrategy('file', $strategy);
~~~

The strategy will extract and store the object's identifier.

During hydration, if no file is uploaded it will return the stored identifier. Otherwise it will upload the new file, delete the old file if there was one, and finally return the new identifier.

## Pre process

Many times you will need to perform some pre-processing before a file is uploaded. Simply provide the pre-process callback as a second argument to the strategy:

~~~php
/** @var RdnUpload\ContainerInterface $uploads */

use RdnUpload\File;
use RdnUpload\Hydrator\Strategy;

$strategy = new Strategy\Upload($uploads, function(File\FileInterface $input)
{
	$img = new Imagick($input->getPath());
	$img->cropThumbnailImage(64, 64);
	$img->writeImage();

	return $input;
});

$hydrator = new Zend\Stdlib\Hydrator\ClassMethods;
$hydrator->addStrategy('file', $strategy);
~~~
