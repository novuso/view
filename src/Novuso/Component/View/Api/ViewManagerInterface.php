<?php
/**
 * This file is part of the Novuso Framework
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */

namespace Novuso\Component\View\Api;

use Novuso\Component\View\Exception\DuplicateHelperException;
use Novuso\Component\View\Exception\InvalidKeyException;
use Novuso\Component\View\Exception\InvalidTemplateException;
use Novuso\Component\View\Exception\UndefinedAdapterException;
use Novuso\Component\View\Exception\ViewRenderException;

/**
 * ViewManagerInterface is the interface for a view manager
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */
interface ViewManagerInterface
{
    /**
     * Sets the view engine adapter
     *
     * @access public
     * @param  ViewAdapterInterface $adapter The ViewAdapterInterface instance
     * @return ViewManagerInterface This ViewManagerInterface instance
     */
    public function setAdapter(ViewAdapterInterface $adapter);

    /**
     * Retrieves the view engine adapter
     *
     * @access public
     * @return ViewAdapterInterface The ViewAdapterInterface instance
     * @throws UndefinedAdapterException If the adapter is not set
     */
    public function getAdapter();

    /**
     * Sets the template name
     *
     * @access public
     * @param  string $template The template name
     * @return ViewManagerInterface This ViewManagerInterface instance
     */
    public function setTemplate($template);

    /**
     * Retrieves the template name
     *
     * @access public
     * @return string The template name
     */
    public function getTemplate();

    /**
     * Checks if the template is found and readable
     *
     * @access public
     * @return boolean True if a template is found and readable; false otherwise
     */
    public function templateExists();

    /**
     * Sets the template file extension
     *
     * @access public
     * @param  string|null $extension The template file extension; null requires the template include extension
     * @return ViewManagerInterface This ViewManagerInterface instance
     */
    public function setExtension($extension);

    /**
     * Retrieves the template file extension
     *
     * @access public
     * @return string|null The template file extension or null if not set
     */
    public function getExtension();

    /**
     * Replaces all template directory paths
     *
     * @access public
     * @param  array $paths A list of template directory paths; first items have a higher match priority
     * @return ViewManagerInterface This ViewManagerInterface instance
     */
    public function replacePaths(array $paths);

    /**
     * Adds template directory paths
     *
     * @access public
     * @param  array   $paths   A list of template directory paths; first items have a higher match priority
     * @param  boolean $prepend Whether to prepend new items with a higher match priority or not
     * @return ViewManagerInterface This ViewManagerInterface instance
     */
    public function addPaths(array $paths, $prepend = false);

    /**
     * Adds a template directory path
     *
     * @access public
     * @param  string  $path    A template directory path
     * @param  boolean $prepend Whether to prepend existing list with a higher priority or not
     * @return ViewManagerInterface This ViewManagerInterface instance
     */
    public function addPath($path, $prepend = false);

    /**
     * Retrieves template directory paths
     *
     * @access public
     * @return array The list of template directory paths
     */
    public function getPaths();

    /**
     * Checks if a path is registered
     *
     * @access public
     * @return boolean True if the path is registered; false otherwise
     */
    public function hasPath($path);

    /**
     * Removes a path from the list
     *
     * @access public
     * @param  string $path A template directory path
     * @return ViewManagerInterface This ViewManagerInterface instance
     */
    public function removePath($path);

    /**
     * Clears all paths from the list
     *
     * @access public
     * @return ViewManagerInterface This ViewManagerInterface instance
     */
    public function clearPaths();

    /**
     * Sets view engine options
     *
     * @access public
     * @param  array $options An associated array of view engine options
     * @return ViewManagerInterface This ViewManagerInterface instance
     */
    public function setOptions(array $options);

    /**
     * Merges view engine options
     *
     * @access public
     * @param  array $options An associated array of view engine options
     * @return ViewManagerInterface This ViewManagerInterface instance
     */
    public function mergeOptions(array $options);

    /**
     * Sets a view engine option
     *
     * @access public
     * @param  string $key   The option key
     * @param  mixed  $value The option value
     * @return ViewManagerInterface This ViewManagerInterface instance
     */
    public function setOption($key, $value);

