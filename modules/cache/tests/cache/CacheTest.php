<?php

/**
 * @package    Kohana/Cache
 * @group      kohana
 * @group      kohana.cache
 *
 * @category   Test
 *
 * @author     Kohana Team
 * @copyright  (c) 2009-2012 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_CacheTest extends PHPUnit_Framework_TestCase
{
    const BAD_GROUP_DEFINITION = 1010;
    const EXPECT_SELF = 1001;

    /**
     * Data provider for test_instance
     *
     * @return array
     */
    public function provider_instance()
    {
        $tmp = realpath(sys_get_temp_dir());

        $base = [];

        if (Kohana::$config->load('cache.file')) {
            $base = [
                // Test default group
                [
                    null,
                    Cache::instance('file')
                ],
                // Test defined group
                [
                    'file',
                    Cache::instance('file')
                ],
            ];
        }


        return [
            // Test bad group definition
            $base + [
                Kohana_CacheTest::BAD_GROUP_DEFINITION,
                'Failed to load Kohana Cache group: 1010'
            ],
        ];
    }

    /**
     * Tests the [Cache::factory()] method behaves as expected
     *
     * @dataProvider provider_instance
     *
     * @param mixed $group
     * @param mixed $expected
     */
    public function test_instance($group, $expected)
    {
        if (in_array($group, [
            Kohana_CacheTest::BAD_GROUP_DEFINITION,
            ]
        )) {
            $this->setExpectedException('Cache_Exception');
        }

        try {
            $cache = Cache::instance($group);
        } catch (Cache_Exception $e) {
            $this->assertSame($expected, $e->getMessage());
            throw $e;
        }

        $this->assertInstanceOf(get_class($expected), $cache);
        $this->assertSame($expected->config(), $cache->config());
    }

    /**
     * Tests that `clone($cache)` will be prevented to maintain singleton
     *
     * @expectedException Cache_Exception
     */
    public function test_cloning_fails()
    {
        if (! Kohana::$config->load('cache.file')) {
            $this->markTestSkipped('Unable to load File configuration');
        }

        try {
            $cache_clone = clone(Cache::instance('file'));
        } catch (Cache_Exception $e) {
            $this->assertSame('Cloning of Kohana_Cache objects is forbidden',
                $e->getMessage());
            throw $e;
        }
    }

    /**
     * Data provider for test_config
     *
     * @return array
     */
    public function provider_config()
    {
        return [
            [
                [
                    'server' => 'otherhost',
                    'port' => 5555,
                    'persistent' => true,
                ],
                null,
                Kohana_CacheTest::EXPECT_SELF,
                [
                    'server' => 'otherhost',
                    'port' => 5555,
                    'persistent' => true,
                ],
            ],
            [
                'foo',
                'bar',
                Kohana_CacheTest::EXPECT_SELF,
                [
                    'foo' => 'bar'
                ]
            ],
            [
                'server',
                null,
                null,
                []
            ],
            [
                null,
                null,
                [],
                []
            ]
        ];
    }

    /**
     * Tests the config method behaviour
     *
     * @dataProvider provider_config
     *
     * @param   mixed    key value to set or get
     * @param   mixed    value to set to key
     * @param   mixed    expected result from [Cache::config()]
     * @param   array    expected config within cache
     * @param mixed $key
     * @param mixed $value
     * @param mixed $expected_result
     */
    public function test_config($key, $value, $expected_result, array $expected_config)
    {
        $cache = $this->getMock('Cache_File', null, [], '', false);

        if ($expected_result === Kohana_CacheTest::EXPECT_SELF) {
            $expected_result = $cache;
        }

        $this->assertSame($expected_result, $cache->config($key, $value));
        $this->assertSame($expected_config, $cache->config());
    }

    /**
     * Data provider for test_sanitize_id
     *
     * @return array
     */
    public function provider_sanitize_id()
    {
        return [
            [
                'foo',
                'foo'
            ],
            [
                'foo+-!@',
                'foo+-!@'
            ],
            [
                'foo/bar',
                'foo_bar',
            ],
            [
                'foo\\bar',
                'foo_bar'
            ],
            [
                'foo bar',
                'foo_bar'
            ],
            [
                'foo\\bar snafu/stfu',
                'foo_bar_snafu_stfu'
            ]
        ];
    }

    /**
     * Tests the [Cache::_sanitize_id()] method works as expected.
     * This uses some nasty reflection techniques to access a protected
     * method.
     *
     * @dataProvider provider_sanitize_id
     *
     * @param   string    id
     * @param   string    expected
     * @param mixed $id
     * @param mixed $expected
     */
    public function test_sanitize_id($id, $expected)
    {
        $cache = $this->getMock('Cache', [
            'get',
            'set',
            'delete',
            'delete_all'
            ], [[]],
            '', false
        );

        $cache_reflection = new ReflectionClass($cache);
        $sanitize_id = $cache_reflection->getMethod('_sanitize_id');
        $sanitize_id->setAccessible(true);

        $this->assertSame($expected, $sanitize_id->invoke($cache, $id));
    }
} // End Kohana_CacheTest
