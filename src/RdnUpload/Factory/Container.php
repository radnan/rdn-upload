<?php

namespace RdnUpload\Factory;

use Interop\Container\ContainerInterface;
use RdnUpload;
use Zend\ServiceManager\Factory\FactoryInterface;

class Container implements FactoryInterface
{
	public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
	{
		$config = $services->get('Config');
		$config = $config['rdn_upload'];

		$adapters = $services->get('RdnUpload\Adapter\AdapterManager');
		$adapter = $adapters->get($config['adapter']);

		return new RdnUpload\Container($adapter, $config['temp_dir']);
	}
}
