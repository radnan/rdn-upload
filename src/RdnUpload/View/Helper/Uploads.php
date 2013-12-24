<?php

namespace RdnUpload\View\Helper;

use RdnUpload\ContainerInterface;
use RdnUpload\Object\ObjectInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Helper to render public path to uploaded files.
 */
class Uploads extends AbstractHelper
{
	/**
	 * @var ContainerInterface
	 */
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * @param string $id
	 *
	 * @return self|ObjectInterface
	 */
	public function __invoke($id = null)
	{
		if (func_num_args() == 0)
		{
			return $this;
		}

		return $this->container->get($id);
	}

	/**
	 * Get the upload container.
	 *
	 * @return ContainerInterface
	 */
	public function getContainer()
	{
		return $this->container;
	}
}
