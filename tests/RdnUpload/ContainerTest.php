<?php

namespace RdnUpload;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use RdnUpload\Adapter\Local;
use RdnUpload\File\File;
use Zend\ServiceManager\ServiceManager;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var vfsStreamDirectory
	 */
	private $vfs;

	/**
	 * @var Container
	 */
	private $uploads;

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
		$adapter = new Local(vfsStream::url('root/uploads'), '/files');
		$this->uploads = new Container($adapter, vfsStream::url('root/tmp'));
	}

	public function testHas()
	{
		$this->assertTrue($this->uploads->has('baz.txt'));
	}

	public function testGet()
	{
		$object = $this->uploads->get('baz.txt');
		$this->assertEquals('baz.txt', $object->getBasename());
		$this->assertEquals('Sample baz content', $object->getContent());
	}

	public function testUpload()
	{
		$this->assertTrue($this->vfs->getChild('tmp')->hasChild('foo.txt'));
		$this->assertFalse($this->vfs->getChild('uploads')->hasChild('bar'));

		$input = new File('foo.txt', vfsStream::url('root/tmp/foo.txt'));
		$id = $this->uploads->upload($input);

		$this->assertFalse($this->vfs->hasChild('foo.txt'));

		$parts = explode('/', $id);
		$leaf = array_pop($parts);
		$child = $this->vfs->getChild('uploads');
		foreach ($parts as $part)
		{
			$this->assertTrue($child->hasChild($part));
			$child = $child->getChild($part);
		}

		$this->assertTrue($child->hasChild($leaf));
	}

	public function testFactory()
	{
		$config = include __DIR__ .'/../../config/module.config.php';

		$config['rdn_upload_adapters']['configs']['Local']['upload_path'] = vfsStream::url('root/uploads');

		$services = new ServiceManager($config['service_manager']);
		$services->setService('Config', $config);

		$uploads = $services->get('RdnUpload\Container');
		$this->assertInstanceOf('RdnUpload\Container', $uploads);
	}
}
