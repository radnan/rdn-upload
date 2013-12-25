<?php

namespace RdnUpload\Factory;

use RdnUpload;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Container implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $services)
	{
		$config = $services->get('Config');
		$config = $config['rdn_upload'];

		$adapters = $services->get('RdnUpload\Adapter\AdapterManager');
		$adapter = $adapters->get($config['adapter']);

		return new RdnUpload\Container($adapter, $config['temp_dir']);
	}
}
