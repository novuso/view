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
 * InvalidTemplateException is thrown when a template cannot be found or read
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */
class InvalidTemplateException extends LogicException
{
    public function __construct($template, array $paths)
    {
        $message = sprintf(
            'Template "%s" does not exist or is not readable in paths: [%s]',
            $template,
            implode(', ', $paths)
        );
        parent::__construct($message);
    }
}
