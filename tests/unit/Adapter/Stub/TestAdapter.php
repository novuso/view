<?php
/**
 * This file is part of the Novuso Framework
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */

namespace Novuso\Test\Unit\View\Adapter\Stub;

use Novuso\Component\View\Adapter\BaseViewAdapter;

class TestAdapter extends BaseViewAdapter
{
    public function getTemplate()
    {
        return $this->template;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function getPaths()
    {
        return $this->paths;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getHelpers()
    {
        return $this->helpers;
    }

    public function render()
    {
        return '';
    }
}
