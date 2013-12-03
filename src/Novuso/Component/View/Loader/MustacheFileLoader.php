<?php
/**
 * This file is part of the Novuso Framework
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */

namespace Novuso\Component\View\Loader;

use Mustache_Loader;
use Mustache_Exception_UnknownTemplateException;

/**
 * MustacheFileLoader loads templates for the MustacheViewAdapter
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */
class MustacheFileLoader implements Mustache_Loader
{
    /**
     * Template directory paths
     *
     * @access protected
     * @var    array
     */
    protected $paths;

    /**
     * Template file extension
     *
     * @access protected
     * @var    string|null
     */
    protected $extension;

    /**
     * Cached templates
     *
     * @access protected
     * @var    array
     */
    protected $templates = [];

    /**
     * Constructs MustacheFileLoader
     *
     * @access public
     * @param  array       $paths     The template directory paths
     * @param  string|null $extension The template file extension
     */
    public function __construct(array $paths, $extension = null)
    {
        $this->paths = $paths;
        $this->extension = $extension;
    }

    /**
     * Loads a template
     *
     * @access public
     * @param  string $name The template name
     * @return string The template contents
     */
    public function load($name)
    {
        $fileName = $this->getFileName($name);
        foreach ($this->paths as $path) {
            $file = $path.DIRECTORY_SEPARATOR.$fileName;
            if (is_file($file) && is_readable($file)) {
                if (!isset($this->templates[$file])) {
                    $this->templates[$file] = file_get_contents($file);
                }

                return $this->templates[$file];
            }
        }
        $message = sprintf($fileName.' >> paths: [%s]', implode(', ', $this->paths));
        throw new Mustache_Exception_UnknownTemplateException($message);
    }

    /**
     * Retrieves the template file name
     *
     * @access protected
     * @param  string $name The template name
     * @return string The template file name
     */
    protected function getFileName($name)
    {
        $extension = (string) $this->extension;
        if (substr($name, 0 - strlen($extension)) !== $extension) {
            $name .= $extension;
        }

        return $name;
    }
}
