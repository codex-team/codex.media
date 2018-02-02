<?php defined('SYSPATH') or die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests the kohana text class (Kohana_Text)
 *
 * @group kohana
 * @group kohana.core
 * @group kohana.core.text
 *
 * @package    Kohana
 * @category   Tests
 */
class Kohana_TextTest extends Unittest_TestCase
{
    /**
     * Sets up the test enviroment
     */
    // @codingStandardsIgnoreStart
    public function setUp()
    // @codingStandardsIgnoreEnd
    {
        parent::setUp();

        Text::alternate();
    }

    /**
     * This test makes sure that auto_p returns an empty string if
     * an empty input was provided
     *
     * @test
     * @covers Text::auto_p
     */
    public function test_auto_para_returns_empty_string_on_empty_input()
    {
        $this->assertSame('', Text::auto_p(''));
    }

    /**
     *
     * @return array Test Data
     */
    public function provider_auto_para_does_not_enclose_html_tags_in_paragraphs()
    {
        return [
            [
                ['div'],
                '<div>Pick a plum of peppers</div>',
            ],
            [
                ['div'],
                '<div id="awesome">Tangas</div>',
            ],
        ];
    }

    /**
     * This test makes sure that auto_p doesn't enclose HTML tags
     * in paragraphs
     *
     * @test
     * @covers Text::auto_p
     * @dataProvider provider_auto_para_does_not_enclose_html_tags_in_paragraphs
     *
     * @param mixed $text
     */
    public function test_auto_para_does_not_enclose_html_tags_in_paragraphs(array $tags, $text)
    {
        $output = Text::auto_p($text);

        foreach ($tags as $tag) {
            $this->assertNotTag(
                ['tag' => $tag, 'ancestor' => ['tag' => 'p']],
                $output
            );
        }
    }

    /**
     * This test makes sure that auto_p surrounds a single line of text
     * with paragraph tags
     *
     * @test
     * @covers Text::auto_p
     */
    public function test_auto_para_encloses_slot_in_paragraph()
    {
        $text = 'Pick a pinch of purple pepper';

        $this->assertSame('<p>' . $text . '</p>', Text::auto_p($text));
    }

    /**
     * Make sure that multiple new lines are replaced with paragraph tags
     *
     * @test
     * @covers Text::auto_p
     */
    public function test_auto_para_replaces_multiple_newlines_with_paragraph()
    {
        $this->assertSame(
            "<p>My name is john</p>\n\n<p>I'm a developer</p>",
            Text::auto_p("My name is john\n\n\n\nI'm a developer")
        );
    }

    /**
     * Data provider for test_limit_words
     *
     * @return array Array of test data
     */
    public function provider_limit_words()
    {
        return [
            ['', '', 100, null],
            ['…', 'The rain in spain', -10, null],
            ['The rain…', 'The rain in spain', 2, null],
            ['The rain...', 'The rain in spain', 2, '...'],
        ];
    }

    /**
     *
     * @test
     * @dataProvider provider_limit_words
     *
     * @param mixed $expected
     * @param mixed $str
     * @param mixed $limit
     * @param mixed $end_char
     */
    public function test_limit_words($expected, $str, $limit, $end_char)
    {
        $this->assertSame($expected, Text::limit_words($str, $limit, $end_char));
    }

    /**
     * Provides test data for test_limit_chars()
     *
     * @return array Test data
     */
    public function provider_limit_chars()
    {
        return [
            ['', '', 100, null, false],
            ['…', 'BOO!', -42, null, false],
            ['making php bet…', 'making php better for the sane', 14, null, false],
            ['Garçon! Un café s.v.p.', 'Garçon! Un café s.v.p.', 50, '__', false],
            ['Garçon!__', 'Garçon! Un café s.v.p.', 8, '__', false],
            // @issue 3238
            ['making php…', 'making php better for the sane', 14, null, true],
            ['Garçon!__', 'Garçon! Un café s.v.p.', 9, '__', true],
            ['Garçon!__', 'Garçon! Un café s.v.p.', 7, '__', true],
            ['__', 'Garçon! Un café s.v.p.', 5, '__', true],
        ];
    }

    /**
     * Tests Text::limit_chars()
     *
     * @test
     * @dataProvider provider_limit_chars
     *
     * @param mixed $expected
     * @param mixed $str
     * @param mixed $limit
     * @param mixed $end_char
     * @param mixed $preserve_words
     */
    public function test_limit_chars($expected, $str, $limit, $end_char, $preserve_words)
    {
        $this->assertSame($expected, Text::limit_chars($str, $limit, $end_char, $preserve_words));
    }

