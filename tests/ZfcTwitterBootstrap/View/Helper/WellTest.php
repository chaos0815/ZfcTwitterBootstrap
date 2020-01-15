<?php
/**
 * ZfcTwitterBootstrap
 */

namespace ZfcTwitterBootstrapTest\View\Helper;

use PHPUnit\Framework\TestCase;
use ZfcTwitterBootstrap\View\Helper\Well;

class WellTest extends TestCase
{
    protected $helper;

    protected function setUp(): void
    {
        $this->helper = new Well();
    }

    public function testLarge()
    {
        $expected = '<div class="well well-large">foo</div>';
        $this->assertEquals($expected, $this->helper->large('foo'));
    }

    public function testSmall()
    {
        $expected = '<div class="well well-small">foo</div>';
        $this->assertEquals($expected, $this->helper->small('foo'));
    }

    public function testRender()
    {
        $expected = '<div class="well ">foo</div>';
        $this->assertEquals($expected, $this->helper->render('foo'));
    }

    public function testInvoke()
    {
        $expected = '<div class="well foo">foo</div>';
        $this->assertEquals($expected, $this->helper->__invoke('foo', 'foo'));
    }
}
