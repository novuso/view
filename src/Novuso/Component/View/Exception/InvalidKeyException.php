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
class InvalidKeyException extends LogicException
{
    /**
     * Constructs InvalidKeyException
     *
     * @access public
     * @param  string $method The method throwing this Exception
     * @param  string $key    The invalid key
     */
    public function __construct($method, $key)
    {
        $text = 'View helper names and data keys may contain letters, numbers, or underscores. They must begin with '
            .'a letter or underscore. %s received "%s", which is an invalid key.';
        parent::__construct(sprintf($text, $method, $key));
    }
}