    /**
     * Test Text::alternate()
     *
     * @test
     */
    public function test_alternate_alternates_between_parameters()
    {
        list($val_a, $val_b, $val_c) = ['good', 'bad', 'ugly'];

        $this->assertSame('good', Text::alternate($val_a, $val_b, $val_c));
        $this->assertSame('bad', Text::alternate($val_a, $val_b, $val_c));
        $this->assertSame('ugly', Text::alternate($val_a, $val_b, $val_c));

        $this->assertSame('good', Text::alternate($val_a, $val_b, $val_c));
    }

    /**
     * Tests Text::alternate()
     *
     * @test
     * @covers Text::alternate
     */
    public function test_alternate_resets_when_called_with_no_params_and_returns_empty_string()
    {
        list($val_a, $val_b, $val_c) = ['yes', 'no', 'maybe'];

        $this->assertSame('yes', Text::alternate($val_a, $val_b, $val_c));

        $this->assertSame('', Text::alternate());

        $this->assertSame('yes', Text::alternate($val_a, $val_b, $val_c));
    }

    /**
     * Provides test data for test_reducde_slashes()
     *
     * @returns array Array of test data
     */
    public function provider_reduce_slashes()
    {
        return [
                ['/', '//'],
                ['/google/php/kohana/', '//google/php//kohana//'],
            ];
    }

    /**
     * Covers Text::reduce_slashes()
     *
     * @test
     * @dataProvider provider_reduce_slashes
     *
     * @param mixed $expected
     * @param mixed $str
     */
    public function test_reduce_slashes($expected, $str)
    {
        $this->assertSame($expected, Text::reduce_slashes($str));
    }

    /**
     * Provides test data for test_censor()
     *
     * @return array Test data
     */
    public function provider_censor()
    {
        return [
                // If the replacement is 1 character long it should be repeated for the length of the removed word
                ["A donkey is also an ***", 'A donkey is also an ass', ['ass'], '*', true],
                ["Cake### isn't nearly as good as kohana###", "CakePHP isn't nearly as good as kohanaphp", ['php'], '#', true],
                // If it's > 1 then it's just replaced straight out
                ["If you're born out of wedlock you're a --expletive--", "If you're born out of wedlock you're a child", ['child'], '--expletive--', true],

                ['class', 'class', ['ass'], '*', false],
            ];
    }

    /**
     * Tests Text::censor
     *
     * @test
     * @dataProvider provider_censor
     *
     * @param mixed $expected
     * @param mixed $str
     * @param mixed $badwords
     * @param mixed $replacement
     * @param mixed $replace_partial_words
     */
    public function test_censor($expected, $str, $badwords, $replacement, $replace_partial_words)
    {
        $this->assertSame($expected, Text::censor($str, $badwords, $replacement, $replace_partial_words));
    }

    /**
     * Provides test data for test_random
     *
     * @return array Test Data
     */
    public function provider_random()
    {
        return [
            ['alnum', 8],
            ['alpha', 10],
            ['hexdec', 20],
            ['nozero', 5],
            ['numeric', 14],
            ['distinct', 12],
            ['aeiou', 4],
            ['‹¡›«¿»', 8], // UTF8 characters
            [null, 8], // Issue #3256
        ];
    }

    /**
     * Tests Text::random() as well as possible
     *
     * Obviously you can't compare a randomly generated string against a
     * pre-generated one and check that they are the same as this goes
     * against the whole ethos of random.
     *
     * This test just makes sure that the value returned is of the correct
     * values and length
     *
     * @test
     * @dataProvider provider_random
     *
     * @param mixed $type
     * @param mixed $length
     */
    public function test_random($type, $length)
    {
        if ($type === null) {
            $type = 'alnum';
        }

        $pool = (string) $type;

        switch ($pool) {
            case 'alnum':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
            case 'alpha':
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
            case 'hexdec':
                $pool = '0123456789abcdef';
            break;
            case 'numeric':
                $pool = '0123456789';
            break;
            case 'nozero':
                $pool = '123456789';
            break;
            case 'distinct':
                $pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
            break;
        }

        $this->assertRegExp('/^[' . $pool . ']{' . $length . '}$/u', Text::random($type, $length));
    }

