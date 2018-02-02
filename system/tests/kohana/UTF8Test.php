<?php defined('SYSPATH') or die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests Kohana_UTF8 class
 *
 * @group kohana
 * @group kohana.core
 * @group kohana.core.utf8
 *
 * @package    Kohana
 * @category   Tests
 *
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_UTF8Test extends Unittest_TestCase
{
    /**
     * Provides test data for test_clean()
     */
    public function provider_clean()
    {
        return [
            ["\0", ''],
            ["→foo\021", '→foo'],
            ["\x7Fbar", 'bar'],
            ["\xFF", ''],
            ["\x41", 'A'],
            [["→foo\021", "\x41"], ['→foo', 'A']],
        ];
    }

    /**
     * Tests UTF8::clean
     *
     * @test
     * @dataProvider provider_clean
     *
     * @param mixed $input
     * @param mixed $expected
     */
    public function test_clean($input, $expected)
    {
        $this->assertSame($expected, UTF8::clean($input));
    }

    /**
     * Provides test data for test_is_ascii()
     */
    public function provider_is_ascii()
    {
        return [
            ["\0", true],
            ["\$eno\r", true],
            ['Señor', false],
            [['Se', 'nor'], true],
            [['Se', 'ñor'], false],
        ];
    }

    /**
     * Tests UTF8::is_ascii
     *
     * @test
     * @dataProvider provider_is_ascii
     *
     * @param mixed $input
     * @param mixed $expected
     */
    public function test_is_ascii($input, $expected)
    {
        $this->assertSame($expected, UTF8::is_ascii($input));
    }

    /**
     * Provides test data for test_strip_ascii_ctrl()
     */
    public function provider_strip_ascii_ctrl()
    {
        return [
            ["\0", ''],
            ["→foo\021", '→foo'],
            ["\x7Fbar", 'bar'],
            ["\xFF", "\xFF"],
            ["\x41", 'A'],
        ];
    }

    /**
     * Tests UTF8::strip_ascii_ctrl
     *
     * @test
     * @dataProvider provider_strip_ascii_ctrl
     *
     * @param mixed $input
     * @param mixed $expected
     */
    public function test_strip_ascii_ctrl($input, $expected)
    {
        $this->assertSame($expected, UTF8::strip_ascii_ctrl($input));
    }

    /**
     * Provides test data for test_strip_non_ascii()
     */
    public function provider_strip_non_ascii()
    {
        return [
            ["\0\021\x7F", "\0\021\x7F"],
            ['I ♥ cocoñùт', 'I  coco'],
        ];
    }

    /**
     * Tests UTF8::strip_non_ascii
     *
     * @test
     * @dataProvider provider_strip_non_ascii
     *
     * @param mixed $input
     * @param mixed $expected
     */
    public function test_strip_non_ascii($input, $expected)
    {
        $this->assertSame($expected, UTF8::strip_non_ascii($input));
    }

    /**
     * Provides test data for test_transliterate_to_ascii()
     */
    public function provider_transliterate_to_ascii()
    {
        return [
            ['Cocoñùт', -1, 'Coconuт'],
            ['COCOÑÙТ', -1, 'COCOÑÙТ'],
            ['Cocoñùт', 0, 'Coconuт'],
            ['COCOÑÙТ', 0, 'COCONUТ'],
            ['Cocoñùт', 1, 'Cocoñùт'],
            ['COCOÑÙТ', 1, 'COCONUТ'],
        ];
    }

    /**
     * Tests UTF8::transliterate_to_ascii
     *
     * @test
     * @dataProvider provider_transliterate_to_ascii
     *
     * @param mixed $input
     * @param mixed $case
     * @param mixed $expected
     */
    public function test_transliterate_to_ascii($input, $case, $expected)
    {
        $this->assertSame($expected, UTF8::transliterate_to_ascii($input, $case));
    }

    /**
     * Provides test data for test_strlen()
     */
    public function provider_strlen()
    {
        return [
            ['Cocoñùт', 7],
            ['Coconut', 7],
        ];
    }

    /**
     * Tests UTF8::strlen
     *
     * @test
     * @dataProvider provider_strlen
     *
     * @param mixed $input
     * @param mixed $expected
     */
    public function test_strlen($input, $expected)
    {
        $this->assertSame($expected, UTF8::strlen($input));
    }

    /**
     * Provides test data for test_strpos()
     */
    public function provider_strpos()
    {
        return [
            ['Cocoñùт', 'o', 0, 1],
            ['Cocoñùт', 'ñ', 1, 4],
        ];
    }

    /**
     * Tests UTF8::strpos
     *
     * @test
     * @dataProvider provider_strpos
     *
     * @param mixed $input
     * @param mixed $str
     * @param mixed $offset
     * @param mixed $expected
     */
    public function test_strpos($input, $str, $offset, $expected)
    {
        $this->assertSame($expected, UTF8::strpos($input, $str, $offset));
    }

    /**
     * Provides test data for test_strrpos()
     */
    public function provider_strrpos()
    {
        return [
            ['Cocoñùт', 'o', 0, 3],
            ['Cocoñùт', 'ñ', 2, 4],
        ];
    }

    /**
     * Tests UTF8::strrpos
     *
     * @test
     * @dataProvider provider_strrpos
     *
     * @param mixed $input
     * @param mixed $str
     * @param mixed $offset
     * @param mixed $expected
     */
    public function test_strrpos($input, $str, $offset, $expected)
    {
        $this->assertSame($expected, UTF8::strrpos($input, $str, $offset));
    }

    /**
     * Provides test data for test_substr()
     */
    public function provider_substr()
    {
        return [
            ['Cocoñùт', 3, 2, 'oñ'],
            ['Cocoñùт', 3, 9, 'oñùт'],
            ['Cocoñùт', 3, null, 'oñùт'],
            ['Cocoñùт', 3, -2, 'oñ'],
        ];
    }

    /**
     * Tests UTF8::substr
     *
     * @test
     * @dataProvider provider_substr
     *
     * @param mixed $input
     * @param mixed $offset
     * @param mixed $length
     * @param mixed $expected
     */
    public function test_substr($input, $offset, $length, $expected)
    {
        $this->assertSame($expected, UTF8::substr($input, $offset, $length));
    }

    /**
     * Provides test data for test_substr_replace()
     */
    public function provider_substr_replace()
    {
        return [
            ['Cocoñùт', 'šš', 3, 2, 'Cocššùт'],
            ['Cocoñùт', 'šš', 3, 9, 'Cocšš'],
        ];
    }

    /**
     * Tests UTF8::substr_replace
     *
     * @test
     * @dataProvider provider_substr_replace
     *
     * @param mixed $input
     * @param mixed $replacement
     * @param mixed $offset
     * @param mixed $length
     * @param mixed $expected
     */
    public function test_substr_replace($input, $replacement, $offset, $length, $expected)
    {
        $this->assertSame($expected, UTF8::substr_replace($input, $replacement, $offset, $length));
    }

    /**
     * Provides test data for test_strtolower()
     */
    public function provider_strtolower()
    {
        return [
            ['COCOÑÙТ', 'cocoñùт'],
            ['JÄGER',   'jäger'],
        ];
    }

    /**
     * Tests UTF8::strtolower
     *
     * @test
     * @dataProvider provider_strtolower
     *
     * @param mixed $input
     * @param mixed $expected
     */
    public function test_strtolower($input, $expected)
    {
        $this->assertSame($expected, UTF8::strtolower($input));
    }

    /**
     * Provides test data for test_strtoupper()
     */
    public function provider_strtoupper()
    {
        return [
            ['Cocoñùт', 'COCOÑÙТ'],
            ['jäger',   'JÄGER'],
        ];
    }

    /**
     * Tests UTF8::strtoupper
     *
     * @test
     * @dataProvider provider_strtoupper
     *
     * @param mixed $input
     * @param mixed $expected
     */
    public function test_strtoupper($input, $expected)
    {
        $this->assertSame($expected, UTF8::strtoupper($input));
    }

    /**
     * Provides test data for test_ucfirst()
     */
    public function provider_ucfirst()
    {
        return [
            ['ñùт', 'Ñùт'],
        ];
    }

    /**
     * Tests UTF8::ucfirst
     *
     * @test
     * @dataProvider provider_ucfirst
     *
     * @param mixed $input
     * @param mixed $expected
     */
    public function test_ucfirst($input, $expected)
    {
        $this->assertSame($expected, UTF8::ucfirst($input));
    }

    /**
     * Provides test data for test_strip_non_ascii()
     */
    public function provider_ucwords()
    {
        return [
            ['ExAmple', 'ExAmple'],
            ['i ♥ Cocoñùт', 'I ♥ Cocoñùт'],
        ];
    }

    /**
     * Tests UTF8::ucwords
     *
     * @test
     * @dataProvider provider_ucwords
     *
     * @param mixed $input
     * @param mixed $expected
     */
    public function test_ucwords($input, $expected)
    {
        $this->assertSame($expected, UTF8::ucwords($input));
    }

    /**
     * Provides test data for test_strcasecmp()
     */
    public function provider_strcasecmp()
    {
        return [
            ['Cocoñùт',   'Cocoñùт', 0],
            ['Čau',       'Čauo',   -1],
            ['Čau',       'Ča',      1],
            ['Cocoñùт',   'Cocoñ',   4],
            ['Cocoñùт',   'Coco',    6],
        ];
    }

    /**
     * Tests UTF8::strcasecmp
     *
     * @test
     * @dataProvider provider_strcasecmp
     *
     * @param mixed $input
     * @param mixed $input2
     * @param mixed $expected
     */
    public function test_strcasecmp($input, $input2, $expected)
    {
        $this->assertSame($expected, UTF8::strcasecmp($input, $input2));
    }

    /**
     * Provides test data for test_str_ireplace()
     */
    public function provider_str_ireplace()
    {
        return [
            ['т', 't', 'cocoñuт', 'cocoñut'],
            ['Ñ', 'N', 'cocoñuт', 'cocoNuт'],
            [['т', 'Ñ', 'k' => 'k'], ['t', 'N', 'K'], ['cocoñuт'], ['cocoNut']],
            [['ñ'], 'n', 'cocoñuт', 'coconuт'],
        ];
    }

    /**
     * Tests UTF8::str_ireplace
     *
     * @test
     * @dataProvider provider_str_ireplace
     *
     * @param mixed $search
     * @param mixed $replace
     * @param mixed $subject
     * @param mixed $expected
     */
    public function test_str_ireplace($search, $replace, $subject, $expected)
    {
        $this->assertSame($expected, UTF8::str_ireplace($search, $replace, $subject));
    }

    /**
     * Provides test data for test_stristr()
     */
    public function provider_stristr()
    {
        return [
            ['Cocoñùт',   'oñ', 'oñùт'],
            ['Cocoñùт',   'o', 'ocoñùт'],
            ['Cocoñùт',   'k', false],
        ];
    }

    /**
     * Tests UTF8::stristr
     *
     * @test
     * @dataProvider provider_stristr
     *
     * @param mixed $input
     * @param mixed $input2
     * @param mixed $expected
     */
    public function test_stristr($input, $input2, $expected)
    {
        $this->assertSame($expected, UTF8::stristr($input, $input2));
    }

    /**
     * Provides test data for test_strspn()
     */
    public function provider_strspn()
    {
        return [
            ["foo", "o", 1, 2, 2],
            ['Cocoñùт', 'oñ', null, null, 1],
            ['Cocoñùт', 'oñ', 2, 4, 1],
            ['Cocoñùт', 'šš', 3, 9, 4],
        ];
    }

    /**
     * Tests UTF8::strspn
     *
     * @test
     * @dataProvider provider_strspn
     *
     * @param mixed $input
     * @param mixed $mask
     * @param mixed $offset
     * @param mixed $length
     * @param mixed $expected
     */
    public function test_strspn($input, $mask, $offset, $length, $expected)
    {
        $this->assertSame($expected, UTF8::strspn($input, $mask, $offset, $length));
    }

    /**
     * Provides test data for test_strcspn()
     */
    public function provider_strcspn()
    {
        return [
            ['Cocoñùт', 'oñ', null, null, 1],
            ['Cocoñùт', 'oñ', 2, 4, 1],
            ['Cocoñùт', 'šš', 3, 9, 4],
        ];
    }

    /**
     * Tests UTF8::strcspn
     *
     * @test
     * @dataProvider provider_strcspn
     *
     * @param mixed $input
     * @param mixed $mask
     * @param mixed $offset
     * @param mixed $length
     * @param mixed $expected
     */
    public function test_strcspn($input, $mask, $offset, $length, $expected)
    {
        $this->assertSame($expected, UTF8::strcspn($input, $mask, $offset, $length));
    }

    /**
     * Provides test data for test_str_pad()
     */
    public function provider_str_pad()
    {
        return [
            ['Cocoñùт', 10, 'š', STR_PAD_RIGHT, 'Cocoñùтššš'],
            ['Cocoñùт', 10, 'š', STR_PAD_LEFT,  'šššCocoñùт'],
            ['Cocoñùт', 10, 'š', STR_PAD_BOTH,  'šCocoñùтšš'],
        ];
    }

    /**
     * Tests UTF8::str_pad
     *
     * @test
     * @dataProvider provider_str_pad
     *
     * @param mixed $input
     * @param mixed $length
     * @param mixed $pad
     * @param mixed $type
     * @param mixed $expected
     */
    public function test_str_pad($input, $length, $pad, $type, $expected)
    {
        $this->assertSame($expected, UTF8::str_pad($input, $length, $pad, $type));
    }

    /**
     * Tests UTF8::str_pad error
     *
     * @test
     * @expectedException UTF8_Exception
     */
    public function test_str_pad_error()
    {
        UTF8::str_pad('Cocoñùт', 10, 'š', 15, 'šCocoñùтšš');
    }

    /**
     * Provides test data for test_str_split()
     */
    public function provider_str_split()
    {
        return [
            ['Bár',     1, ['B', 'á', 'r']],
            ['Cocoñùт', 2, ['Co', 'co', 'ñù', 'т']],
            ['Cocoñùт', 3, ['Coc', 'oñù', 'т']],
        ];
    }

    /**
     * Tests UTF8::str_split
     *
     * @test
     * @dataProvider provider_str_split
     *
     * @param mixed $input
     * @param mixed $split_length
     * @param mixed $expected
     */
    public function test_str_split($input, $split_length, $expected)
    {
        $this->assertSame($expected, UTF8::str_split($input, $split_length));
    }

    /**
     * Provides test data for test_strrev()
     */
    public function provider_strrev()
    {
        return [
            ['Cocoñùт', 'тùñocoC'],
        ];
    }

    /**
     * Tests UTF8::strrev
     *
     * @test
     * @dataProvider provider_strrev
     *
     * @param mixed $input
     * @param mixed $expected
     */
    public function test_strrev($input, $expected)
    {
        $this->assertSame($expected, UTF8::strrev($input));
    }

    /**
     * Provides test data for test_trim()
     */
    public function provider_trim()
    {
        return [
            [' bar ', null, 'bar'],
            ['bar',   'b',  'ar'],
            ['barb',  'b',  'ar'],
        ];
    }

    /**
     * Tests UTF8::trim
     *
     * @test
     * @dataProvider provider_trim
     *
     * @param mixed $input
     * @param mixed $input2
     * @param mixed $expected
     */
    public function test_trim($input, $input2, $expected)
    {
        $this->assertSame($expected, UTF8::trim($input, $input2));
    }

    /**
     * Provides test data for test_ltrim()
     */
    public function provider_ltrim()
    {
        return [
            [' bar ', null, 'bar '],
            ['bar',   'b',  'ar'],
            ['barb',  'b',  'arb'],
            ['ñùт',   'ñ',  'ùт'],
        ];
    }

    /**
     * Tests UTF8::ltrim
     *
     * @test
     * @dataProvider provider_ltrim
     *
     * @param mixed $input
     * @param mixed $charlist
     * @param mixed $expected
     */
    public function test_ltrim($input, $charlist, $expected)
    {
        $this->assertSame($expected, UTF8::ltrim($input, $charlist));
    }

    /**
     * Provides test data for test_rtrim()
     */
    public function provider_rtrim()
    {
        return [
            [' bar ', null, ' bar'],
            ['bar',   'b',  'bar'],
            ['barb',  'b',  'bar'],
            ['Cocoñùт',  'т',  'Cocoñù'],
        ];
    }

    /**
     * Tests UTF8::rtrim
     *
     * @test
     * @dataProvider provider_rtrim
     *
     * @param mixed $input
     * @param mixed $input2
     * @param mixed $expected
     */
    public function test_rtrim($input, $input2, $expected)
    {
        $this->assertSame($expected, UTF8::rtrim($input, $input2));
    }

    /**
     * Provides test data for test_ord()
     */
    public function provider_ord()
    {
        return [
            ['f', 102],
            ['ñ', 241],
            ['Ñ', 209],
        ];
    }

    /**
     * Tests UTF8::ord
     *
     * @test
     * @dataProvider provider_ord
     *
     * @param mixed $input
     * @param mixed $expected
     */
    public function test_ord($input, $expected)
    {
        $this->assertSame($expected, UTF8::ord($input));
    }
}
