<?php defined('SYSPATH') or die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests Kohana upload class
 *
 * @group kohana
 * @group kohana.core
 * @group kohana.core.upload
 *
 * @package    Kohana
 * @category   Tests
 *
 * @author     Kohana Team
 * @author     Jeremy Bush <contractfrombelow@gmail.com>
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_UploadTest extends Unittest_TestCase
{
    /**
     * Provides test data for test_size()
     *
     * @return array
     */
    public function provider_size()
    {
        return [
            // $field, $bytes, $environment, $expected
            [
                'unit_test',
                5,
                ['_FILES' => ['unit_test' => ['error' => UPLOAD_ERR_INI_SIZE]]],
                false
            ],
            [
                'unit_test',
                5,
                ['_FILES' => ['unit_test' => ['error' => UPLOAD_ERR_NO_FILE]]],
                true
            ],
            [
                'unit_test',
                '6K',
                ['_FILES' => [
                    'unit_test' => [
                        'error' => UPLOAD_ERR_OK,
                        'name' => 'Unit_Test File',
                        'type' => 'image/png',
                        'tmp_name' => Kohana::find_file('tests', 'test_data/github', 'png'),
                        'size' => filesize(Kohana::find_file('tests', 'test_data/github', 'png')),
                        ]
                    ]
                ],
                true
            ],
            [
                'unit_test',
                '1B',
                ['_FILES' => [
                        'unit_test' => [
                            'error' => UPLOAD_ERR_OK,
                            'name' => 'Unit_Test File',
                            'type' => 'image/png',
                            'tmp_name' => Kohana::find_file('tests', 'test_data/github', 'png'),
                            'size' => filesize(Kohana::find_file('tests', 'test_data/github', 'png')),
                        ]
                    ]
                ],
                false
            ],
        ];
    }

    /**
     * Tests Upload::size
     *
     * @test
     * @dataProvider provider_size
     * @covers upload::size
     *
     * @param string $field       the files field to test
     * @param string $bytes       valid bite size
     * @param array  $environment set the $_FILES array
     * @param bool   $expected    what to expect
     */
    public function test_size($field, $bytes, $environment, $expected)
    {
        $this->setEnvironment($environment);

        $this->assertSame($expected, Upload::size($_FILES[$field], $bytes));
    }

    /**
     * size() should throw an exception of the supplied max size is invalid
     *
     * @test
     * @covers upload::size
     * @expectedException Kohana_Exception
     */
    public function test_size_throws_exception_for_invalid_size()
    {
        $this->setEnvironment([
            '_FILES' => [
                'unit_test' => [
                    'error' => UPLOAD_ERR_OK,
                    'name' => 'Unit_Test File',
                    'type' => 'image/png',
                    'tmp_name' => Kohana::find_file('tests', 'test_data/github', 'png'),
                    'size' => filesize(Kohana::find_file('tests', 'test_data/github', 'png')),
                ]
            ]
        ]);

        Upload::size($_FILES['unit_test'], '1DooDah');
    }

    /**
     * Provides test data for test_vali()
     *
     * @test
     *
     * @return array
     */
    public function provider_valid()
    {
        return [
            [
                true,
                [
                    'error' => UPLOAD_ERR_OK,
                    'name' => 'Unit_Test File',
                    'type' => 'image/png',
                    'tmp_name' => Kohana::find_file('tests', 'test_data/github', 'png'),
                    'size' => filesize(Kohana::find_file('tests', 'test_data/github', 'png')),
                ]
            ],
            [
                false,
                [
                    'name' => 'Unit_Test File',
                    'type' => 'image/png',
                    'tmp_name' => Kohana::find_file('tests', 'test_data/github', 'png'),
                    'size' => filesize(Kohana::find_file('tests', 'test_data/github', 'png')),
                ]
            ],
            [
                false,
                [
                    'error' => UPLOAD_ERR_OK,
                    'type' => 'image/png',
                    'tmp_name' => Kohana::find_file('tests', 'test_data/github', 'png'),
                    'size' => filesize(Kohana::find_file('tests', 'test_data/github', 'png')),
                ]
            ],
            [
                false,
                [
                    'name' => 'Unit_Test File',
                    'error' => UPLOAD_ERR_OK,
                    'tmp_name' => Kohana::find_file('tests', 'test_data/github', 'png'),
                    'size' => filesize(Kohana::find_file('tests', 'test_data/github', 'png')),
                ]
            ],
            [
                false,
                [
                    'error' => UPLOAD_ERR_OK,
                    'name' => 'Unit_Test File',
                    'type' => 'image/png',
                    'size' => filesize(Kohana::find_file('tests', 'test_data/github', 'png')),
                ]
            ],
            [
                false,
                [
                    'error' => UPLOAD_ERR_OK,
                    'name' => 'Unit_Test File',
                    'type' => 'image/png',
                    'tmp_name' => Kohana::find_file('tests', 'test_data/github', 'png'),
                ]
            ],

        ];
    }

    /**
     * Test Upload::valid
     *
     * @test
     * @dataProvider provider_valid
     * @covers Upload::valid
     *
     * @param mixed $expected
     * @param mixed $file
     */
    public function test_valid($expected, $file)
    {
        $this->setEnvironment([
            '_FILES' => [
                'unit_test' => $file,
            ],
        ]);

        $this->assertSame($expected, Upload::valid($_FILES['unit_test']));
    }

    /**
     * Tests Upload::type
     *
     * @test
     * @covers Upload::type
     */
    public function test_type()
    {
        $this->setEnvironment([
            '_FILES' => [
                'unit_test' => [
                    'error' => UPLOAD_ERR_OK,
                    'name' => 'github.png',
                    'type' => 'image/png',
                    'tmp_name' => Kohana::find_file('tests', 'test_data/github', 'png'),
                    'size' => filesize(Kohana::find_file('tests', 'test_data/github', 'png')),
                ]
            ]
        ]);

        $this->assertTrue(Upload::type($_FILES['unit_test'], ['jpg', 'png', 'gif']));

        $this->assertFalse(Upload::type($_FILES['unit_test'], ['docx']));
    }
}
