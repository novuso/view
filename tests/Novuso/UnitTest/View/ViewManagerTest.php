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
use Novuso\Component\View\ViewManager;
use org\bovigo\vfs\vfsStream;
use Mockery;

class ViewManagerTest extends PHPUnit_Framework_TestCase
{
    protected $viewManager;
    protected $filesystem;

    public function setUp()
    {
        $this->viewManager = new ViewManager();
        $this->filesystem = vfsStream::setup('templates');
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            'Novuso\Component\View\Api\ViewManagerInterface',
            $this->viewManager
        );
    }

    public function testAdapterCanBeChanged()
    {
        $adapter1 = Mockery::mock('Novuso\Component\View\Api\ViewAdapterInterface');
        $adapter2 = Mockery::mock('Novuso\Component\View\Api\ViewAdapterInterface');
        $this->viewManager->setAdapter($adapter1);
        $this->assertSame($adapter1, $this->viewManager->getAdapter());
        $this->viewManager->setAdapter($adapter2);
        $this->assertSame($adapter2, $this->viewManager->getAdapter());
    }

    public function testAddingAndRemovingPaths()
    {
        $this->filesystem = vfsStream::create([
            'sections' => [],
            'content'  => []
        ]);
        $this->viewManager->addPaths([
            vfsStream::url('templates/sections'),
            vfsStream::url('templates/content')
        ]);
        $this->assertTrue($this->viewManager->hasPath(vfsStream::url('templates/sections')));
        $this->assertTrue($this->viewManager->hasPath(vfsStream::url('templates/content')));
        $this->viewManager->removePath(vfsStream::url('templates/sections'));
        $this->assertFalse($this->viewManager->hasPath(vfsStream::url('templates/sections')));
        $this->assertTrue($this->viewManager->hasPath(vfsStream::url('templates/content')));
        $this->viewManager->clearPaths();
        $this->assertFalse($this->viewManager->hasPath(vfsStream::url('templates/sections')));
        $this->assertFalse($this->viewManager->hasPath(vfsStream::url('templates/content')));
    }

    public function testTemplateExists()
    {
        $this->assertFalse($this->viewManager->templateExists());
        $this->filesystem = vfsStream::create([
            'index.html' => '<!DOCTYPE html><html><head></head><body></body></html>'
        ]);
        $this->viewManager->addPath(vfsStream::url('templates'));
        $this->viewManager->setExtension('html');
        $this->viewManager->setTemplate('index');
        $this->assertTrue($this->viewManager->templateExists());
        $this->viewManager->setTemplate('default');
        $this->assertFalse($this->viewManager->templateExists());
    }

    public function testSettingPathOrderWithPrepend()
    {
        $this->filesystem = vfsStream::create([
            'sections' => [
                'header.html' => 'parent header content',
                'footer.html' => 'parent footer content'
            ],
            'content'  => [
                'index.html' => 'parent index content'
            ],
            'children' => [
                'sections' => [
                    'header.html' => 'child header content'
                ],
                'content'  => [
                    'index.html' => 'child index content'
                ]
            ]
        ]);
        $this->viewManager->replacePaths([
            vfsStream::url('templates/sections'),
            vfsStream::url('templates/content')
        ]);
        $this->viewManager->addPaths([
            vfsStream::url('templates/children/sections'),
            vfsStream::url('templates/children/content')
        ], true);
        $this->viewManager->setExtension('html');
        $this->viewManager->setTemplate('index');
        $this->assertTrue($this->viewManager->templateExists());
        $paths = $this->viewManager->getPaths();
        $this->assertEquals('vfs://templates/children/sections', $paths[0]);
        $this->assertEquals('vfs://templates/children/content', $paths[1]);
        $this->assertEquals('vfs://templates/sections', $paths[2]);
        $this->assertEquals('vfs://templates/content', $paths[3]);
    }

    public function testSettingAndRemovingOptions()
    {
        $this->viewManager->setOptions(['foo' => 'bar']);
        $this->assertTrue($this->viewManager->hasOption('foo'));
        $this->assertEquals('bar', $this->viewManager->getOption('foo'));
        $this->viewManager->mergeOptions(['cache' => null, 'autoescape' => true]);
        $this->assertCount(3, $this->viewManager->getOptions());
        $this->assertTrue($this->viewManager->hasOption('foo'));
        $this->assertTrue($this->viewManager->hasOption('cache'));
        $this->viewManager->removeOption('foo');
        // note: unset options and null values will each return null
        $this->assertNull($this->viewManager->getOption('foo'));
        $this->assertNull($this->viewManager->getOption('cache'));
        $this->viewManager->setOption('config', ['key' => 'value']);
        $this->assertEquals(['key' => 'value'], $this->viewManager->getOption('config'));
        $this->viewManager->clearOptions();
        $this->assertCount(0, $this->viewManager->getOptions());
    }

    public function testSettingAndRemovingData()
    {
        $this->viewManager->setData(['foo' => 'bar']);
        $this->assertTrue($this->viewManager->has('foo'));
        $this->assertEquals('bar', $this->viewManager->get('foo'));
        $this->viewManager->mergeData(['title' => 'Welcome', 'template' => null]);
        $this->assertCount(3, $this->viewManager->getData());
        $this->assertTrue($this->viewManager->has('foo'));
        $this->assertTrue($this->viewManager->has('template'));
        $this->viewManager->remove('foo');
        // note: unset options and null values will each return null
        $this->assertNull($this->viewManager->get('foo'));
        $this->assertNull($this->viewManager->get('template'));
        $this->assertEquals('default', $this->viewManager->get('foo', 'default'));
        $this->viewManager->set('config', ['key' => 'value']);
        $this->assertEquals(['key' => 'value'], $this->viewManager->get('config'));
        $this->viewManager->clearData();
        $this->assertCount(0, $this->viewManager->getData());
    }

    public function testAddingViewHelpers()
    {
        $layoutHelper = Mockery::mock('Novuso\Component\View\Api\ViewHelperInterface');
        $layoutHelper
            ->shouldReceive('getName')
            ->once()
            ->andReturn('layout');
        $assetHelper = Mockery::mock('Novuso\Component\View\Api\ViewHelperInterface');
        $assetHelper
            ->shouldReceive('getName')
            ->once()
            ->andReturn('asset');
        $this->viewManager->addHelper($layoutHelper);
        $this->viewManager->addHelper($assetHelper);
        $this->assertCount(2, $this->viewManager->getHelpers());
    }

    public function testValidRenderCall()
    {
        $this->filesystem = vfsStream::create([
            'index.html' => '<!DOCTYPE html><html><head></head><body></body></html>'
        ]);
        $content = $this->filesystem->getChild('index.html')->getContent();
        $adapter = Mockery::mock('Novuso\Component\View\Api\ViewAdapterInterface');
        $adapter
            ->shouldReceive('setExtension')
            ->once()
            ->with('.html')
            ->andReturn(null);
        $adapter
            ->shouldReceive('setData')
            ->once()
            ->with([])
            ->andReturn(null);
        $adapter
            ->shouldReceive('setOptions')
            ->once()
            ->with([])
            ->andReturn(null);
        $adapter
            ->shouldReceive('setPaths')
            ->once()
            ->with([vfsStream::url('templates')])
            ->andReturn(null);
        $adapter
            ->shouldReceive('setTemplate')
            ->once()
            ->with('index')
            ->andReturn(null);
        $adapter
            ->shouldReceive('setHelpers')
            ->once()
            ->with([])
            ->andReturn(null);
        $adapter
            ->shouldReceive('render')
            ->once()
            ->andReturn($content);
        $this->viewManager->setAdapter($adapter);
        $this->viewManager->addPath(vfsStream::url('templates'));
        $this->viewManager->setExtension('html');
        $this->viewManager->setTemplate('index');
        $this->assertEquals('<!DOCTYPE html><html><head></head><body></body></html>', $this->viewManager->render());
    }

    /**
     * @expectedException Novuso\Component\View\Exception\UndefinedAdapterException
     */
    public function testGetAdapterThrowsExceptionWhenUndefined()
    {
        $this->viewManager->getAdapter();
    }

    /**
     * @expectedException Novuso\Component\View\Exception\InvalidKeyException
     */
    public function testSetThrowsExceptionOnInvalidKey()
    {
        $this->viewManager->set('*foo', 'bar');
    }

    /**
     * @expectedException Novuso\Component\View\Exception\InvalidKeyException
     */
    public function testAddHelperThrowsExceptionOnInvalidKey()
    {
        $layoutHelper = Mockery::mock('Novuso\Component\View\Api\ViewHelperInterface');
        $layoutHelper
            ->shouldReceive('getName')
            ->once()
            ->andReturn('layout-helper');
        $this->viewManager->addHelper($layoutHelper);
    }

    /**
     * @expectedException Novuso\Component\View\Exception\DuplicateHelperException
     */
    public function testAddHelperThrowsExceptionOnDuplicate()
    {
        $layoutHelper = Mockery::mock('Novuso\Component\View\Api\ViewHelperInterface');
        $layoutHelper
            ->shouldReceive('getName')
            ->once()
            ->andReturn('helper');
        $assetHelper = Mockery::mock('Novuso\Component\View\Api\ViewHelperInterface');
        $assetHelper
            ->shouldReceive('getName')
            ->once()
            ->andReturn('helper');
        $this->viewManager->addHelper($layoutHelper);
        $this->viewManager->addHelper($assetHelper);
    }

    /**
     * @expectedException Novuso\Component\View\Exception\InvalidTemplateException
     */
    public function testRenderThrowsExceptionOnInvalidTemplate()
    {
        $adapter = Mockery::mock('Novuso\Component\View\Api\ViewAdapterInterface');
        $this->viewManager->setAdapter($adapter);
        $this->viewManager->render();
    }

    /**
     * @expectedException Novuso\Component\View\Exception\ViewRenderException
     */
    public function testRenderThrowsCustomExceptionOnViewEngineRuntimeError()
    {
        $this->filesystem = vfsStream::create([
            'index.html' => '~~~garbled~input~~~'
        ]);
        $content = $this->filesystem->getChild('index.html')->getContent();
        $adapter = Mockery::mock('Novuso\Component\View\Api\ViewAdapterInterface');
        $adapter
            ->shouldReceive('setExtension')
            ->once()
            ->with('.html')
            ->andReturn(null);
        $adapter
            ->shouldReceive('setData')
            ->once()
            ->with([])
            ->andReturn(null);
        $adapter
            ->shouldReceive('setOptions')
            ->once()
            ->with([])
            ->andReturn(null);
        $adapter
            ->shouldReceive('setPaths')
            ->once()
            ->with([vfsStream::url('templates')])
            ->andReturn(null);
        $adapter
            ->shouldReceive('setTemplate')
            ->once()
            ->with('index')
            ->andReturn(null);
        $adapter
            ->shouldReceive('setHelpers')
            ->once()
            ->with([])
            ->andReturn(null);
        $adapter
            ->shouldReceive('render')
            ->once()
            ->andThrow(new \Exception('Unable to render template'));
        $this->viewManager->setAdapter($adapter);
        $this->viewManager->addPath(vfsStream::url('templates'));
        $this->viewManager->setExtension('html');
        $this->viewManager->setTemplate('index');
        $this->viewManager->render();
    }
}
