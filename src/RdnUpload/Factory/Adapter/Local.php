<?php

namespace RdnUpload\Factory\Adapter;

use RdnUpload\Adapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Local implements FactoryInterface
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
		if (isset($config['rdn_upload_adapters']['configs']['Filesystem']))
		{
			$legacyConfig = $config['rdn_upload_adapters']['configs']['Filesystem'];
		}
		else
		{
			$legacyConfig = array();
		}
		$config = array_replace_recursive($legacyConfig, $config['rdn_upload_adapters']['configs']['Local']);

		if ($services->has('ViewHelperManager'))
		{
			$helpers = $services->get('ViewHelperManager');
			$config['public_path'] = call_user_func($helpers->get('BasePath'), $config['public_path']);
		}

		return new Adapter\Local($config['upload_path'], $config['public_path']);
	}
}
