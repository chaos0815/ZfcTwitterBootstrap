<?php
/**
 * ZfcTwitterBootstrap
 */

namespace ZfcTwitterBootstrap\View\Helper;

use Laminas\View\Helper\Navigation as ZendNavigation;

/**
 * Navigation
 */
class Navigation extends ZendNavigation
{
    /**
     * Default proxy to use in {@link render()}
     *
     * @var string
     */
    protected $defaultProxy = 'ztbMenu';

    /**
     * Default set of helpers to inject into the plugin manager
     *
     * @var array
     */
    protected $defaultPluginManagerHelpers = [
        'ztbBreadcrumbs' => 'ZfcTwitterBootstrap\View\Helper\Navigation\Breadcrumbs',
        'ztbMenu'        => 'ZfcTwitterBootstrap\View\Helper\Navigation\Menu',
    ];

    /**
     * Retrieve plugin loader for navigation helpers
     *
     * Lazy-loads an instance of Navigation\HelperLoader if none currently
     * registered.
     *
     * @return \Laminas\View\Helper\Navigation\PluginManager
     */
    public function getPluginManager()
    {
        $pm = parent::getPluginManager();
        foreach ($this->defaultPluginManagerHelpers as $name => $invokableClass) {
            $pm->setInvokableClass($name, $invokableClass);
        }

        return $pm;
    }
}
