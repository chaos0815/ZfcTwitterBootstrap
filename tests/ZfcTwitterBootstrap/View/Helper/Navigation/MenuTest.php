<?php

namespace ZfcTwitterBootstrapTest\View\Helper\Navigation;

use PHPUnit\Framework\TestCase;
use ZfcTwitterBootstrap\View\Helper\Navigation\Menu;

class MenuTest extends TestCase
{
    /**
     * @var Menu
     */
    protected $helper;

    protected function setUp(): void
    {
        $this->helper = new Menu();
        $this->helper->setView(new \Laminas\View\Renderer\PhpRenderer());
        $this->helper->getView()->plugin('basePath')->setBasePath('/');
    }

    public function testDropdownWithMaxDepth()
    {
        $container = new \Laminas\Navigation\Navigation(
            [
                [
                    'label' => 'Page 1',
                    'id'    => 'p1',
                    'uri'   => 'p1',
                ],
                [
                    'label' => 'Page 2',
                    'id'    => 'p2',
                    'uri'   => 'p2',
                    'pages' => [
                        [
                            'label' => 'Page 2.1',
                            'id'    => 'p2-1',
                            'uri'   => 'p2-1',
                            'pages' => [
                                [
                                    'label' => 'Page 2.1.1',
                                    'id'    => 'p2-1-1',
                                    'uri'   => 'p2-1-1',
                                ],
                                [
                                    'label' => 'Page 2.1.2',
                                    'id'    => 'p2-1-2',
                                    'uri'   => 'p2-1-2',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Page 2.2',
                            'id'    => 'p2-2',
                            'uri'   => 'p2-2',
                        ],
                    ],
                ],
            ]
        );
        $expected = '<ul class="nav">
    <li>
        <a id="menu-p1" href="p1">Page 1</a>
    </li>
    <li class="dropdown">
        <a id="menu-p2" href="p2" data-toggle="dropdown" class=" dropdown-toggle">Page 2 <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li>
                <a id="menu-p2-1" href="p2-1">Page 2.1</a>
            </li>
            <li>
                <a id="menu-p2-2" href="p2-2">Page 2.2</a>
            </li>
        </ul>
    </li>
</ul>';

        $this->helper->setMaxDepth(1);
        $actual = $this->helper->renderMenu($container);

        $this->assertEquals($expected, $actual);
    }

    public function testRenderDeepestMenu()
    {
        $container = new \Laminas\Navigation\Navigation(
            [
                [
                    'label' => 'Page 1',
                    'id'    => 'p1',
                    'uri'   => 'p1',
                ],
                [
                    'label' => 'Page 2',
                    'id'    => 'p2',
                    'uri'   => 'p2',
                    'pages' => [
                        [
                            'label' => 'Page 2.1',
                            'id'    => 'p2-1',
                            'uri'   => 'p2-1',
                            'pages' => [
                                [
                                    'label'  => 'Page 2.1.1',
                                    'id'     => 'p2-1-1',
                                    'uri'    => 'p2-1-1',
                                    'active' => true,
                                ],
                                [
                                    'label' => 'Page 2.1.2',
                                    'id'    => 'p2-1-2',
                                    'uri'   => 'p2-1-2',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Page 2.2',
                            'id'    => 'p2-2',
                            'uri'   => 'p2-2',
                        ],
                    ],
                ],
            ]
        );
        $expected = '<ul class="nav">
    <li class="active">
        <a id="menu-p2-1-1" href="p2-1-1">Page 2.1.1</a>
    </li>
    <li>
        <a id="menu-p2-1-2" href="p2-1-2">Page 2.1.2</a>
    </li>
</ul>';

        $this->helper->setOnlyActiveBranch(true);
        $this->helper->setRenderParents(false);
        $actual = $this->helper->renderMenu($container);

        $actual = $this->helper->render($container);
        $this->assertEquals($expected, $actual);
    }

    public function testNoDropdownWhenRenderingDeepestMenu()
    {
        $container = new \Laminas\Navigation\Navigation(
            [
                [
                    'label' => 'Page 1',
                    'id'    => 'p1',
                    'uri'   => 'p1',
                ],
                [
                    'label' => 'Page 2',
                    'id'    => 'p2',
                    'uri'   => 'p2',
                    'pages' => [
                        [
                            'label' => 'Page 2.1',
                            'id'    => 'p2-1',
                            'uri'   => 'p2-1',
                            'pages' => [
                                [
                                    'label'  => 'Page 2.1.1',
                                    'id'     => 'p2-1-1',
                                    'uri'    => 'p2-1-1',
                                    'active' => true,
                                ],
                                [
                                    'label' => 'Page 2.1.2',
                                    'id'    => 'p2-1-2',
                                    'uri'   => 'p2-1-2',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Page 2.2',
                            'id'    => 'p2-2',
                            'uri'   => 'p2-2',
                        ],
                    ],
                ],
            ]
        );
        $expected = '<ul class="nav">
    <li class="active">
        <a id="menu-p2-1" href="p2-1">Page 2.1</a>
    </li>
    <li>
        <a id="menu-p2-2" href="p2-2">Page 2.2</a>
    </li>
</ul>';

        $this->helper->setOnlyActiveBranch(true);
        $this->helper->setRenderParents(false);
        $this->helper->setMaxDepth(1);
        $actual = $this->helper->renderMenu($container);

        $actual = $this->helper->render($container);
        $this->assertEquals($expected, $actual);
    }
}
