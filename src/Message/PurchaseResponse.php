<?php

namespace Omnipay\Eghl\Message;


use Omnipay\Common\Message\AbstractResponse;

class PurchaseResponse extends AbstractResponse
{
    private $testUrl = 'https://test2pay.ghl.com/IPGSG/Payment.aspx';

    private $liveUrl = 'https://test2pay.ghl.com/IPGSG/Payment.aspx';

    public function isSuccessful()
    {
        // TODO: Implement isSuccessful() method.
    }

}