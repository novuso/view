<?php
/**
 * This file is part of the Novuso Framework
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */

namespace Novuso\Component\View\Adapter;

use Twig_Loader_Filesystem;
use Twig_Environment;

/**
 * TwigViewAdapter handles the rendering of twig templates
 *
 * @author    John Nickell
 * @copyright Copyright (c) 2013, Novuso. (http://novuso.com)
 * @license   http://opensource.org/licenses/MIT The MIT License
 */
class TwigViewAdapter extends BaseViewAdapter
{
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $loader = new Twig_Loader_Filesystem($this->paths);
        $twig = new Twig_Environment($loader, $this->options);
        foreach ($this->helpers as $name => $helper) {
            $twig->addGlobal($name, $helper);
        }
        $template = $twig->loadTemplate($this->template.$this->extension);

        return $template->render($this->data);
    }
}
