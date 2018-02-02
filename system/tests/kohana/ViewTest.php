<?php defined('SYSPATH') or die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests the View class
 *
 * @group kohana
 * @group kohana.core
 * @group kohana.core.view
 *
 * @package    Kohana
 * @category   Tests
 *
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_ViewTest extends Unittest_TestCase
{
    protected static $old_modules = [];

    /**
     * Setups the filesystem for test view files
     *
     */
    // @codingStandardsIgnoreStart
    public static function setupBeforeClass()
    // @codingStandardsIgnoreEnd
    {
        self::$old_modules = Kohana::modules();

        $new_modules = self::$old_modules + [
            'test_views' => realpath(dirname(__FILE__) . '/../test_data/')
        ];
        Kohana::modules($new_modules);
    }

    /**
     * Restores the module list
     *
     */
    // @codingStandardsIgnoreStart
    public static function teardownAfterClass()
    // @codingStandardsIgnoreEnd
    {
        Kohana::modules(self::$old_modules);
    }

    /**
     * Provider for test_instaniate
     *
     * @return array
     */
    public function provider_instantiate()
    {
        return [
            ['kohana/error', false],
            ['test.css', false],
            ['doesnt_exist', true],
        ];
    }

    /**
     * Tests that we can instantiate a view file
     *
     * @test
     * @dataProvider provider_instantiate
     *
     * @param mixed $path
     * @param mixed $expects_exception
     */
    public function test_instantiate($path, $expects_exception)
    {
        try {
            $view = new View($path);
            $this->assertSame(false, $expects_exception);
        } catch (View_Exception $e) {
            $this->assertSame(true, $expects_exception);
        }
    }
}
