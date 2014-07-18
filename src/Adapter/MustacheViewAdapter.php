<?php
/**
 * This file is part of the Novuso Framework
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */

namespace Novuso\Component\View\Adapter;

use Novuso\Component\View\Loader\MustacheFileLoader;
use Mustache_Engine;

/**
 * MustacheViewAdapter handles the rendering of mustache templates
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */
class MustacheViewAdapter extends BaseViewAdapter
{
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $engine = new Mustache_Engine($this->resolveOptions());

        return $engine->render($this->template, $this->data);
    }

    /**
     * Resolves mustache engine options
     *
     * @access protected
     * @return array The resolved mustache engine options
     */
    protected function resolveOptions()
    {
        $loader = new MustacheFileLoader($this->paths, $this->extension);
        if (!isset($this->options['loader'])) {
            $this->options['loader'] = $loader;
        }
        if (!isset($this->options['partials_loader'])) {
            $this->options['partials_loader'] = $loader;
        }
        if (!isset($this->options['helpers'])) {
            $this->options['helpers'] = $this->helpers;
        } else {
            $this->options['helpers'] = array_merge($this->options['helpers'], $this->helpers);
        }

        return $this->options;
    }
}
