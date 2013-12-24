<?php

namespace RdnUpload\Adapter;

use RdnUpload\File\FileInterface;
use RdnUpload\Object\ObjectInterface;

/**
 * Upload container adapter for performing file operations.
 */
interface AdapterInterface
{
	/**
	 * Create a new object using the given id from the input file.
	 *
	 * @param string $id
	 * @param FileInterface $input
	 */
	public function upload($id, FileInterface $input);

	/**
	 * Get an object from storage using the given id.
	 *
	 * @param string $id
	 *
	 * @return ObjectInterface
	 */
	public function get($id);

	/**
	 * Download an object using the given id to the output file.
	 *
	 * @param string $id
	 * @param FileInterface $output
	 */
	public function download($id, FileInterface $output);

	/**
	 * Check if the storage contains a object with the given id.
	 *
	 * @param string $id
	 *
	 * @return bool
	 */
	public function has($id);

	/**
	 * Delete an object from storage with the given id.
	 *
	 * @param string $id
	 */
	public function delete($id);
}
