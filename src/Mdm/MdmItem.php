<?php

namespace Athena\Mdm;

use Swagger\Client\Api\ItemApi;
use Swagger\Client\ApiException;
use Swagger\Client\Model\Item;

/**
 * Class MdmItem
 * @package Athena\Mdm
 */
class MdmItem extends MdmBase implements MdmInterface
{

    /**
     * Pagination Limit
     */
    protected $limit = 500;

    /**
     * @return \Swagger\Client\Api\ItemApi
     * @throws \Swagger\Client\ApiException
     */
    public function getMdmApi()
    {
        return new ItemApi($this->getHttpClient(), $this->config);
    }

    /**
     * @param $sku
     * @return Item
     * @throws ApiException
     */
    public function getItemBySku($sku)
    {
        $language = 'it';
        $mdm_item = FALSE;

        $result = $this->getMdmApi()->itemsGet(
            $language,
            $sku,
            $name = NULL,
            $description = NULL,
            $minPrice = NULL,
            $maxPrice = NULL,
            $tags = NULL,
            $keywords = NULL,
            $commercialFamily = NULL,
            $createdBefore = NULL,
            $createdAfter = NULL,
            $updatedBefore = NULL,
            $updatedAfter = NULL,
            $start = '0',
            $limit = $this->limit,
            true
        );

        foreach ($result->getRows() as $item) {
            if ($item->getSku() == $sku) {
                $mdm_item = $item;
            }
        }

        return $mdm_item;
    }

    /**
     * @param string $language
     * @return array
     * @throws ApiException
     */
    public function getItemsPagination($language = 'it')
    {
        $result = $this->getMdmApi()->itemsGet(
            $language,
            $sku = null,
            $name = null,
            $description = null,
            $minPrice = null,
            $maxPrice = null,
            $tags = null,
            $keywords = null,
            $commercialFamily = null,
            $createdBefore = null,
            $createdAfter = null,
            $updatedBefore = null,
            $updatedAfter = null,
            $start = 0,
            $limit = $this->limit,
            true
        );

        return [
            'rowCount' => $result->getRowCount(),
            'totalItems' => $result->getTotal(),
            'currentPage' => $result->getCurrent(),
        ];
    }

    /**
     * @param $start
     * @param string $language
     * @return \Swagger\Client\Model\Item[]
     * @throws ApiException
     */
    public function getAllItems($start, $language = 'it')
    {
        $result = $this->getMdmApi()->itemsGet(
            $language,
            $sku = null,
            $name = null,
            $description = null,
            $minPrice = null,
            $maxPrice = null,
            $tags = null,
            $keywords = null,
            $commercialFamily = null,
            $createdBefore = null,
            $createdAfter = null,
            $updatedBefore = null,
            $updatedAfter = null,
            $start,
            $limit = $this->limit,
            true
        );

        return $result->getRows();
    }

}