<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TimesheetsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TimesheetsTable Test Case
 */
class TimesheetsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TimesheetsTable
     */
    public $Timesheets;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.timesheets',
        'app.companies'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Timesheets') ? [] : ['className' => TimesheetsTable::class];
        $this->Timesheets = TableRegistry::getTableLocator()->get('Timesheets', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Timesheets);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
