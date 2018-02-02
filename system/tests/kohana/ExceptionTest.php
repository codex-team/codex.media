<?php defined('SYSPATH') or die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests Kohana Exception Class
 *
 * @group kohana
 * @group kohana.core
 * @group kohana.core.exception
 *
 * @package    Kohana
 * @category   Tests
 *
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_ExceptionTest extends Unittest_TestCase
{
    /**
     * Provides test data for test_constructor()
     *
     * @return array
     */
    public function provider_constructor()
    {
        return [
            [[''], '', 0],
            [[':a'], ':a', 0],

            [[':a', null], ':a', 0],
            [[':a', []], ':a', 0],
            [[':a', [':a' => 'b']], 'b', 0],
            [[':a :b', [':a' => 'c', ':b' => 'd']], 'c d', 0],

            [[':a', null, 5], ':a', 5],
            // #3358
            [[':a', null, '3F000'], ':a', '3F000'],
            // #3404
            [[':a', null, '42S22'], ':a', '42S22'],
            // #3927
            [[':a', null, 'b'], ':a', 'b'],
            // #4039
            [[':a', null, '25P01'], ':a', '25P01'],
        ];
    }

    /**
     * Tests Kohana_Kohana_Exception::__construct()
     *
     * @test
     * @dataProvider provider_constructor
     * @covers Kohana_Kohana_Exception::__construct
     *
     * @param array      $arguments        Arguments
     * @param string     $expected_message Value from getMessage()
     * @param int|string $expected_code    Value from getCode()
     */
    public function test_constructor($arguments, $expected_message, $expected_code)
    {
        switch (count($arguments)) {
            case 1:
                $exception = new Kohana_Exception(reset($arguments));
            break;
            case 2:
                $exception = new Kohana_Exception(reset($arguments), next($arguments));
            break;
            default:
                $exception = new Kohana_Exception(reset($arguments), next($arguments), next($arguments));
        }

        $this->assertSame($expected_code, $exception->getCode());
        $this->assertSame($expected_message, $exception->getMessage());
    }

    /**
     * Provides test data for test_text()
     *
     * @return array
     */
    public function provider_text()
    {
        return [
            [new Kohana_Exception('foobar'), $this->dirSeparator('Kohana_Exception [ 0 ]: foobar ~ SYSPATH/tests/kohana/ExceptionTest.php [ ' . __LINE__ . ' ]')],
        ];
    }

    /**
     * Tests Kohana_Exception::text()
     *
     * @test
     * @dataProvider provider_text
     * @covers Kohana_Exception::text
     *
     * @param object $exception exception to test
     * @param string $expected  expected output
     */
    public function test_text($exception, $expected)
    {
        $this->assertEquals($expected, Kohana_Exception::text($exception));
    }
}
