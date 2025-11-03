<?php

namespace Bickyraj\Hbl\Api;

use Bickyraj\Hbl\Api\Interfaces\IHblPayment;

class HblPayment implements IHblPayment
{

    public static function pay(PaymentObject $paymentObject)
    {
        $payment = new Payment();
        $joseResponse = $payment->ExecuteFormJose($paymentObject->toArray());
        $response_obj = json_decode($joseResponse);
        header("Location: " . $response_obj->response->Data->paymentPage->paymentPageURL);
        exit();
    }
}
