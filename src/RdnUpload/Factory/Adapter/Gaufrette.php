<?php

namespace RdnUpload\Factory\Adapter;

use RdnUpload\Adapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Gaufrette implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $adapters)
	{
		if ($adapters instanceof ServiceLocatorAwareInterface)
		{
			$services = $adapters->getServiceLocator();
		}
		else
		{
			$services = $adapters;
		}

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
