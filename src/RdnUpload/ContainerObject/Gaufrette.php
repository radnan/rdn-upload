<?php

namespace RdnUpload\ContainerObject;

use DateTime;
use Gaufrette\File as GFile;

class Gaufrette implements ObjectInterface
{
	/**
	 * @var GFile
	 */
	private $file;

	/**
	 * @var string
	 */
	protected $publicPath;

	/**
	 * @param GFile $file
	 * @param string $publicPath
	 */
	public function __construct(GFile $file, $publicPath)
	{
		$this->file = $file;
		$this->publicPath = $publicPath;
	}

	/**
	 * Get the public URL to the file.
	 *
	 * @return string
	 */
	public function getPublicUrl()
	{
		return $this->publicPath .'/'. $this->file->getName();
	}

	public function getContent()
	{
		return $this->file->getContent();
	}

	public function getBasename()
	{
		return basename($this->file->getName());
	}

	public function getExtension()
	{
		return pathinfo($this->file->getName(), PATHINFO_EXTENSION);
	}

	public function getContentLength()
	{
		return $this->file->getSize();
	}

	public function getContentType()
	{
		$info = new \finfo(FILEINFO_MIME_TYPE);
		return $info->buffer($this->getContent());
	}

	public function getLastModified()
	{
		return new DateTime('@'. $this->file->getMtime());
	}

	public function __toString()
	{
		return $this->getPublicUrl();
	}
}
