<?php

namespace RdnUpload\Controller\Plugin;

use RdnUpload\ContainerInterface;
use RdnUpload\LazyResponse;
use RdnUpload\Object\ObjectInterface;
use Zend\Http\PhpEnvironment\Response as HttpResponse;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Plugin to quickly get access to the upload container and send uploaded files as a response to the client.
 */
class Uploads extends AbstractPlugin
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
	 * Return the file object if an id is given, otherwise return the plugin itself.
	 *
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
	 * Return a response object for a given file.
	 *
	 * @param string $id
	 * @param string $filename Filename to use instead of the file's actual basename
	 *
	 * @return HttpResponse
	 */
	public function getResponse($id, $filename = null)
	{
		$object = $this->container->get($id);

		if ($filename === null)
		{
			$filename = $object->getBasename();
		}

		if (!pathinfo($filename, PATHINFO_EXTENSION))
		{
			$filename .= '.'. $object->getExtension();
		}

		/** @var HttpResponse $response */
		$response = $this->controller->getResponse();

		$response->setContent(new LazyResponse($object));
		$response->getHeaders()->addHeaders(array(
			'Content-Type' => 'application/octet-stream',
			'Content-Disposition' => 'attachment;filename="'. str_replace('"', '\\"', $filename) .'"',
			'Content-Transfer-Encoding' => 'binary',
			'Expires' => '-1 year',
			'Cache-Control' => 'must-revalidate',
			'Pragma' => 'public',
			'Content-Length' => $object->getContentLength(),
		));

		return $response;
	}

	/**
	 * Generate a thumbnail from an object and return the ID of the new object.
	 *
	 * @param string $id
	 * @param int $width
	 * @param int $height
	 *
	 * @return string
	 */
	public function generateThumbnail($id, $width, $height = null)
	{
		if ($height === null)
		{
			$height = $width;
		}

		$temp = $this->container->download($id);

		$img = new \Imagick($temp->getPath());
		$img->cropThumbnailImage($width, $height);
		$img->writeimage();

		return $this->container->upload($temp);
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
