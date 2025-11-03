<?php

namespace Bickyraj\Hbl\Api\Interfaces;

use Bickyraj\Hbl\Api\PaymentObject;

interface IHblPayment {
    public static function pay(PaymentObject $paymentObject);
}
