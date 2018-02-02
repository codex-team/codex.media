<?php defined('SYSPATH') or die('Kohana bootstrap needs to be included before tests run');

/**
 * Test for feed helper
 *
 * @group kohana
 * @group kohana.core
 * @group kohana.core.feed
 *
 * @package    Kohana
 * @category   Tests
 *
 * @author     Kohana Team
 * @author     Jeremy Bush <contractfrombelow@gmail.com>
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_FeedTest extends Unittest_TestCase
{
    /**
     * Provides test data for test_parse()
     *
     * @return array
     */
    public function provider_parse()
    {
        return [
            // $source, $expected
            ['http://dev.kohanaframework.org/projects/kohana3/activity.atom', 15],
        ];
    }

    /**
     * Tests that Feed::parse gets the correct number of elements
     *
     * @test
     * @dataProvider provider_parse
     * @covers feed::parse
     *
     * @param string $source   URL to test
     * @param int    $expected Count of items
     */
    public function test_parse($source, $expected)
    {
        $this->markTestSkipped('We don\'t go to the internet for tests.');

        $this->assertEquals($expected, count(Feed::parse($source)));
    }

    /**
     * Provides test data for test_create()
     *
     * @return array
     */
    public function provider_create()
    {
        $info = ['pubDate' => 123, 'image' => ['link' => 'http://kohanaframework.org/image.png', 'url' => 'http://kohanaframework.org/', 'title' => 'title']];

        return [
            // $source, $expected
            [$info, ['foo' => ['foo' => 'bar', 'pubDate' => 123, 'link' => 'foo']], ['_SERVER' => ['HTTP_HOST' => 'localhost'] + $_SERVER],
                [
                    'tag' => 'channel',
                    'descendant' => [
                        'tag' => 'item',
                        'child' => [
                            'tag' => 'foo',
                            'content' => 'bar'
                        ]
                    ]
                ],
                [
                    $this->matcher_composer($info, 'image', 'link'),
                    $this->matcher_composer($info, 'image', 'url'),
                    $this->matcher_composer($info, 'image', 'title')
                ]
            ],
        ];
    }

    /**
     * Helper for handy matcher composing
     *
     * @param array  $data
     * @param string $tag
     * @param string $child
     *
     * @return array
     */
    private function matcher_composer($data, $tag, $child)
    {
        return [
            'tag' => 'channel',
            'descendant' => [
                'tag' => $tag,
                'child' => [
                    'tag' => $child,
                    'content' => $data[$tag][$child]
                ]
            ]
        ];
    }

    /**
     * @test
     *
     * @dataProvider provider_create
     *
     * @covers feed::create
     *
     * @param string $info           info to pass
     * @param int    $items          items to add
     * @param int    $matcher        output
     * @param mixed  $enviroment
     * @param mixed  $matcher_item
     * @param mixed  $matchers_image
     */
    public function test_create($info, $items, $enviroment, $matcher_item, $matchers_image)
    {
        $this->setEnvironment($enviroment);

        $this->assertTag($matcher_item, Feed::create($info, $items), '', false);

        foreach ($matchers_image as $matcher_image) {
            $this->assertTag($matcher_image, Feed::create($info, $items), '', false);
        }
    }
}
