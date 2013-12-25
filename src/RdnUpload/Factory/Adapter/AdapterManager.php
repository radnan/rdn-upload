<?php

namespace RdnUpload\Factory\Adapter;

use RdnUpload\Adapter;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdapterManager implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $services)
	{
		$config = $services->get('Config');
		$config = new Config($config['rdn_upload_adapters']);

		$adapters = new Adapter\AdapterManager($config);
		$adapters->setServiceLocator($services);

		return $adapters;
	}
}
