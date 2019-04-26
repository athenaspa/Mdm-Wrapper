<?php

namespace Athena\Mdm;

use Swagger\Client\Api\OrderApi;

/**
 * Class MdmOrder
 * @package Athena\Mdm
 */
class MdmOrder extends MdmBase implements MdmInterface
{
    /**
     * @return \Swagger\Client\Api\OrderApi
     * @throws \Swagger\Client\ApiException
     */
    public function getMdmApi()
    {
        return new OrderApi($this->getHttpClient());
    }

}