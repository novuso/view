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
 * ViewAdapterInterface is the interface for a view engine adapter
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */
interface ViewAdapterInterface
{
    /**
     * Sets the template name
     *
     * @access public
     * @param  string $template The template name
     * @return void
     */
    public function setTemplate($template);

    /**
     * Sets an optional template file extension
     *
     * @access public
     * @param  string|null $extension The template file extension; null requires the template include extension
     * @return void
     */
    public function setExtension($extension);

    /**
     * Sets the list of template directory paths
     *
     * @access public
     * @param  array $paths A list of directories to seach for templates; first paths should have higher match priority
     * @return void
     */
    public function setPaths(array $paths);

    /**
     * Sets any engine specific options
     *
     * @access public
     * @param  array $options An associated array of engine specific options
     * @return void
     */
    public function setOptions(array $options);

    /**
     * Sets the context view data
     *
     * @access public
     * @param  array $data An associated array of data; keyed by variable names
     * @return void
     */
    public function setData(array $data);

    /**
     * Sets the view helpers
     *
     * @access public
     * @param  array $helpers An associated array of view helpers; keyed by helper name
     * @return void
     */
    public function setHelpers(array $helpers);

    /**
     * Renders the view
     *
     * @access public
     * @return string The rendered view
     */
    public function render();
}
