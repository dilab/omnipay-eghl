<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 23/3/18
 * Time: 11:11 AM
 */

namespace Omnipay\Eghl\Message;


use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return (strtoupper($this->data['HashValue2']) == strtoupper($this->data['computed_hash_value'])) &&
            $this->data['TxnStatus'] === 0;
    }

    public function getTransactionId()
    {
        return $this->data['PaymentID'];
    }

    public function getTransactionReference()
    {
        return $this->data['TxnID'];
    }

    public function getMessage()
    {
        return isset($this->data['TxnMessage']) ? $this->data['TxnMessage'] : null;
    }

}