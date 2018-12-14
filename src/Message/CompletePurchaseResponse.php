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
            $this->data['TxnStatus'] == 0;
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
        if ((strtoupper($this->data['HashValue2']) != strtoupper($this->data['computed_hash_value']))) {
            return sprintf('HashValue2: %s does not match calculated HashValue:%s',
                strtoupper($this->data['HashValue2']),
                strtoupper($this->data['computed_hash_value'])
            );
        }

        if ($this->data['TxnStatus'] != 0) {
            return sprintf('Invalid TxnStatus: %s', $this->data['TxnStatus']);
        }

        if (isset($this->data['TxnMessage'])) {
            return $this->data['TxnMessage'];
        }

        return 'No Message is Returned from Gateway';
    }

}