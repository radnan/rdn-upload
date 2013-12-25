<?php

namespace RdnUpload\Factory\Adapter;

use RdnUpload\Adapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Filesystem implements FactoryInterface
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
		$config = $config['rdn_upload_adapters']['configs']['Filesystem'];

		if ($services->has('ViewHelperManager'))
		{
			$helpers = $services->get('ViewHelperManager');
			$config['public_path'] = call_user_func($helpers->get('BasePath'), $config['public_path']);
		}

		return new Adapter\Filesystem($config['upload_path'], $config['public_path']);
	}
}
