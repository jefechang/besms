<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Velostazione\BeSMS\Api;
use Velostazione\BeSMS\Exceptions\MessageNotEnqueuedError;

final class SendRequestTest extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testCanSendSms(): void
    {
        $mockResponseBody = '+01 SMS Queued - ID: 1234567890123456789';
        $mockResponse = new Response(200, [], $mockResponseBody);
        $client = $this->mockClient($mockResponse);
        $response = $client->send('1234567890', 'test-content', '0987654321');
        $this->assertEquals($mockResponseBody, $response);
    }

    /**
     * @throws GuzzleException
     */
    public function testThrowErrorOnInvalidNumber(): void
    {
        $mockResponseBody = '-13-destination invalid parameter type';
        $mockResponse = new Response(200, [], $mockResponseBody);
        $client = $this->mockClient($mockResponse);
        $this->expectException(MessageNotEnqueuedError::class);
        $client->send('+1234567890', 'test-content', '0987654321');
    }

    /**
     * @throws GuzzleException
     */
    public function testThrowErrorOnClientError(): void
    {
        $mockResponse = new RequestException('Error Communicating with Server', new Request('POST', '/send_sms'));
        $client = $this->mockClient($mockResponse);
        $this->expectException(RequestException::class);
        $client->send('1234567890', 'test-content', '0987654321');
    }

    private function mockClient(Response|RequestException $mockResponse): Api
    {
        $mock = new MockHandler([$mockResponse]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        return new Api(
            $client,
            'test-login',
            'test-password',
            'test-id-api',
            'test-report-type',
        );
    }
}
