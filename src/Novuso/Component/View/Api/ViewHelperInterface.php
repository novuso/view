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
 * ViewHelperInterface is the interface for a view helper
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */
interface ViewHelperInterface
{
    /**
     * Retrieves the helper name
     *
     * @access public
     * @return string A name that follows PHP label name rules:
     *                Starts with a letter or underscore, followed by letters, numbers, or underscores
     */
    public function getName();
}