    /**
     * Provides test data for test_similar
     *
     * @return array
     */
    public function provider_similar()
    {
        return [
                // TODO: add some more cases
                ['foo', ['foobar', 'food', 'fooberry']],
            ];
    }

    /**
     * Tests Text::similar()
     *
     * @test
     * @dataProvider provider_similar
     * @covers Text::similar
     *
     * @param mixed $expected
     * @param mixed $words
     */
    public function test_similar($expected, $words)
    {
        $this->assertSame($expected, Text::similar($words));
    }

    /**
     * Provides test data for test_bytes
     *
     * @return array
     */
    public function provider_bytes()
    {
        return [
                // TODO: cover the other units
                ['256.00 B', 256, null, null, true],
                ['1.02 kB', 1024, null, null, true],

                // In case you need to know the size of a floppy disk in petabytes
                ['0.00147 GB', 1.44 * 1000 * 1024, 'GB', '%01.5f %s', true],

                // SI is the standard, but lets deviate slightly
                ['1.00 MiB', 1024 * 1024, 'MiB', null, false],
            ];
    }

    /**
     * Tests Text::bytes()
     *
     * @test
     * @dataProvider provider_bytes
     *
     * @param mixed $expected
     * @param mixed $bytes
     * @param mixed $force_unit
     * @param mixed $format
     * @param mixed $si
     */
    public function test_bytes($expected, $bytes, $force_unit, $format, $si)
    {
        $this->assertSame($expected, Text::bytes($bytes, $force_unit, $format, $si));
    }

    /**
     * Provides test data for test_widont()
     *
     * @return array Test data
     */
    public function provider_widont()
    {
        return [
                ['No gain, no&nbsp;pain', 'No gain, no pain'],
                ["spaces?what'rethey?", "spaces?what'rethey?"],
                ['', ''],
            ];
    }

    /**
     * Tests Text::widont()
     *
     * @test
     * @dataProvider provider_widont
     *
     * @param mixed $expected
     * @param mixed $string
     */
    public function test_widont($expected, $string)
    {
        $this->assertSame($expected, Text::widont($string));
    }

    /**
     * This checks that auto_link_emails() respects word boundaries and does not
     * just blindly replace all occurences of the email address in the text.
     *
     * In the sample below the algorithm was replacing all occurences of voorzitter@xxxx.com
     * inc the copy in the second list item.
     *
     * It was updated in 6c199366efc1115545ba13108b876acc66c54b2d to respect word boundaries
     *
     * @test
     * @covers Text::auto_link_emails
     * @ticket 2772
     */
    public function test_auto_link_emails_respects_word_boundaries()
    {
        $original = '<ul>
						<li>voorzitter@xxxx.com</li>
						<li>vicevoorzitter@xxxx.com</li>
					</ul>';

        $this->assertFalse(strpos('vice', Text::auto_link_emails($original)));
    }

    /**
     * Provides some test data for test_number()
     *
     * @return array
     */
    public function provider_number()
    {
        return [
            ['one', 1],
            ['twenty-three', 23],
            ['fourty-two', 42],
            ['five million, six hundred and thirty-two', 5000632],
            ['five million, six hundred and thirty', 5000630],
            ['nine hundred million', 900000000],
            ['thirty-seven thousand', 37000],
            ['one thousand and twenty-four', 1024],
        ];
    }

    /**
     * Checks that Text::number formats a number into english text
     *
     * @test
     * @dataProvider provider_number
     *
     * @param mixed $expected
     * @param mixed $number
     */
    public function test_number($expected, $number)
    {
        $this->assertSame($expected, Text::number($number));
    }

