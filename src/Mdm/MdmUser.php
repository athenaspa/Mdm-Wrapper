<?php

namespace Athena\Mdm;

use Swagger\Client\Api\UserApi;
use Swagger\Client\ApiException;

/**
 * Class MdmUser
 * @package Athena\Mdm
 */
class MdmUser extends MdmBase implements MdmInterface
{
    /**
     * @return UserApi
     * @throws ApiException
     */
    public function getMdmApi()
    {
        return new UserApi($this->getHttpClient(), $this->getConfig());
    }
    
}