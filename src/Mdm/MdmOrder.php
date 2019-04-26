<?php

namespace Athena\Mdm;

use Swagger\Client\Api\OrderApi;
use Swagger\Client\ApiException;

/**
 * Class MdmOrder
 * @package Athena\Mdm
 */
class MdmOrder extends MdmBase implements MdmInterface
{
    /**
     * @return OrderApi
     * @throws ApiException
     */
    public function getMdmApi()
    {
        return new OrderApi($this->getHttpClient(), $this->getConfig());
    }

}