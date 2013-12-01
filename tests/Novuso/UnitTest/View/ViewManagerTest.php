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
use Mockery;

class ViewManagerTest extends PHPUnit_Framework_TestCase
{
    protected $viewManager;

    public function setUp()
    {
        $this->viewManager = new ViewManager();
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

    /**
     * @expectedException Novuso\Component\View\Exception\UndefinedAdapterException
     */
    public function testGetAdapterThrowsExceptionWhenUndefined()
    {
        $this->viewManager->getAdapter();
    }
}
