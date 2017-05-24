<?php

namespace ZfcTwitterBootstrapTest\View\Helper\Navigation;

use PHPUnit\Framework\TestCase;
use ZfcTwitterBootstrap\View\Helper\Navigation\Breadcrumbs;

class BreadcrumbTest extends TestCase
{
    /**
     * @var Breadcrumbs
     */
    protected $helper;

    public function setUp()
    {
        $this->helper = new Breadcrumbs();
        $this->helper->setView(new \Zend\View\Renderer\PhpRenderer());
        $this->helper->getView()->plugin('basePath')->setBasePath('/');

        $this->container = new \Zend\Navigation\Navigation(
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
                        ],
                        [
                            'label' => 'Page 2.2',
                            'id'    => 'p2-2',
                            'uri'   => 'p2-2',
                            'pages' => [
                                [
                                    'label'  => 'Page 2.2.1',
                                    'id'     => 'p2-2-1',
                                    'uri'    => 'p2-2-1',
                                    'active' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->helper->setContainer($this->container);
    }

    public function testBreadcrumbs()
    {
        $expected = '<ul class="breadcrumb">' . '<li><a href="p2">Page 2</a><span class="divider">/</span></li>' . '<li><a href="p2-2">Page 2.2</a><span class="divider">/</span></li>' . '<li class="active">Page 2.2.1</li></ul>';

        $actual = $this->helper->render();
        $this->assertEquals($expected, $actual);
    }

    public function testLinkLast()
    {
        $expected = '<ul class="breadcrumb">' . '<li><a href="p2">Page 2</a><span class="divider">/</span></li>' . '<li><a href="p2-2">Page 2.2</a><span class="divider">/</span></li>' . '<li class="active"><a href="p2-2-1">Page 2.2.1</a></li></ul>';
        $actual = $this->helper->setLinkLast(true)->render();
        $this->assertEquals($expected, $actual);
    }
}
