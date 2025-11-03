<?php

namespace Bickyraj\Hbl\Api;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Bickyraj\Hbl\ActionRequest;

class Refund extends ActionRequest
{
    /**
     * @throws GuzzleException
     */
    public function Execute(): string
    {
        $officeId = config('hbl.OfficeId');
        $orderNo = "1643362945100"; //OrderNo can be Refund one time only

        $actionBy = "System|c88ef0dc-14ea-4556-922b-7f62a6a3ec9e";
        $actionEmail = "babulal.cho@2c2pexternal.com";

        $request = [
            "refundAmount" => [
                "AmountText" => "000000100000",
                "CurrencyCode" => "THB",
                "DecimalPlaces" => 2,
                "Amount" => 1000.00
            ],
            "refundItems" => [],
            "localMakerChecker" => [
                "maker" => [
                    "username" => $actionBy,
                    "email" => $actionEmail
                ]
            ],
            "officeId" => $officeId,
            "orderNo" => $orderNo,
        ];

        $stringRequest = json_encode($request);

        echo $stringRequest;

        //third-party http client https://github.com/guzzle/guzzle
        $response = $this->client->post('api/1.0/Refund/refund', [
            'headers' => [
                'Accept' => 'application/json',
                'apiKey' => config('hbl.AccessToken'),
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'body' => $stringRequest
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function ExecuteJose(): string
    {
        $now = Carbon::now();
        $officeId = config('hbl.OfficeId');
        $orderNo = "1643362945100"; //OrderNo can be Refund one time only

        $actionBy = "System|c88ef0dc-14ea-4556-922b-7f62a6a3ec9e";
        $actionEmail = "babulal.cho@2c2pexternal.com";

        $request = [
            "refundAmount" => [
                "AmountText" => "000000100000",
                "CurrencyCode" => "THB",
                "DecimalPlaces" => 2,
                "Amount" => 1000.00
            ],
            "refundItems" => [],
            "localMakerChecker" => [
                "maker" => [
                    "username" => $actionBy,
                    "email" => $actionEmail
                ]
            ],
            "officeId" => $officeId,
            "orderNo" => $orderNo,
        ];

        $payload = [
            "request" => $request,
            "iss" => config('hbl.AccessToken'),
            "aud" => "PacoAudience",
            "CompanyApiKey" => config('hbl.AccessToken'),
            "iat" => $now->unix(),
            "nbf" => $now->unix(),
            "exp" => $now->addHour()->unix(),
        ];

        $stringPayload = json_encode($payload);
        $signingKey = $this->GetPrivateKey(config('hbl.MerchantSigningPrivateKey'));
        $encryptingKey = $this->GetPublicKey(config('hbl.PacoEncryptionPublicKey'));

        $body = $this->EncryptPayload($stringPayload, $signingKey, $encryptingKey);

        //third-party http client https://github.com/guzzle/guzzle
        $response = $this->client->post('api/1.0/Refund/refund', [
            'headers' => [
                'Accept' => 'application/jose',
                'CompanyApiKey' => config('hbl.AccessToken'),
                'Content-Type' => 'application/jose; charset=utf-8'
            ],
            'body' => $body
        ]);

        $token = $response->getBody()->getContents();
        $decryptingKey = $this->GetPrivateKey(config('hbl.MerchantDecryptionPrivateKey'));
        $signatureVerificationKey = $this->GetPublicKey(config('hbl.PacoSigningPublicKey'));

        return $this->DecryptToken($token, $decryptingKey, $signatureVerificationKey);
    }
}
