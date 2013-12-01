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
}
