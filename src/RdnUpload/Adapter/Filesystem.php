<?php

namespace RdnUpload\Adapter;

use RdnUpload\File;
use RdnUpload\File\FileInterface;
use RdnUpload\Object;
use Zend\Stdlib\ErrorHandler;

/**
 * Local filesystem storage for uploaded files.
 */
class Filesystem implements AdapterInterface
{
	/**
	 * The upload path where uploaded files are stored.
	 *
	 * @var string
	 */
	protected $uploadPath;

	/**
	 * The public path from where uploaded files are served.
	 *
	 * @var string
	 */
	protected $publicPath;

	/**
	 * @param string $uploadPath
	 * @param string $publicPath
	 *
	 * @throws \RuntimeException
	 * @throws \InvalidArgumentException
	 */
	public function __construct($uploadPath, $publicPath)
	{
		if (empty($uploadPath))
		{
			throw new \InvalidArgumentException('Must provide an upload directory');
		}

		if (!is_writeable($uploadPath))
		{
			throw new \RuntimeException("Cannot write to directory ($uploadPath)");
		}

		$this->uploadPath = rtrim($uploadPath, DIRECTORY_SEPARATOR);
		$this->publicPath = rtrim($publicPath, DIRECTORY_SEPARATOR);
	}

	/**
	 * Get the full file path of a file with the given id and an optional path prefix.
	 *
	 * @param string $id
	 * @param string $pathPrefix
	 *
	 * @return string
	 */
	protected function getFilepath($id, $pathPrefix = null)
	{
		if ($pathPrefix === null)
		{
			$pathPrefix = $this->uploadPath;
		}
		else
		{
			$pathPrefix = rtrim($pathPrefix, DIRECTORY_SEPARATOR);
		}

		return $pathPrefix . DIRECTORY_SEPARATOR . $id;
	}

	/**
	 * @throws \RuntimeException if move operation is unsuccessful
	 */
	public function upload($id, FileInterface $input)
	{
		$targetPath = $this->getFilepath($id);
		$targetDir = dirname($targetPath);
		if (!is_dir($targetDir))
		{
			mkdir($targetDir, 0777, true);
		}

		ErrorHandler::start();
		if ($input instanceof File\Input)
		{
			$flag = move_uploaded_file($input->getPath(), $targetPath);
		}
		else
		{
			$flag = rename($input->getPath(), $targetPath);
			chmod($targetPath, 0660);
		}
		ErrorHandler::stop(true);

		if (!$flag)
		{
			$this->purge($targetPath);

			throw new \RuntimeException("Could not move file ({$input->getPath()})");
		}
	}

	public function get($id)
	{
		if (!$this->has($id))
		{
			throw new \RuntimeException("File does not exist ($id)");
		}

		$file = new Object\Local($this->getFilepath($id), $this->getFilepath($id, $this->publicPath));
		return $file;
	}

	/**
	 * @throws \RuntimeException if copy operation is unsuccessful
	 */
	public function download($id, FileInterface $output)
	{
		$source = $this->getFilepath($id);

		ErrorHandler::start();
		$flag = copy($source, $output->getPath());
		ErrorHandler::stop(true);

		if (!$flag)
		{
			throw new \RuntimeException("Could not copy file ({$source})");
		}
	}

	public function has($id)
	{
		return file_exists($this->getFilepath($id));
	}

	/**
	 * @throws \RuntimeException if file does not exist or delete operation fails
	 */
	public function delete($id)
	{
		if (!$this->has($id))
		{
			throw new \RuntimeException("File does not exist ($id)");
		}

		$path = $this->getFilepath($id);

		ErrorHandler::start();
		$flag = unlink($path);
		ErrorHandler::stop(true);

		if (!$flag)
		{
			throw new \RuntimeException("Could not delete file ($path)");
		}

		$this->purge($path);
	}

	/**
	 * Remove all empty directories starting from the leaf node and moving all the way up to the upload path.
	 *
	 * @param string $path
	 */
	protected function purge($path)
	{
		$directory = pathinfo($path, PATHINFO_DIRNAME);
		while ($directory != $this->uploadPath)
		{
			if (count(glob($directory .'/*')))
			{
				break;
			}

			rmdir($directory);
			$directory = dirname($directory);
		}
	}
}
