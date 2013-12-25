<?php

namespace RdnUpload\Adapter;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

class AdapterManager extends AbstractPluginManager
{
	/**
	 * Validate the plugin
	 *
	 * Checks that the plugin loaded is an instance of AdapterInterface.
	 *
	 * @param  mixed $plugin
	 * @return void
	 * @throws Exception\RuntimeException if invalid
	 */
	public function validatePlugin($plugin)
	{
		if ($plugin instanceof AdapterInterface)
		{
			return;
		}

		throw new Exception\RuntimeException(sprintf(
			'Plugin of type %s is invalid; must implement %s\AdapterInterface'
			, is_object($plugin) ? get_class($plugin) : gettype($plugin)
			, __NAMESPACE__
		));
	}
}
