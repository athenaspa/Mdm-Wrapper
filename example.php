<?php

require_once 'vendor/autoload.php';

use Swagger\Client\ApiException;
use Swagger\Client\Model\Asset;
use Swagger\Client\Model\BrandModelYear;
use Swagger\Client\Model\Item;

/**
 * Wrapper Element
 */
$api = new Athena\Mdm\MdmItem();


/**
 * Get all product items
 */
$pagination = $api->getItemsPagination();

$totPages = ceil($pagination['totalItems'] / $pagination['rowCount']);

for ($i = $pagination['currentPage']; $i <= $totPages; $i++) {

    $currentPage = ($i * $pagination['rowCount']);

    print 'sto processando pagina ' . $i . ' di ' . $totPages . "\n";

    try {
        $items = $api->getAllItems($currentPage);
    } catch (ApiException $e) {
        print $e->getMessage();
    }

    foreach ($items as $item) {
        /** @var Item $item */
        if ($item instanceof Item) {

            print 'Sto processando item ' . $item->getSku() . "\n";

            print $item->getBrand();
            print $item->getStock();

            $assets = $item->getAssets();
            for ($count = 0; $count < count($assets); $count++) {
                if (isset($assets[$count])) {
                    if (in_array($assets[$count]->getType(), array(Asset::TYPE_ITEM_IMAGE, Asset::TYPE_LIFESTYLE_IMAGE, Asset::TYPE_PACKING_IMAGE))) {
                        $_tmp_image[$count] = file_get_contents($assets[$count]->getUrls()->getOriginalFile());
                        $_file_image[$count] = "./" . $item->getSku() . "-" . sha1($item->getSku() . $count) . ".jpg";
                        file_put_contents($_file_image[$count], 80);
                    }
                }
            }

        }
    }
}


/**
 * brand model year for aftermarket products
 */
$itemApi = $api->getMdmApi();

/** @var BrandModelYear[] $associations */
$associations = $itemApi->associationsGet('P400485100058')->getRows();


foreach ($associations as $association) {
    print $association->getBrand();
    print $association->getModel();
    print $association->getVersion();
    print $association->getCc();
}