    /**
     * Provides test data for test_auto_link_urls()
     *
     * @return array
     */
    public function provider_auto_link_urls()
    {
        return [
            // First we try with the really obvious url
            [
                'Some random text <a href="http://www.google.com">http://www.google.com</a>',
                'Some random text http://www.google.com',
            ],
            // Then we try with varying urls
            [
                'Some random <a href="http://www.google.com">www.google.com</a>',
                'Some random www.google.com',
            ],
            [
                'Some random google.com',
                'Some random google.com',
            ],
            // Check that it doesn't link urls in a href
            [
                'Look at me <a href="http://google.com">Awesome stuff</a>',
                'Look at me <a href="http://google.com">Awesome stuff</a>',
            ],
            [
                'Look at me <a href="http://www.google.com">http://www.google.com</a>',
                'Look at me <a href="http://www.google.com">http://www.google.com</a>',
            ],
            // Punctuation at the end of the URL
            [
                'Wow <a href="http://www.google.com">http://www.google.com</a>!',
                'Wow http://www.google.com!',
            ],
            [
                'Zomg <a href="http://www.google.com">www.google.com</a>!',
                'Zomg www.google.com!',
            ],
            [
                'Well this, <a href="http://www.google.com">www.google.com</a>, is cool',
                'Well this, www.google.com, is cool',
            ],
            // @issue 3190
            [
                '<a href="http://www.google.com/">www.google.com</a>',
                '<a href="http://www.google.com/">www.google.com</a>',
            ],
            [
                '<a href="http://www.google.com/">www.google.com</a> <a href="http://www.google.com/">http://www.google.com/</a>',
                '<a href="http://www.google.com/">www.google.com</a> http://www.google.com/',
            ],
            // @issue 3436
            [
                '<strong><a href="http://www.google.com/">http://www.google.com/</a></strong>',
                '<strong>http://www.google.com/</strong>',
            ],
            // @issue 4208, URLs with a path
            [
                'Foobar <a href="http://www.google.com/analytics">www.google.com/analytics</a> cake',
                'Foobar www.google.com/analytics cake',
            ],
            [
                'Look at this <a href="http://www.google.com/analytics">www.google.com/analytics</a>!',
                'Look at this www.google.com/analytics!',
            ],
            [
                'Path <a href="http://www.google.com/analytics">http://www.google.com/analytics</a> works?',
                'Path http://www.google.com/analytics works?',
            ],
            [
                'Path <a href="http://www.google.com/analytics">http://www.google.com/analytics</a>',
                'Path http://www.google.com/analytics',
            ],
            [
                'Path <a href="http://www.google.com/analytics">www.google.com/analytics</a>',
                'Path www.google.com/analytics',
            ],
        ];
    }

    /**
     * Runs tests for Test::auto_link_urls
     *
     * @test
     * @dataProvider provider_auto_link_urls
     *
     * @param mixed $expected
     * @param mixed $text
     */
    public function test_auto_link_urls($expected, $text)
    {
        $this->assertSame($expected, Text::auto_link_urls($text));
    }

    /**
     * Provides test data for test_auto_link_emails()
     *
     * @return array
     */
    public function provider_auto_link_emails()
    {
        return [
            // @issue 3162
            [
                '<span class="broken"><a href="mailto:info@test.com">info@test.com</a></span>',
                '<span class="broken">info@test.com</span>',
            ],
            [
                '<a href="mailto:info@test.com">info@test.com</a>',
                '<a href="mailto:info@test.com">info@test.com</a>',
            ],
            // @issue 3189
            [
                '<a href="mailto:email@address.com">email@address.com</a> <a href="mailto:email@address.com">email@address.com</a>',
                '<a href="mailto:email@address.com">email@address.com</a> email@address.com',
            ],
        ];
    }

    /**
     * Runs tests for Test::auto_link_emails
     *
     * @test
     * @dataProvider provider_auto_link_emails
     *
     * @param mixed $expected
     * @param mixed $text
     */
    public function test_auto_link_emails($expected, $text)
    {
        // Use html_entity_decode because emails will be randomly encoded by HTML::mailto
        $this->assertSame($expected, html_entity_decode(Text::auto_link_emails($text)));
    }

    /**
     * Provides test data for test_auto_link
     *
     * @return array Test data
     */
    public function provider_auto_link()
    {
        return [
            [
                'Hi there, my site is kohanaframework.org and you can email me at nobody@kohanaframework.org',
                ['kohanaframework.org'],
            ],

            [
                'Hi my.domain.com@domain.com you came from',
                false,
                ['my.domain.com@domain.com'],
            ],
        ];
    }

    /**
     * Tests Text::auto_link()
     *
     * @test
     * @dataProvider provider_auto_link
     *
     * @param mixed $text
     * @param mixed $urls
     * @param mixed $emails
     */
    public function test_auto_link($text, $urls = [], $emails = [])
    {
        $linked_text = Text::auto_link($text);

        if ($urls === false) {
            $this->assertNotContains('http://', $linked_text);
        } elseif (count($urls)) {
            foreach ($urls as $url) {
                // Assert that all the urls have been caught by text auto_link_urls()
                $this->assertContains(Text::auto_link_urls($url), $linked_text);
            }
        }

        foreach ($emails as $email) {
            $this->assertContains('&#109;&#097;&#105;&#108;&#116;&#111;&#058;' . $email, $linked_text);
        }
    }
}