    /**
     * Retrieves view engine options
     *
     * @access public
     * @return array The associated array of view engine options
     */
    public function getOptions();

    /**
     * Retrieves a single option
     *
     * This method may return null for an option value or if the option is not set.
     * Use the hasOption method to determine whether an option is set or not.
     *
     * @access public
     * @param  string $key The option key
     * @return The option setting value or null if not set
     */
    public function getOption($key);

    /**
     * Checks if an option is defined
     *
     * @access public
     * @param  string $key The option key
     * @return boolean True if the option is defined; false otherwise
     */
    public function hasOption($key);

    /**
     * Removes an option
     *
     * @access public
     * @param  string $key The option key
     * @return ViewManagerInterface This ViewManagerInterface instance
     */
    public function removeOption($key);

    /**
     * Clears view engine options
     *
     * @access public
     * @return ViewManagerInterface This ViewManagerInterface instance
     */
    public function clearOptions();

    /**
     * Sets view data
     *
     * @access public
     * @param  array $data An associated array of view data; keys must follow PHP label name rules:
     *                     Starts with a letter or underscore, followed by letters, numbers, or underscores
     * @return ViewManagerInterface This ViewManagerInterface instance
     * @throws InvalidKeyException If any data keys are not valid
     */
    public function setData(array $data);

    /**
     * Merges view data
     *
     * @access public
     * @param  array $data An associated array of view data; keys must follow PHP label name rules:
     *                     Starts with a letter or underscore, followed by letters, numbers, or underscores
     * @return ViewManagerInterface This ViewManagerInterface instance
     * @throws InvalidKeyException If any data keys are not valid
     */
    public function mergeData(array $data);

    /**
     * Retrieves view data
     *
     * @access public
     * @return array An associated array of view data
     */
    public function getData();

    /**
     * Clears view data
     *
     * @access public
     * @return ViewManagerInterface This ViewManagerInterface instance
     */
    public function clearData();

    /**
     * Sets a data value
     *
     * @access public
     * @param  string $key   The data key; keys must follow PHP label name rules:
     *                       Starts with a letter or underscore, followed by letters, numbers, or underscores
     * @param  mixed  $value The data value
     * @throws InvalidKeyException If the key is not valid
     */
    public function set($key, $value);

    /**
     * Retrieves a data value
     *
     * @access public
     * @param  string $key     The data key; keys must follow PHP label name rules:
     *                         Starts with a letter or underscore, followed by letters, numbers, or underscores
     * @param  mixed  $default The value to return if the key is not defined
     * @return mixed The requested value or $default if not found
     */
    public function get($key, $default = null);

    /**
     * Checks if a data value is defined
     *
     * @access public
     * @param  string $key The data key; keys must follow PHP label name rules:
     *                     Starts with a letter or underscore, followed by letters, numbers, or underscores
     * @return boolean True if the key is defined; false otherwise
     */
    public function has($key);

    /**
     * Removes a data value
     *
     * @access public
     * @param  string $key The data key; keys must follow PHP label name rules:
     *                     Starts with a letter or underscore, followed by letters, numbers, or underscores
     * @return ViewManagerInterface This ViewManagerInterface instance
     */
    public function remove($key);

    /**
     * Adds a view helper
     *
     * @access public
     * @param  ViewHelperInterface $helper The ViewHelperInterface instance to add
     * @return ViewManagerInterface This ViewManagerInterface instance
     * @throws DuplicateHelperException If the view helper name is already registered
     * @throws InvalidKeyException      If the view helper name is an invalid key
     */
    public function addHelper(ViewHelperInterface $helper);

    /**
     * Retrieves view helpers
     *
     * @access public
     * @return array An associated array of view helpers; keyed by helper name
     */
    public function getHelpers();

    /**
     * Renders the view
     *
     * @access public
     * @return string The rendered view
     * @throws InvalidTemplateException  If the template cannot be found or read
     * @throws UndefinedAdapterException If the view engine adapter is not defined
     * @throws ViewRenderException       If the view engine adapter throws an exception
     */
    public function render();
}
