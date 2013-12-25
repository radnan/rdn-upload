<?php

namespace RdnUpload;

use RdnUpload\Adapter\AdapterInterface;
use RdnUpload\File\FileInterface;
use RdnUpload\Object\ObjectInterface;

/**
 * Container for uploaded files.
 */
interface ContainerInterface
{
	/**
	 * Upload an object using a local temporary file and return the uploaded object's ID.
	 *
	 * The input can be a single $_FILES item or an object of type FileInterface.
	 *
	 * @param array|FileInterface $input
	 *
	 * @return string ID of the uploaded object.
	 */
	public function upload($input);

	/**
	 * Get the object from the container with the given ID.
	 *
	 * @param string $id
	 *
	 * @return ObjectInterface
	 */
	public function get($id);

	/**
	 * Download a local temporary copy of the object with the given ID.
	 *
	 * @param string $id
	 *
	 * @return FileInterface
	 */
	public function download($id);

	/**
	 * Test whether the container has an object with the given ID.
	 *
	 * @param string $id
	 *
	 * @return boolean
	 */
	public function has($id);

	/**
	 * Delete the object from the container with the given ID.
	 *
	 * @param string $id
	 */
	public function delete($id);

	public function setAdapter(AdapterInterface $adapter);

	/**
	 * @return AdapterInterface
	 */
	public function getAdapter();
}
