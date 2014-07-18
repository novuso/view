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
use Novuso\Component\View\Exception\DuplicateHelperException;
use Novuso\Component\View\Exception\InvalidKeyException;
use Novuso\Component\View\Exception\InvalidTemplateException;
use Novuso\Component\View\Exception\UndefinedAdapterException;
use Novuso\Component\View\Exception\ViewRenderException;
use Exception;

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
            throw new UndefinedAdapterException();
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
        if (null !== $extension && '' !== $extension) {
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

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        $this->options = [];
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function mergeOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($key)
    {
        if (!array_key_exists($key, $this->options)) {
            return null;
        }

        return $this->options[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption($key)
    {
        return array_key_exists($key, $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function removeOption($key)
    {
        unset($this->options[$key]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearOptions()
    {
        $this->options = [];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data)
    {
        $this->data = [];
        $this->mergeData($data);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function mergeData(array $data)
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function clearData()
    {
        $this->data = [];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $key)) {
            throw new InvalidKeyException(__METHOD__, $key);
        }
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        if (!array_key_exists($key, $this->data)) {
            return $default;
        }

        return $this->data[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        unset($this->data[$key]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addHelper(ViewHelperInterface $helper)
    {
        $name = $helper->getName();
        if (array_key_exists($name, $this->helpers)) {
            throw new DuplicateHelperException($name);
        }
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $name)) {
            throw new InvalidKeyException(__METHOD__, $name);
        }
        $this->helpers[$name] = $helper;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHelpers()
    {
        return $this->helpers;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $adapter = $this->getAdapter();
        if (!$this->templateExists()) {
            throw new InvalidTemplateException($this->getTemplate().$this->getExtension(), $this->getPaths());
        }
        $adapter->setTemplate($this->getTemplate());
        $adapter->setExtension($this->getExtension());
        $adapter->setPaths($this->getPaths());
        $adapter->setOptions($this->getOptions());
        $adapter->setData($this->getData());
        $adapter->setHelpers($this->getHelpers());
        try {
            return $adapter->render();
        } catch (Exception $e) {
            throw new ViewRenderException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
