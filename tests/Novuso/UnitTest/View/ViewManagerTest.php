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

    /**
     * @expectedException Novuso\Component\View\Exception\UndefinedAdapterException
     */
    public function testGetAdapterThrowsExceptionWhenUndefined()
    {
        $this->viewManager->getAdapter();
    }
}
