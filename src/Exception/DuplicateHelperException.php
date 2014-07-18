<?php
/**
 * This file is part of the Novuso Framework
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */

namespace Novuso\Component\View\Exception;

use LogicException;

/**
 * InvalidHelperException is thrown when adding an invalid view helper
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */
class DuplicateHelperException extends LogicException
{
    /**
     * Constructs DuplicateHelperException
     *
     * @access public
     * @param  string $name The duplicate view helper name
     */
    public function __construct($name)
    {
        $message = sprintf('Cannot register a view helper with the name "%s". That name is already registered.', $name);
        parent::__construct($message);
    }
}
