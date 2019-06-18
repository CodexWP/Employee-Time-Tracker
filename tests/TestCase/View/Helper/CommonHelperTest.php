<?php
namespace App\Test\TestCase\View\Helper;

use App\View\Helper\CommonHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * App\View\Helper\CommonHelper Test Case
 */
class CommonHelperTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\View\Helper\CommonHelper
     */
    public $Common;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $view = new View();
        $this->Common = new CommonHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Common);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
