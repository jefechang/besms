<?php

namespace Velostazione\BeSMS;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Velostazione\BeSMS\Exceptions\InvalidRecipientError;
use Velostazione\BeSMS\Exceptions\MessageNotEnqueuedError;
use Velostazione\BeSMS\Exceptions\SendError;

final class Api
{

    private array $defaultParams;

    private Client $client;

    public function __construct(
        Client $client,
        string $username,
        string $password,
        string $apiId,
        string $reportType = 'C',
        string $sender = null
    )
    {
        $this->defaultParams = [
            'authlogin'   => $username,
            'authpasswd'  => $password,
            'id_api'      => $apiId,
            'report_type' => $reportType,
            'sender'      => $sender ? base64_encode($sender) : null
        ];
        $this->client = $client;
    }

    /**
     * @param int|string  $recipient
     * @param string      $message
     * @param string|null $sender
     *
     * @return string|false
     * @throws GuzzleException
     */
    public function send(int|string $recipient, string $message, string $sender = null): string|false
    {
        if (!is_numeric($phone)) {
            return false;
        }
        $params = [
            'destination' => $recipient,
            'body'        => base64_encode($message),
        ];
        if ($sender) {
            $params['sender'] = base64_encode($sender);
        }
        $response = $this->sendRequest('/send_sms', $params);
        if (preg_match('~sms queued~i', $response) < 1) {
            throw new MessageNotEnqueuedError($response);
        }
        return $response;
    }

    /**
     * @return string
     * @throws GuzzleException
     */
    public function getCredit(): string
    {
        return $this->sendRequest('/get_credit');
    }

    /**
     * @param string $endpoint
     * @param array  $params
     *
     * @return string
     * @throws GuzzleException
     */
    private function sendRequest(string $endpoint, array $params = []): string
    {
        $response = $this->client->request('POST', "/http{$endpoint}", [
            'form_params' => array_merge($this->defaultParams, $params)
        ]);
        if ($response->getStatusCode() !== 200) {
            throw new SendError();
        }
        return $response->getBody()->getContents();
    }
}
