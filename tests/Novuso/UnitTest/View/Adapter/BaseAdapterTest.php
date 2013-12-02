<?php
/**
 * This file is part of the Novuso Framework
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */

namespace Novuso\UnitTest\View;

use PHPUnit_Framework_TestCase;
use Novuso\UnitTest\View\Adapter\Stub\TestAdapter;
use Mockery;

class BaseAdapterTest extends PHPUnit_Framework_TestCase
{
    protected $adapter;

    public function setUp()
    {
        $this->adapter = new TestAdapter();
    }

    public function testSetTemplate()
    {
        $this->adapter->setTemplate('index');
        $this->assertEquals('index', $this->adapter->getTemplate());
    }

    public function testSetExtension()
    {
        $this->adapter->setExtension('.html');
        $this->assertEquals('.html', $this->adapter->getExtension());
    }

    public function testSetPaths()
    {
        $this->adapter->setPaths([__DIR__]);
        $this->assertEquals([__DIR__], $this->adapter->getPaths());
    }

    public function testSetOptions()
    {
        $this->adapter->setOptions(['autoescape' => true]);
        $this->assertEquals(['autoescape' => true], $this->adapter->getOptions());
    }

    public function testSetData()
    {
        $this->adapter->setData(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $this->adapter->getData());
    }

    public function testSetHelpers()
    {
        $helper = Mockery::mock('Novuso\Component\View\Api\ViewHelperInterface');
        $this->adapter->setHelpers(['helper' => $helper]);
        $helpers = $this->adapter->getHelpers();
        $this->assertSame($helper, $helpers['helper']);
    }
}
