<?php defined('SYSPATH') or die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests the Valid class
 *
 * @group kohana
 * @group kohana.core
 * @group kohana.core.valid
 *
 * @package    Kohana
 * @category   Tests
 *
 * @author     Kohana Team
 * @author     BRMatt <matthew@sigswitch.com>
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_ValidTest extends Unittest_TestCase
{
    /**
     * Provides test data for test_alpha()
     *
     * @return array
     */
    public function provider_alpha()
    {
        return [
            ['asdavafaiwnoabwiubafpowf', true],
            ['!aidhfawiodb', false],
            ['51535oniubawdawd78', false],
            ['!"£$(G$W£(HFW£F(HQ)"n', false],
            // UTF-8 tests
            ['あいうえお', true, true],
            ['¥', false, true],
            // Empty test
            ['', false, false],
            [null, false, false],
            [false, false, false],
        ];
    }

    /**
     * Tests Valid::alpha()
     *
     * Checks whether a string consists of alphabetical characters only.
     *
     * @test
     * @dataProvider provider_alpha
     *
     * @param string $string
     * @param bool   $expected
     * @param mixed  $utf8
     */
    public function test_alpha($string, $expected, $utf8 = false)
    {
        $this->assertSame(
            $expected,
            Valid::alpha($string, $utf8)
        );
    }

    /*
     * Provides test data for test_alpha_numeric
     */
    public function provide_alpha_numeric()
    {
        return [
            ['abcd1234',  true],
            ['abcd',      true],
            ['1234',      true],
            ['abc123&^/-', false],
            // UTF-8 tests
            ['あいうえお', true, true],
            ['零一二三四五', true, true],
            ['あい四五£^£^', false, true],
            // Empty test
            ['', false, false],
            [null, false, false],
            [false, false, false],
        ];
    }

    /**
     * Tests Valid::alpha_numeric()
     *
     * Checks whether a string consists of alphabetical characters and numbers only.
     *
     * @test
     * @dataProvider provide_alpha_numeric
     *
     * @param string $input    The string to test
     * @param bool   $expected Is $input valid
     * @param mixed  $utf8
     */
    public function test_alpha_numeric($input, $expected, $utf8 = false)
    {
        $this->assertSame(
            $expected,
            Valid::alpha_numeric($input, $utf8)
        );
    }

    /**
     * Provides test data for test_alpha_dash
     */
    public function provider_alpha_dash()
    {
        return [
            ['abcdef',     true],
            ['12345',      true],
            ['abcd1234',   true],
            ['abcd1234-',  true],
            ['abc123&^/-', false],
            // Empty test
            ['', false],
            [null, false],
            [false, false],
        ];
    }

    /**
     * Tests Valid::alpha_dash()
     *
     * Checks whether a string consists of alphabetical characters, numbers, underscores and dashes only.
     *
     * @test
     * @dataProvider provider_alpha_dash
     *
     * @param string $input         The string to test
     * @param bool   $contains_utf8 Does the string contain utf8 specific characters
     * @param bool   $expected      Is $input valid?
     */
    public function test_alpha_dash($input, $expected, $contains_utf8 = false)
    {
        if (! $contains_utf8) {
            $this->assertSame(
                $expected,
                Valid::alpha_dash($input)
            );
        }

        $this->assertSame(
            $expected,
            Valid::alpha_dash($input, true)
        );
    }

    /**
     * DataProvider for the valid::date() test
     */
    public function provider_date()
    {
        return [
            ['now',true],
            ['10 September 2010',true],
            ['+1 day',true],
            ['+1 week',true],
            ['+1 week 2 days 4 hours 2 seconds',true],
            ['next Thursday',true],
            ['last Monday',true],

            ['blarg',false],
            ['in the year 2000',false],
            ['324824',false],
            // Empty test
            ['', false],
            [null, false],
            [false, false],
        ];
    }

    /**
     * Tests Valid::date()
     *
     * @test
     * @dataProvider provider_date
     *
     * @param string $date     The date to validate
     * @param int    $expected
     */
    public function test_date($date, $expected)
    {
        $this->assertSame(
            $expected,
            Valid::date($date, $expected)
        );
    }

    /**
     * DataProvider for the valid::decimal() test
     */
    public function provider_decimal()
    {
        return [
            // Empty test
            ['', 2, null, false],
            [null, 2, null, false],
            [false, 2, null, false],
            ['45.1664', 3, null, false],
            ['45.1664', 4, null, true],
            ['45.1664', 4, 2, true],
            ['-45.1664', 4, null, true],
            ['+45.1664', 4, null, true],
            ['-45.1664', 3, null, false],
        ];
    }

    /**
     * Tests Valid::decimal()
     *
     * @test
     * @dataProvider provider_decimal
     *
     * @param string $decimal  The decimal to validate
     * @param int    $places   The number of places to check to
     * @param int    $digits   The number of digits preceding the point to check
     * @param bool   $expected Whether $decimal conforms to $places AND $digits
     */
    public function test_decimal($decimal, $places, $digits, $expected)
    {
        $this->assertSame(
            $expected,
            Valid::decimal($decimal, $places, $digits),
            'Decimal: "' . $decimal . '" to ' . $places . ' places and ' . $digits . ' digits (preceeding period)'
        );
    }

    /**
     * Provides test data for test_digit
     *
     * @return array
     */
    public function provider_digit()
    {
        return [
            ['12345',    true],
            ['10.5',     false],
            ['abcde',    false],
            ['abcd1234', false],
            ['-5',       false],
            [-5,         false],
            // Empty test
            ['',         false],
            [null,       false],
            [false,      false],
        ];
    }

    /**
     * Tests Valid::digit()
     *
     * @test
     * @dataProvider provider_digit
     *
     * @param mixed $input         Input to validate
     * @param bool  $expected      Is $input valid
     * @param mixed $contains_utf8
     */
    public function test_digit($input, $expected, $contains_utf8 = false)
    {
        if (! $contains_utf8) {
            $this->assertSame(
                $expected,
                Valid::digit($input)
            );
        }

        $this->assertSame(
            $expected,
            Valid::digit($input, true)
        );
    }

    /**
     * DataProvider for the valid::color() test
     */
    public function provider_color()
    {
        return [
            ['#000000', true],
            ['#GGGGGG', false],
            ['#AbCdEf', true],
            ['#000', true],
            ['#abc', true],
            ['#DEF', true],
            ['000000', true],
            ['GGGGGG', false],
            ['AbCdEf', true],
            ['000', true],
            ['DEF', true],
            // Empty test
            ['', false],
            [null, false],
            [false, false],
        ];
    }

    /**
     * Tests Valid::color()
     *
     * @test
     * @dataProvider provider_color
     *
     * @param string $color    The color to test
     * @param bool   $expected Is $color valid
     */
    public function test_color($color, $expected)
    {
        $this->assertSame(
            $expected,
            Valid::color($color)
        );
    }

    /**
     * Provides test data for test_credit_card()
     */
    public function provider_credit_card()
    {
        return [
            ['4222222222222',    'visa',       true],
            ['4012888888881881', 'visa',       true],
            ['4012888888881881', null,         true],
            ['4012888888881881', ['mastercard', 'visa'], true],
            ['4012888888881881', ['discover', 'mastercard'], false],
            ['4012888888881881', 'mastercard', false],
            ['5105105105105100', 'mastercard', true],
            ['6011111111111117', 'discover',   true],
            ['6011111111111117', 'visa',       false],
            // Empty test
            ['', null, false],
            [null, null, false],
            [false, null, false],
        ];
    }

    /**
     * Tests Valid::credit_card()
     *
     * @test
     * @covers Valid::credit_card
     * @dataProvider  provider_credit_card()
     *
     * @param string $number   Credit card number
     * @param string $type     Credit card type
     * @param bool   $expected
     */
    public function test_credit_card($number, $type, $expected)
    {
        $this->assertSame(
            $expected,
            Valid::credit_card($number, $type)
        );
    }

    /**
     * Provides test data for test_credit_card()
     */
    public function provider_luhn()
    {
        return [
            ['4222222222222', true],
            ['4012888888881881', true],
            ['5105105105105100', true],
            ['6011111111111117', true],
            ['60111111111111.7', false],
            ['6011111111111117X', false],
            ['6011111111111117 ', false],
            ['WORD ', false],
            // Empty test
            ['', false],
            [null, false],
            [false, false],
        ];
    }

    /**
     * Tests Valid::luhn()
     *
     * @test
     * @covers Valid::luhn
     * @dataProvider  provider_luhn()
     *
     * @param string $number   Credit card number
     * @param bool   $expected
     */
    public function test_luhn($number, $expected)
    {
        $this->assertSame(
            $expected,
            Valid::luhn($number)
        );
    }

    /**
     * Provides test data for test_email()
     *
     * @return array
     */
    public function provider_email()
    {
        return [
            ['foo', true,  false],
            ['foo', false, false],

            ['foo@bar', true, true],
            // RFC is less strict than the normal regex, presumably to allow
            //  admin@localhost, therefore we IGNORE IT!!!
            ['foo@bar', false, false],
            ['foo@bar.com', false, true],
            ['foo@barcom:80', false, false],
            ['foo@bar.sub.com', false, true],
            ['foo+asd@bar.sub.com', false, true],
            ['foo.asd@bar.sub.com', false, true],
            // RFC says 254 length max #4011
            [Text::random(null, 200) . '@' . Text::random(null, 50) . '.com', false, false],
            // Empty test
            ['', true, false],
            [null, true, false],
            [false, true, false],
        ];
    }

    /**
     * Tests Valid::email()
     *
     * Check an email address for correct format.
     *
     * @test
     * @dataProvider provider_email
     *
     * @param string $email   Address to check
     * @param bool   $strict  Use strict settings
     * @param bool   $correct Is $email address valid?
     */
    public function test_email($email, $strict, $correct)
    {
        $this->assertSame(
            $correct,
            Valid::email($email, $strict)
        );
    }

    /**
     * Returns test data for test_email_domain()
     *
     * @return array
     */
    public function provider_email_domain()
    {
        return [
            ['google.com', true],
            // Don't anybody dare register this...
            ['DAWOMAWIDAIWNDAIWNHDAWIHDAIWHDAIWOHDAIOHDAIWHD.com', false],
            // Empty test
            ['', false],
            [null, false],
            [false, false],
        ];
    }

    /**
     * Tests Valid::email_domain()
     *
     * Validate the domain of an email address by checking if the domain has a
     * valid MX record.
     *
     * Test skips on windows
     *
     * @test
     * @dataProvider provider_email_domain
     *
     * @param string $email   Email domain to check
     * @param bool   $correct Is it correct?
     */
    public function test_email_domain($email, $correct)
    {
        if (! $this->hasInternet()) {
            $this->markTestSkipped('An internet connection is required for this test');
        }

        if (! Kohana::$is_windows or version_compare(PHP_VERSION, '5.3.0', '>=')) {
            $this->assertSame(
                $correct,
                Valid::email_domain($email)
            );
        } else {
            $this->markTestSkipped('checkdnsrr() was not added on windows until PHP 5.3');
        }
    }

    /**
     * Provides data for test_exact_length()
     *
     * @return array
     */
    public function provider_exact_length()
    {
        return [
            ['somestring', 10, true],
            ['somestring', 11, false],
            ['anotherstring', 13, true],
            // Empty test
            ['', 10, false],
            [null, 10, false],
            [false, 10, false],
            // Test array of allowed lengths
            ['somestring', [1, 3, 5, 7, 9, 10], true],
            ['somestring', [1, 3, 5, 7, 9], false],
        ];
    }

    /**
     *
     * Tests Valid::exact_length()
     *
     * Checks that a field is exactly the right length.
     *
     * @test
     * @dataProvider provider_exact_length
     *
     * @param string $string  The string to length check
     * @param int    $length  The length of the string
     * @param bool   $correct Is $length the actual length of the string?
     *
     * @return bool
     */
    public function test_exact_length($string, $length, $correct)
    {
        return $this->assertSame(
            $correct,
            Valid::exact_length($string, $length),
            'Reported string length is not correct'
        );
    }

    /**
     * Provides data for test_equals()
     *
     * @return array
     */
    public function provider_equals()
    {
        return [
            ['foo', 'foo', true],
            ['1', '1', true],
            [1, '1', false],
            ['011', 011, false],
            // Empty test
            ['', 123, false],
            [null, 123, false],
            [false, 123, false],
        ];
    }

    /**
     * Tests Valid::equals()
     *
     * @test
     * @dataProvider provider_equals
     *
     * @param string $string   value to check
     * @param int    $required required value
     * @param bool   $correct  is $string the same as $required?
     *
     * @return bool
     */
    public function test_equals($string, $required, $correct)
    {
        return $this->assertSame(
            $correct,
            Valid::equals($string, $required),
            'Values are not equal'
        );
    }

    /**
     * DataProvider for the valid::ip() test
     *
     * @return array
     */
    public function provider_ip()
    {
        return [
            ['75.125.175.50',   false, true],
            // PHP 5.3.6 fixed a bug that allowed 127.0.0.1 as a public ip: http://bugs.php.net/53150
            ['127.0.0.1',       false, version_compare(PHP_VERSION, '5.3.6', '<')],
            ['256.257.258.259', false, false],
            ['255.255.255.255', false, false],
            ['192.168.0.1',     false, false],
            ['192.168.0.1',     true,  true],
            // Empty test
            ['', true, false],
            [null, true, false],
            [false, true, false],
        ];
    }

    /**
     * Tests Valid::ip()
     *
     * @test
     * @dataProvider  provider_ip
     *
     * @param string $input_ip
     * @param bool   $allow_private
     * @param bool   $expected_result
     */
    public function test_ip($input_ip, $allow_private, $expected_result)
    {
        $this->assertEquals(
            $expected_result,
            Valid::ip($input_ip, $allow_private)
        );
    }

    /**
     * Returns test data for test_max_length()
     *
     * @return array
     */
    public function provider_max_length()
    {
        return [
            // Border line
            ['some', 4, true],
            // Exceeds
            ['KOHANARULLLES', 2, false],
            // Under
            ['CakeSucks', 10, true],
            // Empty test
            ['', -10, false],
            [null, -10, false],
            [false, -10, false],
        ];
    }

    /**
     * Tests Valid::max_length()
     *
     * Checks that a field is short enough.
     *
     * @test
     * @dataProvider provider_max_length
     *
     * @param string $string    String to test
     * @param int    $maxlength Max length for this string
     * @param bool   $correct   Is $string <= $maxlength
     */
    public function test_max_length($string, $maxlength, $correct)
    {
        $this->assertSame(
            $correct,
            Valid::max_length($string, $maxlength)
        );
    }

    /**
     * Returns test data for test_min_length()
     *
     * @return array
     */
    public function provider_min_length()
    {
        return [
            ['This is obviously long enough', 10, true],
            ['This is not', 101, false],
            ['This is on the borderline', 25, true],
            // Empty test
            ['', 10, false],
            [null, 10, false],
            [false, 10, false],
        ];
    }

    /**
     * Tests Valid::min_length()
     *
     * Checks that a field is long enough.
     *
     * @test
     * @dataProvider provider_min_length
     *
     * @param string $string    String to compare
     * @param int    $minlength The minimum allowed length
     * @param bool   $correct   Is $string 's length >= $minlength
     */
    public function test_min_length($string, $minlength, $correct)
    {
        $this->assertSame(
            $correct,
            Valid::min_length($string, $minlength)
        );
    }

    /**
     * Returns test data for test_not_empty()
     *
     * @return array
     */
    public function provider_not_empty()
    {
        // Create a blank arrayObject
        $ao = new ArrayObject;

        // arrayObject with value
        $ao1 = new ArrayObject;
        $ao1['test'] = 'value';

        return [
            [[],      false],
            [null,         false],
            ['',           false],
            [$ao,          false],
            [$ao1,         true],
            [[null],  true],
            [0,            true],
            ['0',          true],
            ['Something',  true],
        ];
    }

    /**
     * Tests Valid::not_empty()
     *
     * Checks if a field is not empty.
     *
     * @test
     * @dataProvider provider_not_empty
     *
     * @param mixed $value Value to check
     * @param bool  $empty Is the value really empty?
     */
    public function test_not_empty($value, $empty)
    {
        return $this->assertSame(
            $empty,
            Valid::not_empty($value)
        );
    }

    /**
     * DataProvider for the Valid::numeric() test
     */
    public function provider_numeric()
    {
        return [
            [12345,   true],
            [123.45,  true],
            ['12345', true],
            ['10.5',  true],
            ['-10.5', true],
            ['10.5a', false],
            // @issue 3240
            [.4,      true],
            [-.4,     true],
            [4.,      true],
            [-4.,     true],
            ['.5',    true],
            ['-.5',   true],
            ['5.',    true],
            ['-5.',   true],
            ['.',     false],
            ['1.2.3', false],
            // Empty test
            ['', false],
            [null, false],
            [false, false],
        ];
    }

    /**
     * Tests Valid::numeric()
     *
     * @test
     * @dataProvider provider_numeric
     *
     * @param string $input    Input to test
     * @param bool   $expected Whether or not $input is numeric
     */
    public function test_numeric($input, $expected)
    {
        $this->assertSame(
            $expected,
            Valid::numeric($input)
        );
    }

    /**
     * Provides test data for test_phone()
     *
     * @return array
     */
    public function provider_phone()
    {
        return [
            ['0163634840',       null, true],
            ['+27173634840',     null, true],
            ['123578',           null, false],
            // Some uk numbers
            ['01234456778',      null, true],
            ['+0441234456778',   null, false],
            // Google UK case you're interested
            ['+44 20-7031-3000', [12], true],
            // BT Corporate
            ['020 7356 5000',	  null, true],
            // Empty test
            ['', null, false],
            [null, null, false],
            [false, null, false],
        ];
    }

    /**
     * Tests Valid::phone()
     *
     * @test
     * @dataProvider  provider_phone
     *
     * @param string $phone    Phone number to test
     * @param bool   $expected Is $phone valid
     * @param mixed  $lengths
     */
    public function test_phone($phone, $lengths, $expected)
    {
        $this->assertSame(
            $expected,
            Valid::phone($phone, $lengths)
        );
    }

    /**
     * DataProvider for the valid::regex() test
     */
    public function provider_regex()
    {
        return [
            ['hello world', '/[a-zA-Z\s]++/', true],
            ['123456789', '/[0-9]++/', true],
            ['£$%£%', '/[abc]/', false],
            ['Good evening',  '/hello/',  false],
            // Empty test
            ['', '/hello/', false],
            [null, '/hello/', false],
            [false, '/hello/', false],
        ];
    }

    /**
     * Tests Valid::range()
     *
     * Tests if a number is within a range.
     *
     * @test
     * @dataProvider provider_regex
     *
     * @param string $value    Value to test against
     * @param string $regex    Valid pcre regular expression
     * @param bool   $expected Does the value match the expression?
     */
    public function test_regex($value, $regex, $expected)
    {
        $this->AssertSame(
            $expected,
            Valid::regex($value, $regex)
        );
    }

    /**
     * DataProvider for the valid::range() test
     */
    public function provider_range()
    {
        return [
            [1,  0,  2, null, true],
            [-1, -5, 0, null, true],
            [-1, 0,  1, null, false],
            [1,  0,  0, null, false],
            [2147483647, 0, 200000000000000, null, true],
            [-2147483647, -2147483655, 2147483645, null, true],
            // #4043
            [2, 0, 10, 2, true],
            [3, 0, 10, 2, false],
            // Empty test
            ['', 5, 10, null, false],
            [null, 5, 10, null, false],
            [false, 5, 10, null, false],
        ];
    }

    /**
     * Tests Valid::range()
     *
     * Tests if a number is within a range.
     *
     * @test
     * @dataProvider provider_range
     *
     * @param int   $number   Number to test
     * @param int   $min      Lower bound
     * @param int   $max      Upper bound
     * @param bool  $expected Is Number within the bounds of $min && $max
     * @param mixed $step
     */
    public function test_range($number, $min, $max, $step, $expected)
    {
        $this->AssertSame(
            $expected,
            Valid::range($number, $min, $max, $step)
        );
    }

    /**
     * Provides test data for test_url()
     *
     * @return array
     */
    public function provider_url()
    {
        $data = [
            ['http://google.com', true],
            ['http://google.com/', true],
            ['http://google.com/?q=abc', true],
            ['http://google.com/#hash', true],
            ['http://localhost', true],
            ['http://hello-world.pl', true],
            ['http://hello--world.pl', true],
            ['http://h.e.l.l.0.pl', true],
            ['http://server.tld/get/info', true],
            ['http://127.0.0.1', true],
            ['http://127.0.0.1:80', true],
            ['http://user@127.0.0.1', true],
            ['http://user:pass@127.0.0.1', true],
            ['ftp://my.server.com', true],
            ['rss+xml://rss.example.com', true],

            ['http://google.2com', false],
            ['http://google.com?q=abc', false],
            ['http://google.com#hash', false],
            ['http://hello-.pl', false],
            ['http://hel.-lo.world.pl', false],
            ['http://ww£.google.com', false],
            ['http://127.0.0.1234', false],
            ['http://127.0.0.1.1', false],
            ['http://user:@127.0.0.1', false],
            ["http://finalnewline.com\n", false],
            // Empty test
            ['', false],
            [null, false],
            [false, false],
        ];

        $data[] = ['http://' . str_repeat('123456789.', 25) . 'com/', true]; // 253 chars
        $data[] = ['http://' . str_repeat('123456789.', 25) . 'info/', false]; // 254 chars

        return $data;
    }

    /**
     * Tests Valid::url()
     *
     * @test
     * @dataProvider provider_url
     *
     * @param string $url      The url to test
     * @param bool   $expected Is it valid?
     */
    public function test_url($url, $expected)
    {
        $this->assertSame(
            $expected,
            Valid::url($url)
        );
    }

    /**
     * DataProvider for the valid::matches() test
     */
    public function provider_matches()
    {
        return [
            [['a' => 'hello', 'b' => 'hello'], 'a', 'b', true],
            [['a' => 'hello', 'b' => 'hello '], 'a', 'b', false],
            [['a' => '1', 'b' => 1], 'a', 'b', false],
            // Empty test
            [['a' => '', 'b' => 'hello'], 'a', 'b', false],
            [['a' => null, 'b' => 'hello'], 'a', 'b', false],
            [['a' => false, 'b' => 'hello'], 'a', 'b', false],
        ];
    }

    /**
     * Tests Valid::matches()
     *
     * Tests if a field matches another from an array of data
     *
     * @test
     * @dataProvider provider_matches
     *
     * @param array $data     Array of fields
     * @param int   $field    First field name
     * @param int   $match    Field name that must match $field in $data
     * @param bool  $expected Do the two fields match?
     */
    public function test_matches($data, $field, $match, $expected)
    {
        $this->AssertSame(
            $expected,
            Valid::matches($data, $field, $match)
        );
    }
}
