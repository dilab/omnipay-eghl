<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 26/3/18
 * Time: 10:07 AM
 */

use Omnipay\Eghl\Message\CompletePurchaseResponse;
use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    /**
     * @var CompletePurchaseResponse
     */
    public $response;

    protected function setUp()
    {
        parent::setUp();
    }

    public function testBuild()
    {
        $request = $this->getMockRequest();

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
            'TxnStatus' => '0',
            'AuthCode' => '123456',
            'BankRefNo' => '',
            'TxnMessage' => '',
            'Param6' => '66',
            'Param7' => '77',
            'computed_hash_value' => '8795c391a3091585295906a0694d9d13d29c38aa3d4d4521385f222ac19fb773',
        ];

        $this->response = new CompletePurchaseResponse($request, $data);

        $this->assertTrue($this->response->isSuccessful());
        $this->assertFalse($this->response->isPending());
        $this->assertFalse($this->response->isRedirect());
        $this->assertFalse($this->response->isTransparentRedirect());
        $this->assertFalse($this->response->isCancelled());
        $this->assertEquals('PAYTEST123', $this->response->getTransactionId());
        $this->assertEquals('TESTTXN123', $this->response->getTransactionReference());
    }
}
