<?php

namespace RdnUpload\Factory\View\Helper;

use Interop\Container\ContainerInterface;
use RdnUpload;
use RdnUpload\View\Helper;
use Zend\ServiceManager\Factory\FactoryInterface;

class Uploads implements FactoryInterface
{
	public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
	{
		/** @var RdnUpload\ContainerInterface $uploads */
		$uploads = $services->get('RdnUpload\Container');
		return new Helper\Uploads($uploads);
	}
}
