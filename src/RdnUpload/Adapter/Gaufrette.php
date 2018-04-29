<?php

namespace RdnUpload\Adapter;

use Gaufrette\File as GFile;
use Gaufrette\Filesystem as GFilesystem;
use RdnUpload\File\FileInterface;
use RdnUpload\ContainerObject;
use Zend\Stdlib\ErrorHandler;

class Gaufrette implements AdapterInterface
{
	/**
	 * @var GFilesystem
	 */
	protected $filesystem;

	/**
	 * @var string
	 */
	private $publicPath;

	/**
	 * @param GFilesystem $filesystem
	 * @param string $publicPath
	 */
	public function __construct(GFilesystem $filesystem, $publicPath)
	{
		$this->filesystem = $filesystem;
		$this->publicPath = rtrim($publicPath, '/');
	}

	public function upload($id, FileInterface $input)
	{
		$this->filesystem->write($id, file_get_contents($input->getPath()));
	}

	public function get($id)
	{
		$this->assertHasObject($id);

		$file = new GFile($id, $this->filesystem);
		return new ContainerObject\Gaufrette($file, $this->publicPath);
	}

	/**
	 * @inheritdoc
	 * @throws \RuntimeException
	 */
	public function download($id, FileInterface $output)
	{
		$source = new GFile($id, $this->filesystem);

		ErrorHandler::start();
		$flag = file_put_contents($output->getPath(), $source->getContent());
		ErrorHandler::stop(true);

		if ($flag === false)
		{
			throw new \RuntimeException("Could not download file ({$id})");
		}
	}

	public function has($id)
	{
		return $this->filesystem->has($id);
	}


	public function delete($id)
	{
		$this->assertHasObject($id);

		$this->filesystem->delete($id);
	}

	private function assertHasObject($id)
	{
		if (!$this->has($id))
		{
			throw new \RuntimeException("File does not exist ($id)");
		}
	}
}
