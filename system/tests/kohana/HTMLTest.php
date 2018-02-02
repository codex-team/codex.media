<?php defined('SYSPATH') or die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests HTML
 *
 * @group kohana
 * @group kohana.core
 * @group kohana.core.html
 *
 * @package    Kohana
 * @category   Tests
 *
 * @author     Kohana Team
 * @author     BRMatt <matthew@sigswitch.com>
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_HTMLTest extends Unittest_TestCase
{
    /**
     * Defaults for this test
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    protected $environmentDefault = [
        'Kohana::$base_url' => '/kohana/',
        'Kohana::$index_file' => 'index.php',
        'HTML::$strict' => true,
        'HTTP_HOST' => 'www.kohanaframework.org',
    ];
    // @codingStandardsIgnoreStart

    /**
     * Provides test data for test_attributes()
     *
     * @return array
     */
    public function provider_attributes()
    {
        return [
            [
                ['name' => 'field', 'random' => 'not_quite', 'id' => 'unique_field'],
                [],
                ' id="unique_field" name="field" random="not_quite"'
            ],
            [
                ['invalid' => null],
                [],
                ''
            ],
            [
                [],
                [],
                ''
            ],
            [
                ['name' => 'field', 'checked'],
                [],
                ' name="field" checked="checked"',
            ],
            [
                ['id' => 'disabled_field', 'disabled'],
                ['HTML::$strict' => false],
                ' id="disabled_field" disabled',
            ],
        ];
    }

    /**
     * Tests HTML::attributes()
     *
     * @test
     * @dataProvider provider_attributes
     *
     * @param array  $attributes Attributes to use
     * @param array  $options    Environment options to use
     * @param string $expected   Expected output
     */
    public function test_attributes(array $attributes, array $options, $expected)
    {
        $this->setEnvironment($options);

        $this->assertSame(
            $expected,
            HTML::attributes($attributes)
        );
    }

    /**
     * Provides test data for test_script
     *
     * @return array Array of test data
     */
    public function provider_script()
    {
        return [
            [
                '<script type="text/javascript" src="http://google.com/script.js"></script>',
                'http://google.com/script.js',
            ],
            [
                '<script type="text/javascript" src="http://www.kohanaframework.org/kohana/index.php/my/script.js"></script>',
                'my/script.js',
                null,
                'http',
                true
            ],
            [
                '<script type="text/javascript" src="https://www.kohanaframework.org/kohana/my/script.js"></script>',
                'my/script.js',
                null,
                'https',
                false
            ],
            [
                '<script type="text/javascript" src="https://www.kohanaframework.org/kohana/my/script.js"></script>',
                '/my/script.js', // Test absolute paths
                null,
                'https',
                false
            ],

        ];
    }

    /**
     * Tests HTML::script()
     *
     * @test
     * @dataProvider  provider_script
     *
     * @param string $expected   Expected output
     * @param string $file       URL to script
     * @param array  $attributes HTML attributes for the anchor
     * @param string $protocol   Protocol to use
     * @param bool   $index      Should the index file be included in url?
     */
    public function test_script($expected, $file, array $attributes = null, $protocol = null, $index = false)
    {
        $this->assertSame(
            $expected,
            HTML::script($file, $attributes, $protocol, $index)
        );
    }

    /**
     * Data provider for the style test
     *
     * @return array Array of test data
     */
    public function provider_style()
    {
        return [
            [
                '<link type="text/css" href="http://google.com/style.css" rel="stylesheet" />',
                'http://google.com/style.css',
                [],
                null,
                false
            ],
            [
                '<link type="text/css" href="/kohana/my/style.css" rel="stylesheet" />',
                'my/style.css',
                [],
                null,
                false
            ],
            [
                '<link type="text/css" href="https://www.kohanaframework.org/kohana/my/style.css" rel="stylesheet" />',
                'my/style.css',
                [],
                'https',
                false
            ],
            [
                '<link type="text/css" href="https://www.kohanaframework.org/kohana/index.php/my/style.css" rel="stylesheet" />',
                'my/style.css',
                [],
                'https',
                true
            ],
            [
                '<link type="text/css" href="https://www.kohanaframework.org/kohana/index.php/my/style.css" rel="stylesheet" />',
                '/my/style.css',
                [],
                'https',
                true
            ],
            [
                // #4283: http://dev.kohanaframework.org/issues/4283
                '<link type="text/css" href="https://www.kohanaframework.org/kohana/index.php/my/style.css" rel="stylesheet/less" />',
                'my/style.css',
                [
                    'rel' => 'stylesheet/less'
                ],
                'https',
                true
            ],
        ];
    }

    /**
     * Tests HTML::style()
     *
     * @test
     * @dataProvider  provider_style
     *
     * @param string $expected   The expected output
     * @param string $file       The file to link to
     * @param array  $attributes Any extra attributes for the link
     * @param string $protocol   Protocol to use
     * @param bool   $index      Whether the index file should be added to the link
     */
    public function test_style($expected, $file, array $attributes = null, $protocol = null, $index = false)
    {
        $this->assertSame(
            $expected,
            HTML::style($file, $attributes, $protocol, $index)
        );
    }

    /**
     * Provides test data for test_anchor
     *
     * @return array Test data
     */
    public function provider_anchor()
    {
        return [
            [
                '<a href="http://kohanaframework.org">Kohana</a>',
                [],
                'http://kohanaframework.org',
                'Kohana',
            ],
            [
                '<a href="http://google.com" target="_blank">GOOGLE</a>',
                [],
                'http://google.com',
                'GOOGLE',
                ['target' => '_blank'],
                'http',
            ],
            [
                '<a href="https://www.kohanaframework.org/kohana/users/example">Kohana</a>',
                [],
                'users/example',
                'Kohana',
                null,
                'https',
                false,
            ],
            [
                '<a href="https://www.kohanaframework.org/kohana/index.php/users/example">Kohana</a>',
                [],
                'users/example',
                'Kohana',
                null,
                'https',
                true,
            ],
            [
                '<a href="https://www.kohanaframework.org/kohana/index.php/users/example">Kohana</a>',
                [],
                'users/example',
                'Kohana',
                null,
                'https',
            ],
            [
                '<a href="https://www.kohanaframework.org/kohana/index.php/users/example">Kohana</a>',
                [],
                'users/example',
                'Kohana',
                null,
                'https',
                true,
            ],
            [
                '<a href="https://www.kohanaframework.org/kohana/users/example">Kohana</a>',
                [],
                'users/example',
                'Kohana',
                null,
                'https',
                false,
            ],
            [
                '<a href="https://www.kohanaframework.org/kohana/users/example">Kohana</a>',
                [],
                '/users/example',
                'Kohana',
                null,
                'https',
                false,
            ],
        ];
    }

    /**
     * Tests HTML::anchor
     *
     * @test
     * @dataProvider provider_anchor
     *
     * @param mixed      $expected
     * @param mixed      $uri
     * @param null|mixed $title
     * @param null|mixed $protocol
     * @param mixed      $index
     */
    public function test_anchor($expected, array $options, $uri, $title = null, array $attributes = null, $protocol = null, $index = true)
    {
        // $this->setEnvironment($options);

        $this->assertSame(
            $expected,
            HTML::anchor($uri, $title, $attributes, $protocol, $index)
        );
    }

    /**
     * Data provider for test_file_anchor
     *
     * @return array
     */
    public function provider_file_anchor()
    {
        return [
            [
                '<a href="/kohana/mypic.png">My picture file</a>',
                [],
                'mypic.png',
                'My picture file',
            ],
            [
                '<a href="https://www.kohanaframework.org/kohana/index.php/mypic.png" attr="value">My picture file</a>',
                ['attr' => 'value'],
                'mypic.png',
                'My picture file',
                'https',
                true
            ],
            [
                '<a href="ftp://www.kohanaframework.org/kohana/mypic.png">My picture file</a>',
                [],
                'mypic.png',
                'My picture file',
                'ftp',
                false
            ],
            [
                '<a href="ftp://www.kohanaframework.org/kohana/mypic.png">My picture file</a>',
                [],
                '/mypic.png',
                'My picture file',
                'ftp',
                false
            ],
        ];
    }

    /**
     * Test for HTML::file_anchor()
     *
     * @test
     * @covers HTML::file_anchor
     * @dataProvider provider_file_anchor
     *
     * @param mixed      $expected
     * @param mixed      $file
     * @param null|mixed $title
     * @param null|mixed $protocol
     * @param mixed      $index
     */
    public function test_file_anchor($expected, array $attributes, $file, $title = null, $protocol = null, $index = false)
    {
        $this->assertSame(
            $expected,
            HTML::file_anchor($file, $title, $attributes, $protocol, $index)
        );
    }
}
