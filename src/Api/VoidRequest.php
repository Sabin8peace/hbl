<?php

namespace Bickyraj\Hbl\Api;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Bickyraj\Hbl\ActionRequest;

class VoidRequest extends ActionRequest
{
    /**
     * @throws GuzzleException
     */
    public function Execute(): string
    {
        $officeId = config('hbl.OfficeId');
        $orderNo = "1643362945102"; //OrderNo can be Refund/Void one time only
        $productDescription = "Sample request for 1643362945102";

        $request = [
            "officeId" => $officeId,
            "orderNo" => $orderNo,
            "productDescription" => $productDescription,
            "issuerApprovalCode" => "140331", // approvalCode of order place (Payment api) response
            "actionBy" => "System",
            "voidAmount" => [
                "amountText" => "000000100000",
                "currencyCode" => "THB",
                "decimalPlaces" => 2,
                "amount" => 1000.00
            ],
        ];

        $stringRequest = json_encode($request);

        //third-party http client https://github.com/guzzle/guzzle
        $response = $this->client->post('api/1.0/Void', [
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
        $orderNo = "1643362945102"; //OrderNo can be Refund/Void one time only
        $productDescription = "Sample request for 1643362945102";

        $request = [
            "officeId" => $officeId,
            "orderNo" => $orderNo,
            "productDescription" => $productDescription,
            "issuerApprovalCode" => "140331", // approvalCode of order place (Payment api) response
            "actionBy" => "System",
            "voidAmount" => [
                "amountText" => "000000100000",
                "currencyCode" => "THB",
                "decimalPlaces" => 2,
                "amount" => 1000.00
            ],
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
        $response = $this->client->post('api/1.0/Void', [
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
