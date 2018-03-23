<?php

use Omnipay\Eghl\Message\PurchaseRequest;
use Omnipay\Eghl\Message\PurchaseResponse;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    public $request;

    protected function setUp()
    {
        parent::setUp();
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetData()
    {
        $this->request->initialize([
            'card' => [
                'email' => ($email = 'xuding@spacebib.com'),
                'name' => ($fullName = 'Xu Ding'),
                'phone' => ($phone = '123456789'),
            ],
            'merchantId' => ($merchantId = '123'),
            'merchantPassword' => ($merchantPwd = 'p123'),
            'amount' => ($amount = 10000.0),
            'currency' => ($currency = 'IDR'),
            'description' => ($description = 'Marina Run 2016'),
            'transactionId' => ($transactionId = 12),
            'returnUrl' => ($returnUrl = 'https://www.example.com/return?yes&no'),
            'notifyUrl' => ($notifyUrl = 'https://www.example.com/notify?yes&no'),
            'clientIp' => ($ip = '127.0.0.1'),
            'invoiceNo' => ($invoiceNo = '20191212-123123')
        ]);

        $result = $this->request->getData();

        $amountTransformed = '10000.00';
        $returnUrlTransformed = 'https://www.example.com/return?yes;no';
        $notifyUrlTransformed = 'https://www.example.com/notify?yes;no';

        $expected = [
            'TransactionType' => 'SALE',
            'PymtMethod' => 'ANY',
            'ServiceID' => $merchantId,
            'PaymentID' => $transactionId,
            'OrderNumber' => $invoiceNo,
            'PaymentDesc' => $description,
            'MerchantReturnURL' => $returnUrl,
            'MerchantCallBackURL' => $notifyUrl,
            'Amount' => $amountTransformed,
            'CurrencyCode' => $currency,
            'HashValue' =>
                hash('sha256',
                    $merchantPwd .
                    $merchantId .
                    $transactionId .
                    $returnUrlTransformed .
                    $notifyUrlTransformed .
                    $amountTransformed .
                    $currency .
                    $ip .
                    600
                ),
            'CustIP' => $ip,
            'CustName' => $fullName,
            'CustEmail' => $email,
            'CustPhone' => $phone,
            'PageTimeout' => 600
        ];

        $this->assertEquals($expected, $result);
    }

    public function testSendData()
    {
        $this->request->initialize([
            'merchantId' => ($merchantId = '123'),
            'merchantPassword' => ($merchantPwd = 'p123'),
        ]);

        $result = $this->request->sendData(['test' => 1]);

        $this->assertInstanceOf(PurchaseResponse::class, $result);
    }

}
