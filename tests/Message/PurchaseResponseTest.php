<?php

use Omnipay\Eghl\Message\AbstractRequest;
use Omnipay\Eghl\Message\PurchaseResponse;
use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    /**
     * @var PurchaseResponse
     */
    public $response;

    protected function setUp()
    {
        parent::setUp();

    }

    public function testBuild()
    {
        $email = 'xuding@spacebib.com';
        $fullName = 'Xu Ding';
        $phone = '123456789';
        $merchantId = '123';
        $merchantPwd = 'p123';
        $currency = 'IDR';
        $description = 'Marina Run 2016';
        $transactionId = 12;
        $ip = '127.0.0.1';
        $invoiceNo = '20191212-123123';
        $amount = '10000.00';
        $returnUrl = 'https://www.example.com/return?yes;no';
        $notifyUrl = 'https://www.example.com/notify?yes;no';

        $data = [
            'TransactionType' => 'SALE',
            'PymtMethod' => 'ANY',
            'ServiceID' => $merchantId,
            'PaymentID' => $transactionId,
            'OrderNumber' => $invoiceNo,
            'PaymentDesc' => $description,
            'MerchantReturnURL' => $returnUrl,
            'MerchantCallBackURL' => $notifyUrl,
            'Amount' => $amount,
            'CurrencyCode' => $currency,
            'HashValue' =>
                hash('sha256',
                    $merchantPwd .
                    $merchantId .
                    $transactionId .
                    $returnUrl .
                    $notifyUrl .
                    $amount .
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

        $request = $this
            ->getMockBuilder(AbstractRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request
            ->expects($this->any())
            ->method('getTestMode')
            ->will($this->returnValue(true));

        $response = new PurchaseResponse($request, $data);

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertFalse($response->isCancelled());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getTransactionReference());
        $this->assertTrue($response->isTransparentRedirect());
        $this->assertSame($transactionId, $response->getTransactionId());
        $this->assertSame('https://test2pay.ghl.com/IPGSG/Payment.aspx', $response->getRedirectUrl());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertEquals($data, $response->getRedirectData());
    }

    public function testGetRedirectUrl()
    {
        $request = $this
            ->getMockBuilder(AbstractRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request
            ->expects($this->at(0))
            ->method('getTestMode')
            ->will($this->returnValue(false));

        $request
            ->expects($this->at(1))
            ->method('getTestMode')
            ->will($this->returnValue(true));

        $response = new PurchaseResponse($request, []);

        $this->assertEquals(
            'https://securepay.e-ghl.com/IPG/Payment.aspx',
            $response->getRedirectUrl()
        );

        $this->assertEquals(
            'https://test2pay.ghl.com/IPGSG/Payment.aspx',
            $response->getRedirectUrl()
        );
    }
}
