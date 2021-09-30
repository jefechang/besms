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
use Velostazione\BeSMS\Exceptions\InvalidRecipient;
use Velostazione\BeSMS\Exceptions\MessageNotEnqueued;

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
     * @dataProvider phoneNumberDataProvider
     */
    public function testCanSendSmsWithNonNumericSender(string $phoneNumber): void
    {
        $mockResponseBody = '+01 SMS Queued - ID: 1234567890123456789';
        $mockResponse = new Response(200, [], $mockResponseBody);
        $client = $this->mockClient($mockResponse);

        $response = $client->send($phoneNumber, 'test-content');
        $this->assertEquals($mockResponseBody, $response);
    }

    public function phoneNumberDataProvider(): array
    {
        return [
            ['+931234567890'],
            ['00931234567890'],
            ['+1-6841234567890'],
            ['+93 123 45 67 890'],
        ];
    }

    /**
     * @throws GuzzleException
     */
    public function testThrowErrorOnInvalidNumber(): void
    {
        $client = $this->mockClient(new Response(200));
        $this->expectException(InvalidRecipient::class);
        $client->send('invalid-number', 'test-content');
    }

    /**
     * @throws GuzzleException
     */
    public function testThrowErrorOnNonEnqueuedMessage(): void
    {
        $mockResponseBody = '-13-destination invalid parameter type';
        $mockResponse = new Response(200, [], $mockResponseBody);
        $client = $this->mockClient($mockResponse);
        $this->expectException(MessageNotEnqueued::class);
        $client->send('1234567890', 'test-content');
    }

    /**
     * @throws GuzzleException
     */
    public function testThrowErrorOnClientError(): void
    {
        $mockResponse = new RequestException('Error Communicating with Server', new Request('POST', '/send_sms'));
        $client = $this->mockClient($mockResponse);
        $this->expectException(RequestException::class);
        $client->send('1234567890', 'test-content');
    }

    private function mockClient(Response|RequestException $mockResponse = null): Api
    {
        $mock = new MockHandler([$mockResponse]);
        $handlerStack = HandlerStack::create($mockResponse ? $mock : null);
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
