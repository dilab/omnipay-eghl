<?php

namespace Omnipay\Eghl;


use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Eghl';
    }

    public function getDefaultParameters()
    {
        return [
            'merchantId' => '',
            'merchantPassword' => '',
        ];
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($merchantId)
    {
        return $this->setParameter('merchantId', $merchantId);
    }

    public function getMerchantPassword()
    {
        return $this->getParameter('merchantPassword');
    }

    public function setMerchantPassword($merchantPassword)
    {
        return $this->setParameter('merchantPassword', $merchantPassword);
    }

}