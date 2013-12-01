<?php
/**
 * This file is part of the Novuso Framework
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */

namespace Novuso\Component\View;

use Novuso\Component\View\Api\ViewManagerInterface;
use Novuso\Component\View\Api\ViewAdapterInterface;
use Novuso\Component\View\Api\ViewHelperInterface;
use Novuso\Component\View\Exception\InvalidHelperException;
use Novuso\Component\View\Exception\InvalidTemplateException;
use Novuso\Component\View\Exception\UndefinedAdapterException;
use Novuso\Component\View\Exception\ViewRenderException;

/**
 * ViewManager is the entry point for the view component
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */
class ViewManager implements ViewManagerInterface
{
    /**
     * View adapter
     *
     * @access protected
     * @var    ViewAdapterInterface
     */
    protected $adapter;

    /**
     * Template name
     *
     * @access protected
     * @var    string
     */
    protected $template;

    /**
     * Template file extension
     *
     * @access protected
     * @var    string|null
     */
    protected $extension;

    /**
     * View data
     *
     * @access protected
     * @var    array
     */
    protected $data = [];

    /**
     * View engine options
     *
     * @access protected
     * @var    array
     */
    protected $options = [];

    /**
     * Template directory paths
     *
     * @access protected
     * @var    array
     */
    protected $paths = [];

    /**
     * View helpers
     *
     * @access protected
     * @var    ViewHelperInterface[]
     */
    protected $helpers = [];

    /**
     * {@inheritdoc}
     */
    public function setAdapter(ViewAdapterInterface $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdapter()
    {
        if (!isset($this->adapter)) {
            throw new UndefinedAdapterException('View adapter is not defined');
        }

        return $this->adapter;
    }
}
