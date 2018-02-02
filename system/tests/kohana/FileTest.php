<?php defined('SYSPATH') or die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests Kohana File helper
 *
 * @group kohana
 * @group kohana.core
 * @group kohana.core.url
 *
 * @package    Kohana
 * @category   Tests
 *
 * @author     Kohana Team
 * @author     Jeremy Bush <contractfrombelow@gmail.com>
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_FileTest extends Unittest_TestCase
{
    /**
     * Provides test data for test_sanitize()
     *
     * @return array
     */
    public function provider_mime()
    {
        return [
            // $value, $result
            [Kohana::find_file('classes', 'File')],
            [Kohana::find_file('tests', 'test_data/github', 'png')],
        ];
    }

    /**
     * Tests File::mime()
     *
     * @test
     * @dataProvider provider_mime
     *
     * @param bool $input    Input for File::mime
     * @param bool $expected Output for File::mime
     */
    public function test_mime($input)
    {
        $this->markTestSkipped(
            'This test doesn\'t do anything useful!'
        );
        $this->assertSame(1, preg_match('/^(?:application|audio|image|message|multipart|text|video)\/[a-z.+0-9-]+$/i', File::mime($input)));
    }

    /**
     * Provides test data for test_split_join()
     *
     * @return array
     */
    public function provider_split_join()
    {
        return [
            // $value, $result
            [Kohana::find_file('tests', 'test_data/github', 'png'), .01, 1],
        ];
    }

    /**
     * Tests File::mime()
     *
     * @test
     * @dataProvider provider_split_join
     *
     * @param bool $input    Input for File::split
     * @param bool $peices   Input for File::split
     * @param bool $expected Output for File::splut
     */
    public function test_split_join($input, $peices, $expected)
    {
        $this->assertSame($expected, File::split($input, $peices));
        $this->assertSame($expected, File::join($input));

        foreach (glob(Kohana::find_file('tests', 'test_data/github', 'png') . '.*') as $file) {
            unlink($file);
        }
    }
}
