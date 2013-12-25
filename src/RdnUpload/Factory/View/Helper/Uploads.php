<?php

namespace RdnUpload\Factory\View\Helper;

use RdnUpload\ContainerInterface;
use RdnUpload\View\Helper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Uploads implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $services)
	{
		/** @var ContainerInterface $uploads */
		$uploads = $services->get('RdnUpload\Container');
		return new Helper\Uploads($uploads);
	}
}
