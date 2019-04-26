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
    const PAGINATION_MAX = 500;

    /**
     * @return ItemApi
     * @throws ApiException
     */
    public function getMdmApi()
    {
        return new ItemApi($this->getHttpClient(), $this->getConfig());
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
            $limit = self::PAGINATION_MAX,
            TRUE
        );

        foreach ($result->getRows() as $item) {
            if ($item->getSku() == $sku) {
                $mdm_item = $item;
                break;
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
            $sku = NULL,
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
            $start = 0,
            $limit = self::PAGINATION_MAX,
            TRUE
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
     * @return Item[]
     * @throws ApiException
     */
    public function getAllItems($start, $language = 'it')
    {
        $result = $this->getMdmApi()->itemsGet(
            $language,
            $sku = NULL,
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
            $start,
            $limit = self::PAGINATION_MAX,
            TRUE
        );

        return $result->getRows();
    }

}