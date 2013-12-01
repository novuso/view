<?php
/**
 * This file is part of the Novuso Framework
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */

namespace Novuso\Component\View\Api;

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
}
