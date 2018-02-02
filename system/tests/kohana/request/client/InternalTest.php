<?php defined('SYSPATH') or die('Kohana bootstrap needs to be included before tests run');

/**
 * Unit tests for internal request client
 *
 * @group kohana
 * @group kohana.core
 * @group kohana.core.request
 * @group kohana.core.request.client
 * @group kohana.core.request.client.internal
 *
 * @package    Kohana
 * @category   Tests
 *
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_Request_Client_InternalTest extends Unittest_TestCase
{
    public function provider_response_failure_status()
    {
        return [
            ['', 'Welcome', 'missing_action', 'Welcome/missing_action', 404],
            ['kohana3', 'missing_controller', 'index', 'kohana3/missing_controller/index', 404],
            ['', 'Template', 'missing_action', 'kohana3/Template/missing_action', 500],
        ];
    }

    /**
     * Tests for correct exception messages
     *
     * @test
     * @dataProvider provider_response_failure_status
     *
     * @param mixed $directory
     * @param mixed $controller
     * @param mixed $action
     * @param mixed $uri
     * @param mixed $expected
     */
    public function test_response_failure_status($directory, $controller, $action, $uri, $expected)
    {
        // Mock for request object
        $request = $this->getMock('Request', ['directory', 'controller', 'action', 'uri', 'response'], [$uri]);

        $request->expects($this->any())
            ->method('directory')
            ->will($this->returnValue($directory));

        $request->expects($this->any())
            ->method('controller')
            ->will($this->returnValue($controller));

        $request->expects($this->any())
            ->method('action')
            ->will($this->returnValue($action));

        $request->expects($this->any())
            ->method('uri')
            ->will($this->returnValue($uri));

        $request->expects($this->any())
            ->method('response')
            ->will($this->returnValue($this->getMock('Response')));

        $internal_client = new Request_Client_Internal;

        $response = $internal_client->execute($request);

        $this->assertSame($expected, $response->status());
    }
}
