<?php

namespace Omnipay\Eghl;

use Omnipay\Tests\TestCase;


class GatewayTest extends TestCase
{
    /** @var Gateway */
    protected $gateway;

    /** @var array */
    private $options;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = [
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
        ];
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());

        $this->assertTrue($response->isRedirect());
    }

    public function testCompletePurchase()
    {
        $this->getHttpRequest()->request->replace([
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
            'computed_hash_value' => '8795c391a3091585295906a0694d9d13d29c38aa3d4d4521385f222ac19fb773',
        ]);


        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());

        $this->assertNotNull($response->getTransactionReference());
    }
}
