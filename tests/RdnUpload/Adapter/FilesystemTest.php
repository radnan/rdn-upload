<?php

namespace RdnUpload\Adapter;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use RdnUpload\File\File;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

class FilesystemTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var vfsStreamDirectory
	 */
	private $vfs;

	/**
	 * @var Filesystem
	 */
	private $adapter;

	public function setUp()
	{
		$this->vfs = vfsStream::setup('root', null, array(
			'uploads' => array(
				'baz.txt' => 'Sample baz content',
			),
			'tmp' => array(
				'foo.txt' => 'Sample foo content',
			),
		));
		$this->adapter = new Filesystem(vfsStream::url('root/uploads'), '/files');
	}

	public function testUpload()
	{
		$this->assertTrue($this->vfs->getChild('tmp')->hasChild('foo.txt'));
		$this->assertFalse($this->vfs->getChild('uploads')->hasChild('bar'));

		$input = new File('foo.txt', vfsStream::url('root/tmp/foo.txt'));
		$this->adapter->upload('bar/foo.txt', $input);

		$this->assertFalse($this->vfs->hasChild('foo.txt'));
		$this->assertTrue($this->vfs->getChild('uploads')->hasChild('bar'));
		$this->assertTrue($this->vfs->getChild('uploads')->getChild('bar')->hasChild('foo.txt'));
	}

	public function testGet()
	{
		$this->assertTrue($this->vfs->getChild('uploads')->hasChild('baz.txt'));

		$object = $this->adapter->get('baz.txt');

		$this->assertInstanceOf('RdnUpload\Object\ObjectInterface', $object);
		$this->assertEquals(vfsStream::url('root/uploads/baz.txt'), $object->getPath());
	}

	public function testDownload()
	{
		$this->assertFalse($this->vfs->getChild('tmp')->hasChild('pot.txt'));

		$output = new File('pot.txt', vfsStream::url('root/tmp/pot.txt'));
		$this->adapter->download('baz.txt', $output);

		$this->assertTrue($this->vfs->getChild('tmp')->hasChild('pot.txt'));
	}

	public function testHas()
	{
		$this->assertTrue($this->vfs->getChild('uploads')->hasChild('baz.txt'));
		$this->assertTrue($this->adapter->has('baz.txt'));
	}

	public function testDelete()
	{
		$this->assertTrue($this->vfs->getChild('uploads')->hasChild('baz.txt'));
		$this->adapter->delete('baz.txt');
		$this->assertFalse($this->vfs->getChild('uploads')->hasChild('baz.txt'));
	}

	public function testFactory()
	{
		$config = include __DIR__ .'/../../../config/module.config.php';

		$config['rdn_upload_adapters']['configs']['Filesystem']['upload_path'] = vfsStream::url('root/uploads');

		$services = new ServiceManager(new ServiceManagerConfig($config['service_manager']));
		$services->setService('Config', $config);

		$adapters = $services->get('RdnUpload\Adapter\AdapterManager');
		$adapter = $adapters->get('Filesystem');

		$this->assertInstanceOf('RdnUpload\Adapter\Filesystem', $adapter);
	}
}
