Configuration
=============

The `RdnUpload\Container` service is configured using the `rdn_upload` configuration option. The following default configuration is provided by the module:

~~~php
<?php

return array(
	'rdn_upload' => array(
		'adapter' => 'Local',
		'temp_dir' => null,
	),
);
~~~

The `adapter` option points to an adapter loaded from the [upload container adapter service locator](02-upload-adapters.md).

The `temp_dir` option can be used to customize where temporarily downloaded files are placed. If `null`, the `temp_dir` option will default to `sys_get_temp_dir()`.
