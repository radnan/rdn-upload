<?php

namespace RdnUpload\Factory\Adapter;

use Interop\Container\ContainerInterface;
use RdnUpload\Adapter;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdapterManager implements FactoryInterface
{
	public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
	{
		$config = $services->get('Config');
		$adapters = new Adapter\AdapterManager($services, $config['rdn_upload_adapters']);

		return $adapters;
	}
}
