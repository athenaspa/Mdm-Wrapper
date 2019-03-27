<?php

namespace Athena\Mdm;

use Swagger\Client\Api\UserApi;

/**
 * Class MdmUser
 * @package Athena\Mdm
 */
class MdmUser extends MdmBase implements MdmInterface
{
    /**
     * @return \Swagger\Client\Api\UserApi
     * @throws \Swagger\Client\ApiException
     */
    public function getMdmApi()
    {
        return new UserApi($this->getHttpClient(), $this->config);
    }
    
}