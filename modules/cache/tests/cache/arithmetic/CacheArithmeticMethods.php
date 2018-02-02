<?php
include_once(Kohana::find_file('tests/cache', 'CacheBasicMethodsTest'));

/**
 * @package    Kohana/Cache/Memcache
 * @group      kohana
 * @group      kohana.cache
 *
 * @category   Test
 *
 * @author     Kohana Team
 * @copyright  (c) 2009-2012 Kohana Team
 * @license    http://kohanaphp.com/license
 */
abstract class Kohana_CacheArithmeticMethodsTest extends Kohana_CacheBasicMethodsTest
{
    public function tearDown()
    {
        parent::tearDown();

        // Cleanup
        $cache = $this->cache();

        if ($cache instanceof Cache) {
            $cache->delete_all();
        }
    }

    /**
     * Provider for test_increment
     *
     * @return array
     */
    public function provider_increment()
    {
        return [
            [
                0,
                [
                    'id' => 'increment_test_1',
                    'step' => 1
                ],
                1
            ],
            [
                1,
                [
                    'id' => 'increment_test_2',
                    'step' => 1
                ],
                2
            ],
            [
                5,
                [
                    'id' => 'increment_test_3',
                    'step' => 5
                ],
                10
            ],
            [
                null,
                [
                    'id' => 'increment_test_4',
                    'step' => 1
                ],
                false
            ],
        ];
    }

    /**
     * Test for [Cache_Arithmetic::increment()]
     *
     * @dataProvider provider_increment
     *
     * @param   int  start state
     * @param   array    increment arguments
     * @param null|mixed $start_state
     * @param mixed      $expected
     */
    public function test_increment(
        $start_state = null,
        array $inc_args,
        $expected)
    {
        $cache = $this->cache();

        if ($start_state !== null) {
            $cache->set($inc_args['id'], $start_state, 0);
        }

        $this->assertSame(
            $expected,
            $cache->increment(
                $inc_args['id'],
                $inc_args['step']
            )
        );
    }

    /**
     * Provider for test_decrement
     *
     * @return array
     */
    public function provider_decrement()
    {
        return [
            [
                10,
                [
                    'id' => 'decrement_test_1',
                    'step' => 1
                ],
                9
            ],
            [
                10,
                [
                    'id' => 'decrement_test_2',
                    'step' => 2
                ],
                8
            ],
            [
                50,
                [
                    'id' => 'decrement_test_3',
                    'step' => 5
                ],
                45
            ],
            [
                null,
                [
                    'id' => 'decrement_test_4',
                    'step' => 1
                ],
                false
            ],
        ];
    }

    /**
     * Test for [Cache_Arithmetic::decrement()]
     *
     * @dataProvider provider_decrement
     *
     * @param   int  start state
     * @param   array    decrement arguments
     * @param null|mixed $start_state
     * @param mixed      $expected
     */
    public function test_decrement(
        $start_state = null,
        array $dec_args,
        $expected)
    {
        $cache = $this->cache();

        if ($start_state !== null) {
            $cache->set($dec_args['id'], $start_state, 0);
        }

        $this->assertSame(
            $expected,
            $cache->decrement(
                $dec_args['id'],
                $dec_args['step']
            )
        );
    }
} // End Kohana_CacheArithmeticMethodsTest
