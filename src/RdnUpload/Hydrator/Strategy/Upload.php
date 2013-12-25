<?php

namespace RdnUpload\Hydrator\Strategy;

use RdnUpload\ContainerInterface;
use RdnUpload\File;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class Upload implements StrategyInterface
{
	/**
	 * @var ContainerInterface
	 */
	protected $container;

	/**
	 * @var callable
	 */
	protected $preUpload;

	/**
	 * @var string|null
	 */
	protected $value;

	public function __construct(ContainerInterface $container, callable $preUpload = null)
	{
		$this->container = $container;
		$this->preUpload = $preUpload;
	}

	public function extract($value)
	{
		$this->value = $value;
		return $value;
	}

	public function hydrate($data)
	{
		if ($data['error'] === UPLOAD_ERR_OK)
		{
			$input = new File\Input($data);
			if ($this->preUpload)
			{
				$input = call_user_func($this->preUpload, $input);
			}

			$newValue = $this->container->upload($input);

			if ($this->value && $this->container->has($this->value))
			{
				try
				{
					$this->container->delete($this->value);
				}
				catch (\Exception $ex)
				{
					$this->container->delete($newValue);
					throw $ex;
				}
			}

			$this->value = $newValue;
			return $this->value;
		}

		return $this->value;
	}
}
