<?php

namespace RdnUpload\Adapter;

use Zend\ServiceManager\AbstractPluginManager;

class AdapterManager extends AbstractPluginManager
{
	protected $instanceOf = AdapterInterface::class;
}
