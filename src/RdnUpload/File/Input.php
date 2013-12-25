<?php

namespace RdnUpload\File;

/**
 * File object from $_FILES input.
 */
class Input extends File
{
	public function __construct(array $data)
	{
		if (!isset($data['name']))
		{
			throw new \InvalidArgumentException("Input array does not contain the 'name' key");
		}
		if (!isset($data['tmp_name']))
		{
			throw new \InvalidArgumentException("Input array does not contain the 'tmp_name' key");
		}

		parent::__construct($data['name'], $data['tmp_name']);
	}
}
