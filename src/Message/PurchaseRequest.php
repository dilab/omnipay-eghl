<?php

namespace Omnipay\Eghl\Message;


use Omnipay\Common\Exception\InvalidRequestException;

class PurchaseRequest extends AbstractRequest
{
    public function sendData($data)
    {
        return new PurchaseResponse($this, $data);
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

        $data = [
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

        $this->guardGatewayData($data);

        return $data;
    }

    private function guardGatewayData($data)
    {
        if (strlen($data['PaymentID']) > 20) {
            throw new InvalidRequestException(
                sprintf('Invalid PaymentID length: %s, max length is 20', strlen($data['PaymentID']))
            );
        }

        if (strlen($data['OrderNumber']) > 20) {
            throw new InvalidRequestException(
                sprintf('Invalid OrderNumber length: %s, max length is 20', strlen($data['OrderNumber']))
            );
        }

        if (strlen($data['PaymentDesc']) > 100) {
            throw new InvalidRequestException(
                sprintf('Invalid PaymentDesc length: %s, max length is 100', strlen($data['PaymentDesc']))
            );
        }

        if (strlen($data['MerchantReturnURL']) > 255) {
            throw new InvalidRequestException(
                sprintf('Invalid MerchantReturnURL length: %s, max length is 255', strlen($data['MerchantReturnURL']))
            );
        }

        if (strlen($data['MerchantCallBackURL']) > 255) {
            throw new InvalidRequestException(
                sprintf('Invalid MerchantCallBackURL length: %s, max length is 255', strlen($data['MerchantCallBackURL']))
            );
        }

        if (strlen($data['CustIP']) > 20) {
            throw new InvalidRequestException(
                sprintf('Invalid CustIP length: %s, max length is 20', strlen($data['CustIP']))
            );
        }

        if (strlen($data['CustName']) > 50) {
            throw new InvalidRequestException(
                sprintf('Invalid CustName length: %s, max length is 50', strlen($data['CustName']))
            );
        }

        if (strlen($data['CustEmail']) > 60) {
            throw new InvalidRequestException(
                sprintf('Invalid CustEmail length: %s, max length is 60', strlen($data['CustEmail']))
            );
        }

        if (strlen($data['CustPhone']) > 25) {
            throw new InvalidRequestException(
                sprintf('Invalid CustPhone length: %s, max length is 25', strlen($data['CustPhone']))
            );
        }

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