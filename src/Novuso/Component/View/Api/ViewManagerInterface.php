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
}
