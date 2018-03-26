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
        return strtoupper($this->data['HashValue2']) ==  strtoupper($this->data['computed_hash_value']);
    }

    public function getTransactionId()
    {
        return $this->data['PaymentID'];
    }

    public function getTransactionReference()
    {
        return $this->data['TxnID'];
    }


}