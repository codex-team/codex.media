<?php defined('SYSPATH') or die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests URL
 *
 * @group kohana
 * @group kohana.core
 * @group kohana.core.url
 *
 * @package    Kohana
 * @category   Tests
 *
 * @author     Kohana Team
 * @author     BRMatt <matthew@sigswitch.com>
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_URLTest extends Unittest_TestCase
{
    /**
     * Default values for the environment, see setEnvironment
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    protected $environmentDefault = [
        'Kohana::$base_url' => '/kohana/',
        'Kohana::$index_file' => 'index.php',
        'HTTP_HOST' => 'example.com',
        '_GET' => [],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Provides test data for test_base()
     *
     * @return array
     */
    public function provider_base()
    {
        return [
            // $protocol, $index, $expected, $enviroment

            // Test with different combinations of parameters for max code coverage
            [null,    false, '/kohana/'],
            ['http',  false, 'http://example.com/kohana/'],
            [null,    true,  '/kohana/index.php/'],
            [null,    true,  '/kohana/index.php/'],
            ['http',  true,  'http://example.com/kohana/index.php/'],
            ['https', true,  'https://example.com/kohana/index.php/'],
            ['ftp',   true,  'ftp://example.com/kohana/index.php/'],

            // Test for automatic protocol detection, protocol = TRUE
            [true,    true,  'cli://example.com/kohana/index.php/', ['HTTPS' => false, 'Request::$initial' => Request::factory('/')->protocol('cli')]],

            // Change base url'
            ['https', false, 'https://example.com/kohana/', ['Kohana::$base_url' => 'omglol://example.com/kohana/']],

            // Use port in base url, issue #3307
            ['http', false, 'http://example.com:8080/', ['Kohana::$base_url' => 'example.com:8080/']],

            // Use protocol from base url if none specified
            [null,  false, 'http://www.example.com/', ['Kohana::$base_url' => 'http://www.example.com/']],

            // Use HTTP_HOST before SERVER_NAME
            ['http', false, 'http://example.com/kohana/', ['HTTP_HOST' => 'example.com', 'SERVER_NAME' => 'example.org']],

            // Use SERVER_NAME if HTTP_HOST DNX
            ['http',  false, 'http://example.org/kohana/', ['HTTP_HOST' => null, 'SERVER_NAME' => 'example.org']],
        ];
    }

    /**
     * Tests URL::base()
     *
     * @test
     * @dataProvider provider_base
     *
     * @param bool   $protocol   Parameter for Url::base()
     * @param bool   $index      Parameter for Url::base()
     * @param string $expected   Expected url
     * @param array  $enviroment Array of enviroment vars to change @see Kohana_URLTest::setEnvironment()
     */
    public function test_base($protocol, $index, $expected, array $enviroment = [])
    {
        $this->setEnvironment($enviroment);

        $this->assertSame(
            $expected,
            URL::base($protocol, $index)
        );
    }

    /**
     * Provides test data for test_site()
     *
     * @return array
     */
    public function provider_site()
    {
        return [
            ['', null,		'/kohana/index.php/'],
            ['', 'http',			'http://example.com/kohana/index.php/'],

            ['my/site', null, '/kohana/index.php/my/site'],
            ['my/site', 'http',  'http://example.com/kohana/index.php/my/site'],

            // @ticket #3110
            ['my/site/page:5', null, '/kohana/index.php/my/site/page:5'],
            ['my/site/page:5', 'http', 'http://example.com/kohana/index.php/my/site/page:5'],

            ['my/site?var=asd&kohana=awesome', null,  '/kohana/index.php/my/site?var=asd&kohana=awesome'],
            ['my/site?var=asd&kohana=awesome', 'http',  'http://example.com/kohana/index.php/my/site?var=asd&kohana=awesome'],

            ['?kohana=awesome&life=good', null, '/kohana/index.php/?kohana=awesome&life=good'],
            ['?kohana=awesome&life=good', 'http', 'http://example.com/kohana/index.php/?kohana=awesome&life=good'],

            ['?kohana=awesome&life=good#fact', null, '/kohana/index.php/?kohana=awesome&life=good#fact'],
            ['?kohana=awesome&life=good#fact', 'http', 'http://example.com/kohana/index.php/?kohana=awesome&life=good#fact'],

            ['some/long/route/goes/here?kohana=awesome&life=good#fact', null, '/kohana/index.php/some/long/route/goes/here?kohana=awesome&life=good#fact'],
            ['some/long/route/goes/here?kohana=awesome&life=good#fact', 'http', 'http://example.com/kohana/index.php/some/long/route/goes/here?kohana=awesome&life=good#fact'],

            ['/route/goes/here?kohana=awesome&life=good#fact', 'https', 'https://example.com/kohana/index.php/route/goes/here?kohana=awesome&life=good#fact'],
            ['/route/goes/here?kohana=awesome&life=good#fact', 'ftp', 'ftp://example.com/kohana/index.php/route/goes/here?kohana=awesome&life=good#fact'],
        ];
    }

    /**
     * Tests URL::site()
     *
     * @test
     * @dataProvider provider_site
     *
     * @param string      $uri        URI to use
     * @param bool|string $protocol   Protocol to use
     * @param string      $expected   Expected result
     * @param array       $enviroment Array of enviroment vars to set
     */
    public function test_site($uri, $protocol, $expected, array $enviroment = [])
    {
        $this->setEnvironment($enviroment);

        $this->assertSame(
            $expected,
            URL::site($uri, $protocol)
        );
    }

    /**
     * Provides test data for test_site_url_encode_uri()
     * See issue #2680
     *
     * @return array
     */
    public function provider_site_url_encode_uri()
    {
        $provider = [
            ['test', 'encode'],
            ['test', 'éñçø∂ë∂'],
            ['†éß†', 'encode'],
            ['†éß†', 'éñçø∂ë∂', 'µåñ¥'],
        ];

        foreach ($provider as $i => $params) {
            // Every non-ASCII character except for forward slash should be encoded...
            $expected = implode('/', array_map('rawurlencode', $params));

            // ... from a URI that is not encoded
            $uri = implode('/', $params);

            $provider[$i] = ["/kohana/index.php/{$expected}", $uri];
        }

        return $provider;
    }

    /**
     * Tests URL::site for proper URL encoding when working with non-ASCII characters.
     *
     * @test
     * @dataProvider provider_site_url_encode_uri
     *
     * @param mixed $expected
     * @param mixed $uri
     */
    public function test_site_url_encode_uri($expected, $uri)
    {
        $this->assertSame($expected, URL::site($uri, false));
    }

    /**
     * Provides test data for test_title()
     *
     * @return array
     */
    public function provider_title()
    {
        return [
            // Tests that..
            // Title is converted to lowercase
            ['we-shall-not-be-moved', 'WE SHALL NOT BE MOVED', '-'],
            // Excessive white space is removed and replaced with 1 char
            ['thissssss-is-it', 'THISSSSSS         IS       IT  ', '-'],
            // separator is either - (dash) or _ (underscore) & others are converted to underscores
            ['some-title', 'some title', '-'],
            ['some_title', 'some title', '_'],
            ['some!title', 'some title', '!'],
            ['some:title', 'some title', ':'],
            // Numbers are preserved
            ['99-ways-to-beat-apple', '99 Ways to beat apple', '-'],
            // ... with lots of spaces & caps
            ['99_ways_to_beat_apple', '99    ways   TO beat      APPLE', '_'],
            ['99-ways-to-beat-apple', '99    ways   TO beat      APPLE', '-'],
            // Invalid characters are removed
            ['each-gbp-is-now-worth-32-usd', 'Each GBP(£) is now worth 32 USD($)', '-'],
            // ... inc. separator
            ['is-it-reusable-or-re-usable', 'Is it reusable or re-usable?', '-'],
            // Doing some crazy UTF8 tests
            ['espana-wins', 'España-wins', '-', true],
        ];
    }

    /**
     * Tests URL::title()
     *
     * @test
     * @dataProvider provider_title
     *
     * @param string $title      Input to convert
     * @param string $separator  Seperate to replace invalid characters with
     * @param string $expected   Expected result
     * @param mixed  $ascii_only
     */
    public function test_title($expected, $title, $separator, $ascii_only = false)
    {
        $this->assertSame(
            $expected,
            URL::title($title, $separator, $ascii_only)
        );
    }

    /**
     * Provides test data for URL::query()
     *
     * @return array
     */
    public function provider_query()
    {
        return [
            [[], '', null],
            [['_GET' => ['test' => 'data']], '?test=data', null],
            [[], '?test=data', ['test' => 'data']],
            [['_GET' => ['more' => 'data']], '?more=data&test=data', ['test' => 'data']],
            [['_GET' => ['sort' => 'down']], '?test=data', ['test' => 'data'], false],

            // http://dev.kohanaframework.org/issues/3362
            [[], '', ['key' => null]],
            [[], '?key=0', ['key' => false]],
            [[], '?key=1', ['key' => true]],
            [['_GET' => ['sort' => 'down']], '?sort=down&key=1', ['key' => true]],
            [['_GET' => ['sort' => 'down']], '?sort=down&key=0', ['key' => false]],

            // @issue 4240
            [['_GET' => ['foo' => ['a' => 100]]], '?foo%5Ba%5D=100&foo%5Bb%5D=bar', ['foo' => ['b' => 'bar']]],
            [['_GET' => ['a' => 'a']], '?a=b', ['a' => 'b']],
        ];
    }

    /**
     * Tests URL::query()
     *
     * @test
     * @dataProvider provider_query
     *
     * @param array  $enviroment Set environment
     * @param string $expected   Expected result
     * @param array  $params     Query string
     * @param bool   $use_get    Combine with GET parameters
     */
    public function test_query($enviroment, $expected, $params, $use_get = true)
    {
        $this->setEnvironment($enviroment);

        $this->assertSame(
            $expected,
            URL::query($params, $use_get)
        );
    }
}
