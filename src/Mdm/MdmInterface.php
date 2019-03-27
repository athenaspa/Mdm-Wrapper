<?php

namespace Athena\Mdm;

/**
 * Interface MdmInterface
 * @package Athena\Mdm
 */
interface MdmInterface
{
    /**
     * @return \Swagger\Client\Api\ItemApi|\Swagger\Client\Api\UserApi|\Swagger\Client\Api\OrderApi
     */
    public function getMdmApi();

}