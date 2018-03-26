<?php

use Omnipay\Eghl\Message\CompletePurchaseRequest;
use Omnipay\Eghl\Message\CompletePurchaseResponse;
use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    /**
     * @var CompletePurchaseRequest
     */
    public $request;

    protected function setUp()
    {
        parent::setUp();
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
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
            'merchantPassword' => ($merchantPwd = 'abc123'),
            'amount' => ($amount = 10000.0),
            'currency' => ($currency = 'IDR'),
            'description' => ($description = 'Marina Run 2016'),
            'transactionId' => ($transactionId = 12),
            'returnUrl' => ($returnUrl = 'https://www.example.com/return?yes&no'),
            'notifyUrl' => ($notifyUrl = 'https://www.example.com/notify?yes&no'),
            'clientIp' => ($ip = '127.0.0.1'),
            'invoiceNo' => ($invoiceNo = '20191212-123123')
        ]);

        $response = [
            'TransactionType' => 'SALE',
            'PymtMethod' => 'ANY',
            'ServiceID' => 'S22',
            'PaymentID' => 'PAYTEST123',
            'OrderNumber' => '0007901000',
            'Amount' => '12.34',
            'CurrencyCode' => 'MYR',
            'HashValue2' => '8795c391a3091585295906a0694d9d13d29c38aa3d4d4521385f222ac19fb773',
            'TxnID' => 'TESTTXN123',
            'IssuingBank' => '',
            'TxnStatus' => '1',
            'AuthCode' => '123456',
            'BankRefNo' => '',
            'TxnMessage' => '',
            'Param6' => '66',
            'Param7' => '77',
        ];

        $this->getHttpRequest()->request->replace($response);

        $data = $this->request->getData();

        $this->assertEquals($data['computed_hash_value'], $data['HashValue2']);
    }

    public function testSendData()
    {
        $this->request->initialize([
            'card' => [
                'email' => ($email = 'xuding@spacebib.com'),
                'name' => ($fullName = 'Xu Ding'),
                'phone' => ($phone = '123456789'),
            ],
            'merchantId' => ($merchantId = '123'),
            'merchantPassword' => ($merchantPwd = 'abc123'),
            'amount' => ($amount = 10000.0),
            'currency' => ($currency = 'IDR'),
            'description' => ($description = 'Marina Run 2016'),
            'transactionId' => ($transactionId = 12),
            'returnUrl' => ($returnUrl = 'https://www.example.com/return?yes&no'),
            'notifyUrl' => ($notifyUrl = 'https://www.example.com/notify?yes&no'),
            'clientIp' => ($ip = '127.0.0.1'),
            'invoiceNo' => ($invoiceNo = '20191212-123123')
        ]);

        $data = [
            'TransactionType' => 'SALE',
            'PymtMethod' => 'ANY',
            'ServiceID' => 'S22',
            'PaymentID' => 'PAYTEST123',
            'OrderNumber' => '0007901000',
            'Amount' => '12.34',
            'CurrencyCode' => 'MYR',
            'HashValue2' => '8795c391a3091585295906a0694d9d13d29c38aa3d4d4521385f222ac19fb773',
            'TxnID' => 'TESTTXN123',
            'IssuingBank' => '',
            'TxnStatus' => '1',
            'AuthCode' => '123456',
            'BankRefNo' => '',
            'TxnMessage' => '',
            'Param6' => '66',
            'Param7' => '77',
            'computed_hash_value' => '8795c391a3091585295906a0694d9d13d29c38aa3d4d4521385f222ac19fb773'
        ];

        $this->assertInstanceOf(CompletePurchaseResponse::class, $this->request->sendData($data));
    }

}
