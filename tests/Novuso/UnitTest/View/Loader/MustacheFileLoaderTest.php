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
use Novuso\Component\View\Loader\MustacheFileLoader;
use org\bovigo\vfs\vfsStream;

class MustacheFileLoaderTest extends PHPUnit_Framework_TestCase
{
    protected $loader;
    protected $filesystem;

    public function setUp()
    {
        $this->filesystem = vfsStream::setup('templates');
        $this->loader = new MustacheFileLoader([vfsStream::url('templates')], '.html');
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            'Mustache_Loader',
            $this->loader
        );
    }

    public function testLoadTemplate()
    {
        $layout = <<<EOF
<!DOCTYPE html>
<html>
<head>
<title>{{ title }}</title>
</head>
<body>
{{ content }}
</body>
</html>
EOF;
        $index = <<<EOF
<div id="content">
    <h1>Hello {{ name }}!</h1>
</div><!-- #content -->
EOF;
        $this->filesystem = vfsStream::create([
            'layout.html' => $layout,
            'content'     => [
                'index.html' => $index
            ]
        ]);
        $this->assertContains('Hello {{ name }}!', $this->loader->load('content/index'));
        $this->assertContains('<title>{{ title }}</title>', $this->loader->load('layout'));
    }

    /**
     * @expectedException Mustache_Exception_UnknownTemplateException
     */
    public function testUnknownTemplateException()
    {
        $this->loader->load('foo');
    }
}
