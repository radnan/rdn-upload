Hydrator strategy
=================

The module comes with a strategy object that can be hooked up to a `file` form input object and it will take care of the rest.

~~~php
/** @var RdnUpload\ContainerInterface $uploads */

$strategy = new RdnUpload\Hydrator\Strategy\Upload($uploads);

$hydrator = new Zend\Stdlib\Hydrator\ClassMethods;
$hydrator->addStrategy('file', $strategy);
~~~
