<?php

namespace Omnipay\Eghl\Message;


use Omnipay\Common\Message\ResponseInterface;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->httpRequest->request->all();

        $data['computed_hash_value'] = $this->hashValue($data);

        return $data;
    }

    public function sendData($data)
    {
        // TODO: Implement sendData() method.
    }

    private function hashValue($data)
    {
        $this->validate('merchantId', 'merchantPassword');

        return hash('sha256',
            $this->getMerchantPassword() .
            $this->emptyIfNotFound($data, 'TxnID') .
            $this->emptyIfNotFound($data, 'ServiceID') .
            $this->emptyIfNotFound($data, 'PaymentID') .
            $this->emptyIfNotFound($data, 'TxnStatus') .
            $this->emptyIfNotFound($data, 'Amount') .
            $this->emptyIfNotFound($data, 'CurrencyCode') .
            $this->emptyIfNotFound($data, 'AuthCode') .
            $this->emptyIfNotFound($data, 'OrderNumber') .
            $this->emptyIfNotFound($data, 'Param6') .
            $this->emptyIfNotFound($data, 'Param7')
        );
    }

    protected function emptyIfNotFound($haystack, $needle)
    {
        if (!isset($haystack[$needle])) {
            return '';
        }
        return $haystack[$needle];
    }
}