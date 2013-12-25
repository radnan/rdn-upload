<?php

return array(
	'controller_plugins' => array(
		'factories' => array(
			'Uploads' => 'RdnUpload\Factory\Controller\Plugin\Uploads',
		),
	),

	'rdn_upload' => array(
		'adapter' => 'Filesystem',
		'temp_dir' => null,
	),

	'rdn_upload_adapters' => array(
		'factories' => array(
			'Filesystem' => 'RdnUpload\Factory\Adapter\Filesystem',
		),

		'configs' => array(
			'Filesystem' => array(
				'upload_path' => 'data/uploads',
				'public_path' => '/files',
			),
		),
	),

	'service_manager' => array(
		'factories' => array(
			'RdnUpload\Adapter\AdapterManager' => 'RdnUpload\Factory\Adapter\AdapterManager',
			'RdnUpload\Container' => 'RdnUpload\Factory\Container',
		),
	),

	'view_helpers' => array(
		'factories' => array(
			'Uploads' => 'RdnUpload\Factory\View\Helper\Uploads',
		),
	),
);
