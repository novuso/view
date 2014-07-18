<?php
/**
 * This file is part of the Novuso Framework
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */

namespace Novuso\Test\Unit\View\Adapter;

use PHPUnit_Framework_TestCase;
use Novuso\Component\View\Adapter\TwigViewAdapter;
use org\bovigo\vfs\vfsStream;
use Mockery;

class TwigViewAdapterTest extends PHPUnit_Framework_TestCase
{
    protected $adapter;
    protected $filesystem;

    public function setUp()
    {
        $this->filesystem = vfsStream::setup('templates');
        $this->adapter = new TwigViewAdapter();
    }

    public function testRenderTemplate()
    {
        $test = <<<EOF
<!DOCTYPE html>
<html>
<head>
<title>{{ title }}</title>
</head>
<body>
    <h1>{{ content }}</h1>
</body>
</html>
EOF;
        $this->filesystem = vfsStream::create([
            'test.html' => $test
        ]);
        $this->adapter->setTemplate('test');
        $this->adapter->setExtension('.html');
        $this->adapter->setPaths([vfsStream::url('templates')]);
        $this->adapter->setOptions([]);
        $this->adapter->setData([
            'title'   => 'Welcome to the Twig test!',
            'content' => 'Hello Twig!'
        ]);
        $this->adapter->setHelpers([]);
        $html = $this->adapter->render();
        $this->assertContains('<title>Welcome to the Twig test!</title>', $html);
        $this->assertContains('<h1>Hello Twig!</h1>', $html);
    }

    public function testRenderHelpersInTemplate()
    {
        $index = <<<EOF
{{ layout.doctype|raw }}
<html lang="{{ lang }}">
<head>
<title>{{ title }}</title>
</head>
<body>
    <h1>{{ content }}</h1>
</body>
</html>
EOF;
        $this->filesystem = vfsStream::create([
            'index.html' => $index
        ]);
        $layoutHelper = Mockery::mock('Novuso\Component\View\Api\ViewHelperInterface');
        $layoutHelper->doctype = '<!DOCTYPE html>';
        $this->adapter->setTemplate('index');
        $this->adapter->setExtension('.html');
        $this->adapter->setPaths([vfsStream::url('templates')]);
        $this->adapter->setData([
            'lang'    => 'en_US',
            'title'   => 'Welcome to the Twig test!',
            'content' => 'Hello Twig!'
        ]);
        $this->adapter->setHelpers([
            'layout' => $layoutHelper
        ]);
        $html = $this->adapter->render();
        $this->assertContains('<title>Welcome to the Twig test!</title>', $html);
        $this->assertContains('<h1>Hello Twig!</h1>', $html);
        $this->assertContains('<!DOCTYPE html>', $html);
        $this->assertContains('<html lang="en_US">', $html);
    }
}
