<?php

namespace RdnUpload\Factory\Adapter;

use Interop\Container\ContainerInterface;
use RdnUpload\Adapter;
use Zend\ServiceManager\Factory\FactoryInterface;

class Gaufrette implements FactoryInterface
{
	public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
	{
		$config = $services->get('Config');
		$config = $config['rdn_upload_adapters']['configs']['Gaufrette'];

		if (!isset($config['filesystem']))
		{
			throw new \InvalidArgumentException("You must set the 'rdn_upload_adapters.configs.Gaufrette.filesystem' configuration option to a valid Gaufrette filesystem service name");
		}

		$filesystem = $services->get($config['filesystem']);
		return new Adapter\Gaufrette($filesystem, $config['public_path']);
	}
}
