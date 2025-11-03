<?php

namespace Bickyraj\Hbl\Api;

class PaymentObject
{
    private $order_no;
    private $amount;
    private $success_url;
    private $failed_url;
    private $cancel_url;
    private $backend_url;
    private $custom_fields;

    public function __construct()
    {
    }

    // Setter methods
    public function setOrderNo($order_no)
    {
        $this->order_no = $order_no;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function setSuccessUrl($success_url)
    {
        $this->success_url = $success_url;
    }

    public function setFailedUrl($failed_url)
    {
        $this->failed_url = $failed_url;
    }

    public function setCancelUrl($cancel_url)
    {
        $this->cancel_url = $cancel_url;
    }

    public function setBackendUrl($backend_url)
    {
        $this->backend_url = $backend_url;
    }

    public function setCustomFields($custom_fields)
    {
        $this->custom_fields = $custom_fields;
    }

    public function getOrderNo()
    {
        return $this->order_no;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getSuccessUrl()
    {
        return $this->success_url;
    }

    public function getFailedUrl()
    {
        return $this->failed_url;
    }

    public function getCancelUrl()
    {
        return $this->cancel_url;
    }

    public function getBackendUrl()
    {
        return $this->backend_url;
    }

    public function getCustomFields()
    {
        return $this->custom_fields;
    }

    public function toArray()
    {
        return [
            "order_no" => $this->getOrderNo(),
            "amount" => $this->getAmount(),
            "success_url" => $this->getSuccessUrl(),
            "failed_url" => $this->getFailedUrl(),
            "cancel_url" => $this->getCancelUrl(),
            "backend_url" => $this->getBackendUrl(),
            "custom_fields" => $this->getCustomFields(),
        ];
    }
}
