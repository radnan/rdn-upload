<?php

namespace RdnUpload\Object;

use DateTime;

/**
 * Objects stored in the upload container.
 */
interface ObjectInterface
{
	/**
	 * Get the public URL to the file.
	 *
	 * @return string
	 */
	public function getPublicUrl();

	/**
	 * Get the file's contents.
	 *
	 * @return string
	 */
	public function getContent();

	/**
	 * Get the file's basename.
	 *
	 * @return string
	 */
	public function getBasename();

	/**
	 * Get the file's extension.
	 *
	 * @return string
	 */
	public function getExtension();

	/**
	 * Get the file's size in bytes.
	 *
	 * @return int
	 */
	public function getContentLength();

	/**
	 * Get the file's last modified date and time.
	 *
	 * @return DateTime
	 */
	public function getLastModified();

	/**
	 * Proxy to get the public URL of the file.
	 *
	 * @return string
	 */
	public function __toString();
}
