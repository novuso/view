<?php
/**
 * This file is part of the Novuso Framework
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */

namespace Novuso\Component\View\Adapter;

use Novuso\Component\View\Api\ViewAdapterInterface;

/**
 * BaseViewAdapter is the base class for view adapters
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */
abstract class BaseViewAdapter implements ViewAdapterInterface
{
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
     * Template directory paths
     *
     * @access protected
     * @var    array
     */
    protected $paths = [];

    /**
     * View engine options
     *
     * @access protected
     * @var    array
     */
    protected $options = [];

    /**
     * View data
     *
     * @access protected
     * @var    array
     */
    protected $data = [];

    /**
     * View helpers
     *
     * @access protected
     * @var    array
     */
    protected $helpers = [];

    /**
     * {@inheritdoc}
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * {@inheritdoc}
     */
    public function setPaths(array $paths)
    {
        $this->paths = $paths;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function setHelpers(array $helpers)
    {
        $this->helpers = $helpers;
    }
}
