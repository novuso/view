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
 * UndefinedAdapterException is thrown when attempting to reference an undefined adapter
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */
class UndefinedAdapterException extends LogicException
{
    /**
     * Constructs UndefinedAdapterException
     *
     * @access public
     */
    public function __construct()
    {
        $message = 'The ViewAdapter is not set in the view component. You must pass an instance of '
            .'"Novuso\\Component\\View\\Api\\ViewAdapterInterface" to the '
            .'"Novuso\\Component\\View\\ViewManager::setAdapter" method to set the view engine adapter.';
        parent::__construct($message);
    }
}
