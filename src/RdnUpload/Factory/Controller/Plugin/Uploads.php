<?php

namespace RdnUpload\Factory\Controller\Plugin;

use RdnUpload\ContainerInterface;
use RdnUpload\Controller\Plugin;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Uploads implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $services)
	{
		/** @var ContainerInterface $uploads */
		$uploads = $services->get('RdnUpload\Container');
		return new Plugin\Uploads($uploads);
	}
}
