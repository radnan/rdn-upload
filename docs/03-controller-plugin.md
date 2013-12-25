Controller plugin
=================

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

## Authorization

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

## Configuration

The controller plugin is configured as an alias to `RdnUpload:Uploads`. If you have another plugin with this same name, you can change the plugin's name by using the following configuration:

~~~php
<?php

return array(
	'controller_plugins' => array(
		'aliases' => array(
			'fooUploads' => 'RdnUpload:Uploads',
		),
	),
);
~~~
