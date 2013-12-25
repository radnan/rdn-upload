<?php

namespace RdnUpload;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use RdnUpload\Adapter\Filesystem;
use Zend\Mvc\Service\ServiceManagerConfig;
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
		$adapter = new Filesystem(vfsStream::url('root/uploads'), '/files');
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

	public function testFactory()
	{
		$config = include __DIR__ .'/../../config/module.config.php';

		$config['rdn_upload_adapters']['configs']['Filesystem']['upload_path'] = vfsStream::url('root/uploads');

		$services = new ServiceManager(new ServiceManagerConfig($config['service_manager']));
		$services->setService('Config', $config);

		$uploads = $services->get('RdnUpload\Container');
		$this->assertInstanceOf('RdnUpload\Container', $uploads);
	}
}
