<?php

return array(
	'controller_plugins' => array(
		'aliases' => array(
			'uploads' => 'RdnUpload:Uploads',
		),

		'factories' => array(
			'RdnUpload:Uploads' => 'RdnUpload\Factory\Controller\Plugin\Uploads',
		),
	),

	'rdn_upload' => array(
		'adapter' => 'Local',
		'temp_dir' => null,
	),

	'rdn_upload_adapters' => array(
		'aliases' => array(
			'Filesystem' => 'Local',
		),
		'factories' => array(
			'Local' => 'RdnUpload\Factory\Adapter\Local',
			'Gaufrette' => 'RdnUpload\Factory\Adapter\Gaufrette',
		),

		'configs' => array(
			'Local' => array(
				'upload_path' => 'data/uploads',
				'public_path' => '/files',
			),

			'Gaufrette' => array(
				'filesystem' => null,
				'public_path' => null,
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
		'aliases' => array(
			'uploads' => 'RdnUpload:Uploads',
		),

		'factories' => array(
			'RdnUpload:Uploads' => 'RdnUpload\Factory\View\Helper\Uploads',
		),
	),
);
