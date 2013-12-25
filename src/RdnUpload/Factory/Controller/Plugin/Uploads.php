<?php

namespace RdnUpload\Factory\Controller\Plugin;

use RdnUpload\ContainerInterface;
use RdnUpload\Controller\Plugin;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Uploads implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $plugins)
	{
		if ($plugins instanceof ServiceLocatorAwareInterface)
		{
			$services = $plugins->getServiceLocator();
		}
		else
		{
			$services = $plugins;
		}

		/** @var ContainerInterface $uploads */
		$uploads = $services->get('RdnUpload\Container');
		return new Plugin\Uploads($uploads);
	}
}
