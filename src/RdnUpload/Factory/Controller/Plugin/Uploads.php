<?php

namespace RdnUpload\Factory\Controller\Plugin;

use Interop\Container\ContainerInterface;
use RdnUpload\Controller\Plugin;
use Zend\ServiceManager\Factory\FactoryInterface;

class Uploads implements FactoryInterface
{
	public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
	{
		/** @var \RdnUpload\ContainerInterface $uploads */
		$uploads = $services->get('RdnUpload\Container');
		return new Plugin\Uploads($uploads);
	}
}
