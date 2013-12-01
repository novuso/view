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

    /**
     * {@inheritdoc}
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function templateExists()
    {
        if (empty($this->paths) || !isset($this->template)) {
            return false;
        }
        foreach ($this->getPaths() as $path) {
            $file = $path.DIRECTORY_SEPARATOR.$this->getTemplate().$this->getExtension();
            if (is_file($file) && is_readable($file)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setExtension($extension)
    {
        if (null !== $extension) {
            $extension = '.'.ltrim($extension, '.');
        }
        $this->extension = $extension;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * {@inheritdoc}
     */
    public function replacePaths(array $paths)
    {
        $this->paths = [];
        foreach ($paths as $path) {
            $this->addPaths($paths);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addPaths(array $paths, $prepend = false)
    {
        if ($prepend) {
            $paths = array_reverse($paths);
            foreach ($paths as $path) {
                $this->addPath($path, true);
            }
        } else {
            foreach ($paths as $path) {
                $this->addPath($path);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addPath($path, $prepend = false)
    {
        $path = (string) $path;
        if (!in_array($path, $this->paths)) {
            if ($prepend) {
                array_unshift($this->paths, $path);
            } else {
                $this->paths[] = $path;
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPath($path)
    {
        return in_array($path, $this->paths, true);
    }

    /**
     * {@inheritdoc}
     */
    public function removePath($path)
    {
        $key = array_search($path, $this->paths, true);
        if (false !== $key) {
            unset($this->paths[$key]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearPaths()
    {
        $this->paths = [];

        return $this;
    }
}
