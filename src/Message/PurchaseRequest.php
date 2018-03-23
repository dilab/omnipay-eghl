<?php

namespace Omnipay\Eghl\Message;


class PurchaseRequest extends AbstractRequest
{
    public function sendData($data)
    {
        return new PurchaseResponse($data);
    }

    public function getData()
    {
        $this->validate(
            'merchantId',
            'merchantPassword',
            'amount',
            'currency',
            'description',
            'transactionId',
            'returnUrl',
            'notifyUrl',
            'invoiceNo',
            'clientIp'
        );

        $amount = number_format($this->getAmount(), 2, '.', '');

        return [
            'TransactionType' => 'SALE',
            'PymtMethod' => 'ANY',
            'ServiceID' => $this->getMerchantId(),
            'PaymentID' => $this->getTransactionId(),
            'OrderNumber' => $this->getInvoiceNo(),
            'PaymentDesc' => $this->getDescription(),
            'MerchantReturnURL' => $this->getReturnUrl(),
            'MerchantCallBackURL' => $this->getNotifyUrl(),
            'Amount' => $amount,
            'CurrencyCode' => $this->getCurrency(),
            'HashValue' => $this->hash($amount),
            'CustIP' => $this->getClientIp(),
            'CustName' => $this->getCard()->getName(),
            'CustEmail' => $this->getCard()->getEmail(),
            'CustPhone' => $this->getCard()->getPhone(),
            'PageTimeout' => 600
        ];
    }

    private function safeUrl($url)
    {
        return str_replace('&', ';', $url);
    }

    private function hash($amount)
    {
        return hash('sha256',
            $this->getMerchantPassword() .
            $this->getMerchantId() .
            $this->getTransactionId() .
            $this->safeUrl($this->getReturnUrl()) .
            $this->safeUrl($this->getNotifyUrl()) .
            $amount .
            $this->getCurrency() .
            $this->getClientIp() .
            600
        );
    }


}