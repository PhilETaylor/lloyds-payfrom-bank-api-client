<?php
/*
 * @copyright  Copyright (C) 2022,2023 Blue Flame Digital Solutions Limited / Phil Taylor. All rights reserved.
 * @copyright  Copyright (C) 2022,2023 Red Evolution Limited. All rights reserved.
 * @author     Phil Taylor <phil@phil-taylor.com>
 * @see        https://github.com/PhilETaylor/lloyds-payfrom-bank-api-client
 * @license    The GNU General Public License v3.0
 */

namespace App;

use DateTimeZone;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{
    private const ADMINLOGIN = 'https://lloydsbankpayfrombank.gateway.mastercard.com/ma/login.s';

    private const ENDPOINT = 'https://lloydsbankpayfrombank.gateway.mastercard.com/api/rest/version/69/merchant/';

    private readonly string $merchantId;

    private readonly string $password;

    private readonly string $createSessionUrl;

    private readonly string $correlationId;

    private string $sessionId = '';

    private string $userData = '';

    private readonly HttpClientInterface $client;

    private string $orderId;

    private string $transactionId;

    private string $amount;

    private string $returnUrl = '';

    private readonly string $merchantName;

    private string $reference = '';

    public function __construct()
    {
        $this->returnUrl        = $_ENV['RETURNURL'];
        $this->merchantId       = $_ENV['MERCHANTID'];
        $this->merchantName     = $_ENV['MERCHANTNAME'];
        $this->password         = $_ENV['PASSWORD'];
        $this->createSessionUrl = self::ENDPOINT . $this->merchantId . '/session';
        $this->correlationId    = sha1(random_bytes(6));
        $this->client           = HttpClient::create();

        session_start();
        if (\array_key_exists('orderId', $_SESSION)) {
            $this->orderId
                = $this->transactionId
                = $this->reference = $_SESSION['orderId'];
        }
    }

    public function setUnfilteredUserData(array $unfilteredUserData)
    {
        $this->amount = number_format((float) $unfilteredUserData['amount'], 2, '.', '');

        unset($unfilteredUserData['amount']);
        $this->userData = ucwords(json_encode($unfilteredUserData, \JSON_THROW_ON_ERROR));
    }

    public function prepareSession(): array
    {
        $this->setOrderId();
        $this->lloydsCreateSession();
        $this->lloydsUpdateSession();

        return [
            'merchantName'  => $this->merchantName,
            'merchantId'    => $this->merchantId,
            'amount'        => $this->amount,
            'orderId'       => $this->orderId,
            'transactionId' => $this->transactionId,
            'sessionId'     => $this->sessionId,
        ];
    }

    private function setOrderId(): void
    {
        $data = json_decode($this->userData, true, 512, \JSON_THROW_ON_ERROR);

        $date = new \DateTime();
        $date->setTimezone(new DateTimeZone('Europe/London'));
        $dateString = $date->format('ymdHi');

        $this->orderId
            = $this->transactionId
            = $this->reference = $_SESSION['orderId'] =
            substr($dateString . $data['tenancynumber'], 0, 16);
    }

    private function lloydsCreateSession(): string
    {
        $response = $this->client->request(
            'POST',
            $this->createSessionUrl,
            [
                'json'        => [
                    'correlationId' => $this->correlationId,
                    'session'       => [
                        'authenticationLimit' => 25,
                    ],
                ],
                'verify_host' => ! ($_ENV['PROXY'] === "true"),
                'verify_peer' => ! ($_ENV['PROXY'] === "true"),
                'proxy'       => $_ENV['PROXY'] === "true" ? '127.0.0.1:8888' : false,
                'auth_basic'  => ['merchant.' . $this->merchantId, $this->password],
            ],
        );

        return $this->sessionId = json_decode($response->getContent(false), null, 512, \JSON_THROW_ON_ERROR)->session->id;
    }

    private function lloydsUpdateSession()
    {
        try {
            $this->client->request(
                'PUT',
                $this->createSessionUrl . '/' . $this->sessionId,
                [
                    'json'        => [
                        'order'          => [
                            'amount'    => $this->amount,
                            'currency'  => 'GBP',
                            'id'        => $this->orderId,
                            'reference' => $this->reference,
                            'custom'    => $this->reference,
                        ],
                        'transaction'    => [
                            'reference'    => $this->reference,
                            'merchantNote' => $this->reference,
                            'id'           => $this->transactionId,
                        ],
                        'browserPayment' => [
                            'operation' => 'PAY',
                            'returnUrl' => $this->returnUrl,
                        ],
                    ],
                    'verify_host' => ! ($_ENV['PROXY'] === "true"),
                    'verify_peer' => ! ($_ENV['PROXY'] === "true"),
                    'proxy'       => $_ENV['PROXY'] === "true" ? '127.0.0.1:8888' : false,
                    'auth_basic'  => ['merchant.' . $this->merchantId, $this->password],
                ],
            );
        } catch (ClientException $exception) {
            echo $exception->getResponse()->getContent(false);
        }
    }

    public function getTransactionData(): array
    {
        if (! $this->orderId) {
            return [];
        }

        $response = $this->client->request(
            'GET',
            self::ENDPOINT . $this->merchantId . '/order/' . $this->orderId,
            [
                'verify_host' => ! ($_ENV['PROXY'] === "true"),
                'verify_peer' => ! ($_ENV['PROXY'] === "true"),
                'proxy'       => $_ENV['PROXY'] === "true" ? '127.0.0.1:8888' : false,
                'auth_basic'  => ['merchant.' . $this->merchantId, $this->password],
            ],
        );

        return json_decode($response->getContent(false), true, 512, \JSON_THROW_ON_ERROR);
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }
}
