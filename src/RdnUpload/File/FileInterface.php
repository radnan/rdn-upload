<?php

namespace RdnUpload\File;

/**
 * Local file object.
 */
interface FileInterface
{
	/**
	 * @return string
	 */
	public function getBasename();

	/**
	 * @return string
	 */
	public function getFilename();

	/**
	 * @return string
	 */
	public function getExtension();

	/**
	 * @return string
	 */
	public function getPath();
}
